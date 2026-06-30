<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\AcceptedDriver;
use App\Models\CancelReason;
use App\Models\DriverUser;
use App\Models\Order;
use App\Events\OrderUpdated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Get orders for current driver (with filters).
     */
    public function index(Request $request): JsonResponse
    {
        $driver = $request->user();

        $query = Order::where('driver_id', $driver->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->orderBy('created_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully.',
            'data' => $orders,
        ]);
    }

    /**
     * Get single order.
     */
    public function show(string $id): JsonResponse
    {
        $order = Order::with(['driver', 'customer', 'acceptedDrivers'])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully.',
            'data' => $order,
        ]);
    }

    /**
     * Update order.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
                'data' => null,
            ], 404);
        }

        $fillableFields = (new Order())->getFillable();
        $updateData = $request->only($fillableFields);
        unset($updateData['id']);

        $wasCompleted = $this->isCompletedStatus($order->status);

        $order->fill($updateData);
        $order->save();

        // One fare everywhere — if the driver's bid/accept changes the fare,
        // keep offer_rate and final_rate identical so both apps show one price.
        if ($request->hasAny(['final_rate', 'offer_rate'])) {
            $fare = Order::singleFare($request->input('offer_rate'), $request->input('final_rate'));
            if ($fare !== null && ((float) $order->offer_rate !== $fare || (float) $order->final_rate !== $fare)) {
                $order->offer_rate = $fare;
                $order->final_rate = $fare;
                $order->save();
            }
        }

        // When a ride first transitions into a completed state, consume one of
        // the driver's recharge rides (floored at 0 — never goes negative).
        if (! $wasCompleted && $this->isCompletedStatus($order->status)) {
            $this->consumeDriverRide($order);
        }

        event(new OrderUpdated($order));

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully.',
            'data' => $order->fresh(),
        ]);
    }

    /** Admin-managed cancel reasons shown on the driver cancel sheet. */
    public function cancelReasons(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Cancel reasons retrieved successfully.',
            'data' => CancelReason::forAudience('driver')->get(['id', 'reason', 'applies_to']),
        ]);
    }

    /**
     * Driver cancels an assigned ride and records why. Sends the chosen reason
     * (and optional free text). Only the assigned driver can cancel, and only
     * before the ride is completed/cancelled.
     */
    public function cancel(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:255',
            'cancel_note'   => 'nullable|string|max:500',
        ]);

        $order = Order::where('id', $id)
            ->where('driver_id', $request->user()->id)
            ->firstOrFail();

        $current = strtolower(trim((string) $order->status));
        if (in_array($current, ['ride completed', 'completed', 'ride canceled', 'ride cancelled', 'cancelled', 'canceled'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'This ride can no longer be cancelled.',
                'data' => $order,
            ], 409);
        }

        $reason = trim($validated['cancel_reason']);
        if (! empty($validated['cancel_note'])) {
            $reason .= ' — '.trim($validated['cancel_note']);
        }

        $order->status = Order::STATUS_RIDE_CANCELED;
        $order->cancel_reason = $reason;
        $order->cancelled_by = 'driver';
        $order->cancelled_at = now();
        $order->update_date = now();
        $order->save();

        event(new OrderUpdated($order));

        Log::channel('stack')->info('[RIDE_FLOW] Driver cancelled ride', [
            'order_id' => $order->id,
            'driver_id' => $request->user()->id,
            'cancel_reason' => $reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ride cancelled successfully.',
            'data' => $order,
        ]);
    }

    /**
     * Completed-ride status strings (case-insensitive). Mirrors the buckets the
     * admin God's Eye view treats as completed.
     */
    private const COMPLETED_STATUSES = ['completed', 'ride completed', 'ride end', 'end ride', 'finished'];

    private function isCompletedStatus(?string $status): bool
    {
        return in_array(strtolower(trim((string) $status)), self::COMPLETED_STATUSES, true);
    }

    /**
     * Consume one recharge ride from the order's assigned driver, never letting
     * remaining_rides drop below zero.
     */
    private function consumeDriverRide(Order $order): void
    {
        if ($order->driver_id === null) {
            return;
        }

        DriverUser::whereKey($order->driver_id)
            ->where('remaining_rides', '>', 0)
            ->decrement('remaining_rides');

        // When the quota hits zero, take the driver offline so the matching
        // engine stops sending them requests until they recharge.
        $driver = DriverUser::find($order->driver_id);
        if ($driver && (int) $driver->remaining_rides <= 0 && $driver->is_online) {
            $driver->is_online = false;
            $driver->save();
        }
    }

    /**
     * Accept ride (create accepted_driver record).
     */
    public function accept(Request $request, string $orderId): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|string',
            'offer_amount' => 'nullable|string',
            'suggested_time' => 'nullable|string',
            'suggested_date' => 'nullable|string',
        ]);

        // Cannot accept a ride with zero ride quota — enforced server-side so it
        // cannot be bypassed from the app.
        $authDriver = $request->user();
        if ($authDriver && ! $authDriver->hasRideBalance()) {
            return response()->json([
                'success' => false,
                'message' => 'You have no rides left. Recharge to accept rides.',
                'error_code' => 'RIDE_RECHARGE_REQUIRED',
                'data' => null,
            ], 403);
        }

        Log::channel('stack')->info('[RIDE_FLOW] Driver attempting to ACCEPT ride', [
            'order_id' => $orderId,
            'driver_id' => $request->driver_id,
            'offer_amount' => $request->offer_amount,
        ]);

        $order = Order::find($orderId);

        if (!$order) {
            Log::channel('stack')->warning('[RIDE_FLOW] Accept FAILED — order not found', [
                'order_id' => $orderId,
                'driver_id' => $request->driver_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
                'data' => null,
            ], 404);
        }

        // Check if driver already accepted this order
        $existing = AcceptedDriver::where('order_id', $orderId)
            ->where('driver_id', $request->driver_id)
            ->where('order_type', 'city')
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Driver has already accepted this order.',
                'data' => $existing,
            ], 409);
        }

        $acceptedDriver = AcceptedDriver::create([
            'order_id' => $orderId,
            'order_type' => 'city',
            'driver_id' => $request->driver_id,
            'offer_amount' => $request->offer_amount,
            'suggested_time' => $request->suggested_time,
            'suggested_date' => $request->suggested_date,
            'accepted_reject_time' => now(),
        ]);

        // Add driver_id to the order's accepted_driver_id array
        $acceptedIds = $order->accepted_driver_id ?? [];
        if (!in_array($request->driver_id, $acceptedIds)) {
            $acceptedIds[] = $request->driver_id;
            $order->accepted_driver_id = $acceptedIds;
            $order->save();
        }

        event(new OrderUpdated($order->fresh()));

        Log::channel('stack')->info('[RIDE_FLOW] Driver ACCEPTED ride successfully', [
            'order_id' => $orderId,
            'driver_id' => $request->driver_id,
            'accepted_driver_record_id' => $acceptedDriver->id,
            'total_drivers_accepted' => count($acceptedIds),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ride accepted successfully.',
            'data' => $acceptedDriver,
        ]);
    }

    /**
     * Get accepted driver record for an order.
     */
    public function getAcceptedDriver(string $orderId, string $driverId): JsonResponse
    {
        $acceptedDriver = AcceptedDriver::where('order_id', $orderId)
            ->where('driver_id', $driverId)
            ->where('order_type', 'city')
            ->with('driver')
            ->first();

        if (!$acceptedDriver) {
            return response()->json([
                'success' => false,
                'message' => 'Accepted driver record not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Accepted driver retrieved successfully.',
            'data' => $acceptedDriver,
        ]);
    }

    /**
     * Get nearby orders using Haversine formula.
     */
    public function nearby(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric',
            'service_id' => 'nullable|integer',
            'zone_ids' => 'nullable|array',
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;
        $radius = $request->radius ?? 10; // km

        $driver = $request->user();

        // A driver with zero ride quota is hidden from ride matching and must
        // not receive any City Ride requests until they recharge.
        if ($driver && ! $driver->hasRideBalance()) {
            return response()->json([
                'success' => true,
                'message' => 'No ride quota — recharge to receive ride requests.',
                'data' => [],
            ]);
        }

        // Haversine distance, clamped to acos's [-1,1] domain. Filtered in WHERE
        // (not HAVING) so it's valid on every DB driver (sqlite rejects a HAVING
        // clause on a non-aggregate query; MySQL allows it).
        $haversine = '(6371 * acos(least(1, greatest(-1, '
            .'cos(radians(?)) * cos(radians(source_latitude)) '
            .'* cos(radians(source_longitude) - radians(?)) '
            .'+ sin(radians(?)) * sin(radians(source_latitude))))))';
        $point = [$lat, $lng, $lat];

        $query = Order::selectRaw("*, {$haversine} AS distance", $point)
            ->whereRaw("{$haversine} <= ?", array_merge($point, [$radius]))
            ->where('status', Order::STATUS_RIDE_PLACED);

        if ($request->has('service_id') && $request->service_id) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->has('zone_ids') && !empty($request->zone_ids)) {
            $zoneIds = $request->zone_ids;
            $query->where(function ($q) use ($zoneIds) {
                foreach ($zoneIds as $zoneId) {
                    $q->orWhereJsonContains('zone', ['id' => $zoneId]);
                }
            });
        }

        $orders = $query->orderBy('distance')->get();

        return response()->json([
            'success' => true,
            'message' => 'Nearby orders retrieved successfully.',
            'data' => $orders,
        ]);
    }

    /**
     * Check if this is the user's first order.
     */
    public function firstOrder(string $userId): JsonResponse
    {
        $orderCount = Order::where('user_id', $userId)->count();

        return response()->json([
            'success' => true,
            'message' => 'First order check completed.',
            'data' => [
                'is_first_order' => $orderCount <= 1,
                'order_count' => $orderCount,
            ],
        ]);
    }
}
