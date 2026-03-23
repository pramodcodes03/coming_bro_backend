<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\AcceptedDriver;
use App\Models\IntercityOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IntercityOrderController extends Controller
{
    /**
     * Get single intercity order.
     */
    public function show(string $id): JsonResponse
    {
        $order = IntercityOrder::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Intercity order not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Intercity order retrieved successfully.',
            'data' => $order,
        ]);
    }

    /**
     * Update intercity order.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $order = IntercityOrder::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Intercity order not found.',
                'data' => null,
            ], 404);
        }

        $fillableFields = (new IntercityOrder())->getFillable();
        $updateData = $request->only($fillableFields);
        unset($updateData['id']);

        $order->fill($updateData);
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Intercity order updated successfully.',
            'data' => $order->fresh(),
        ]);
    }

    /**
     * Accept intercity ride.
     */
    public function accept(Request $request, string $orderId): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|string',
            'offer_amount' => 'nullable|string',
            'suggested_time' => 'nullable|string',
            'suggested_date' => 'nullable|string',
        ]);

        $order = IntercityOrder::find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Intercity order not found.',
                'data' => null,
            ], 404);
        }

        // Check if driver already accepted this order
        $existing = AcceptedDriver::where('order_id', $orderId)
            ->where('driver_id', $request->driver_id)
            ->where('order_type', 'intercity')
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Driver has already accepted this intercity order.',
                'data' => $existing,
            ], 409);
        }

        $acceptedDriver = AcceptedDriver::create([
            'order_id' => $orderId,
            'order_type' => 'intercity',
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
            'message' => 'Intercity ride accepted successfully.',
            'data' => $acceptedDriver,
        ]);
    }

    /**
     * Get accepted driver record for an intercity order.
     */
    public function getAcceptedDriver(string $orderId, string $driverId): JsonResponse
    {
        $acceptedDriver = AcceptedDriver::where('order_id', $orderId)
            ->where('driver_id', $driverId)
            ->where('order_type', 'intercity')
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
     * Get nearby intercity/freight orders using Haversine formula.
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

        $query = IntercityOrder::selectRaw(
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
            'message' => 'Nearby intercity orders retrieved successfully.',
            'data' => $orders,
        ]);
    }

    /**
     * Check if this is the user's first intercity order.
     */
    public function firstOrder(string $userId): JsonResponse
    {
        $orderCount = IntercityOrder::where('user_id', $userId)->count();

        return response()->json([
            'success' => true,
            'message' => 'First intercity order check completed.',
            'data' => [
                'is_first_order' => $orderCount <= 1,
                'order_count' => $orderCount,
            ],
        ]);
    }
}
