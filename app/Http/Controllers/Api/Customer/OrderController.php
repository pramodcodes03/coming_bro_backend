<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\AcceptedDriver;
use App\Models\Order;
use App\Models\Referral;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'source_location_name' => 'nullable|string',
            'destination_location_name' => 'nullable|string',
            'payment_type' => 'nullable|string',
            'source_latitude' => 'nullable|numeric',
            'source_longitude' => 'nullable|numeric',
            'destination_latitude' => 'nullable|numeric',
            'destination_longitude' => 'nullable|numeric',
            'service_id' => 'nullable|integer',
            'offer_rate' => 'nullable',
            'final_rate' => 'nullable',
            'distance' => 'nullable',
            'duration' => 'nullable',
            'distance_type' => 'nullable|string',
            'status' => 'nullable|string',
            'otp' => 'nullable|string',
            'is_ac_selected' => 'nullable|boolean',
            'tax_list' => 'nullable|array',
            'some_one_else' => 'nullable|array',
            'coupon' => 'nullable|array',
            'service' => 'nullable|array',
            'admin_commission' => 'nullable|array',
            'zone' => 'nullable|array',
            'zone_id' => 'nullable|integer',
            'position_latitude' => 'nullable|numeric',
            'position_longitude' => 'nullable|numeric',
            'position_geohash' => 'nullable|string',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['payment_status'] = false;
        $data['created_date'] = now();
        $data['update_date'] = now();

        $order = Order::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully.',
            'data' => $order,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $order = Order::with(['driver'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully.',
            'data' => $order,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        $order->update($request->only([
            'status', 'driver_id', 'payment_status', 'payment_type',
            'final_rate', 'otp', 'accepted_driver_id', 'rejected_driver_id',
            'ride_hold_time', 'holding_charges', 'total_holding_charges',
            'position_latitude', 'position_longitude', 'position_geohash',
            'total_ride_time', 'ac_non_ac_charges',
        ]));
        $order->update_date = now();
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully.',
            'data' => $order->fresh(),
        ]);
    }

    public function acceptedDrivers(string $orderId): JsonResponse
    {
        $drivers = AcceptedDriver::with('driver')
            ->where('order_id', $orderId)
            ->where('order_type', 'city')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Accepted drivers retrieved.',
            'data' => $drivers,
        ]);
    }

    public function paymentStatus(string $id): JsonResponse
    {
        $order = Order::select('id', 'payment_status', 'status')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Payment status retrieved.',
            'data' => $order,
        ]);
    }

    public function firstOrder(string $userId): JsonResponse
    {
        $isFirst = !Order::where('user_id', $userId)->where('payment_status', true)->exists();

        return response()->json([
            'success' => true,
            'message' => 'First order check completed.',
            'data' => ['is_first_order' => $isFirst],
        ]);
    }

    public function updateReferralAmount(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer', 'amount' => 'required|numeric']);

        $referral = Referral::where('referral_by', $request->user_id)->first();
        if ($referral) {
            $referral->referral_amount = (float) $referral->referral_amount + (float) $request->amount;
            $referral->total_referral_amount = (float) $referral->total_referral_amount + (float) $request->amount;
            $referral->save();
        }

        return response()->json(['success' => true, 'message' => 'Referral amount updated.', 'data' => $referral]);
    }
}
