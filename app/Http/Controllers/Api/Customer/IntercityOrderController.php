<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\AcceptedDriver;
use App\Models\IntercityOrder;
use App\Models\Referral;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IntercityOrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'source_city' => 'nullable|string',
            'source_location_name' => 'nullable|string',
            'destination_city' => 'nullable|string',
            'destination_location_name' => 'nullable|string',
            'payment_type' => 'nullable|string',
            'source_latitude' => 'nullable|numeric',
            'source_longitude' => 'nullable|numeric',
            'destination_latitude' => 'nullable|numeric',
            'destination_longitude' => 'nullable|numeric',
            'intercity_service_id' => 'nullable|integer',
            'loading_unloading_charges' => 'nullable',
            'offer_rate' => 'nullable',
            'final_rate' => 'nullable',
            'distance' => 'nullable',
            'distance_type' => 'nullable|string',
            'status' => 'nullable|string',
            'loading' => 'nullable|boolean',
            'unloading' => 'nullable|boolean',
            'parcel_dimension' => 'nullable|string',
            'parcel_weight' => 'nullable',
            'freight_weight' => 'nullable',
            'parcel_image' => 'nullable|array',
            'otp' => 'nullable|string',
            'tax_list' => 'nullable|array',
            'coupon' => 'nullable|array',
            'freight_vehicle' => 'nullable|array',
            'intercity_service' => 'nullable|array',
            'when_dates' => 'nullable|string',
            'when_time' => 'nullable|string',
            'number_of_passenger' => 'nullable|string',
            'comments' => 'nullable|string',
            'some_one_else' => 'nullable|array',
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

        $order = IntercityOrder::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Intercity order created successfully.',
            'data' => $order,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $order = IntercityOrder::with(['driver'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Intercity order retrieved successfully.',
            'data' => $order,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $order = IntercityOrder::findOrFail($id);
        $order->update($request->only([
            'status', 'driver_id', 'payment_status', 'payment_type',
            'final_rate', 'otp', 'accepted_driver_id', 'rejected_driver_id',
            'ride_hold_time', 'holding_charges', 'total_holding_charges',
            'position_latitude', 'position_longitude', 'position_geohash',
        ]));
        $order->update_date = now();
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Intercity order updated successfully.',
            'data' => $order->fresh(),
        ]);
    }

    public function acceptedDrivers(string $orderId): JsonResponse
    {
        $drivers = AcceptedDriver::with('driver')
            ->where('order_id', $orderId)
            ->where('order_type', 'intercity')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Accepted drivers retrieved.',
            'data' => $drivers,
        ]);
    }

    public function paymentStatus(string $id): JsonResponse
    {
        $order = IntercityOrder::select('id', 'payment_status', 'status')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Payment status retrieved.',
            'data' => $order,
        ]);
    }

    public function firstOrder(string $userId): JsonResponse
    {
        $isFirst = !IntercityOrder::where('user_id', $userId)->where('payment_status', true)->exists();

        return response()->json([
            'success' => true,
            'message' => 'First intercity order check completed.',
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
