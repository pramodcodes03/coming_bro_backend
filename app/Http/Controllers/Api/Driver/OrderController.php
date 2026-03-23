<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\AcceptedDriver;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        $orders = $query->orderBy('created_at', 'desc')->get();

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

        $order->fill($updateData);
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully.',
            'data' => $order->fresh(),
        ]);
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

        $order = Order::find($orderId);

        if (!$order) {
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
            'service_id' => 'nullable|string',
            'zone_ids' => 'nullable|array',
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;
        $radius = $request->radius ?? 10; // km

        $query = Order::selectRaw(
            "*, (6371 * acos(cos(radians(?)) * cos(radians(source_latitude)) * cos(radians(source_longitude) - radians(?)) + sin(radians(?)) * sin(radians(source_latitude)))) AS distance",
            [$lat, $lng, $lat]
        )
            ->having('distance', '<=', $radius)
            ->where('status', 'ride_placed');

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
