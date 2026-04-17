<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\AcceptedDriver;
use App\Models\DriverUser;
use App\Models\Order;
use App\Models\Referral;
use App\Models\WalletTransaction;
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

        $wasPaymentPending = !$order->payment_status;
        $isCompletingPayment = $request->has('payment_status')
            && filter_var($request->payment_status, FILTER_VALIDATE_BOOLEAN)
            && $wasPaymentPending;

        $order->update($request->only([
            'status', 'driver_id', 'payment_status', 'payment_type',
            'final_rate', 'otp', 'accepted_driver_id', 'rejected_driver_id',
            'ride_hold_time', 'holding_charges', 'total_holding_charges',
            'position_latitude', 'position_longitude', 'position_geohash',
            'total_ride_time', 'ac_non_ac_charges', 'coupon',
        ]));
        $order->update_date = now();
        $order->save();

        if ($isCompletingPayment && $order->driver_id) {
            $this->processPaymentComplete($order->fresh());
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully.',
            'data' => $order->fresh(),
        ]);
    }

    private function processPaymentComplete(Order $order): void
    {
        $finalRate = (float) ($order->final_rate ?? 0);
        $couponAmount = 0.0;

        if ($order->coupon) {
            $coupon = is_array($order->coupon) ? $order->coupon : json_decode($order->coupon, true);
            if ($coupon && isset($coupon['discount'])) {
                $couponAmount = (float) $coupon['discount'];
            }
        }

        $holdingCharges = (float) ($order->total_holding_charges ?? 0);
        $netAmount = $finalRate - $couponAmount + $holdingCharges;

        $commissionAmount = 0.0;
        if ($order->admin_commission) {
            $commission = is_array($order->admin_commission) ? $order->admin_commission : json_decode($order->admin_commission, true);
            if ($commission && isset($commission['type'])) {
                if ($commission['type'] === 'fix') {
                    $commissionAmount = (float) ($commission['amount'] ?? 0);
                } else {
                    $commissionAmount = ($netAmount * (float) ($commission['amount'] ?? 0)) / 100;
                }
            }
        }

        WalletTransaction::create([
            'amount' => $netAmount,
            'payment_type' => $order->payment_type ?? 'cash',
            'transaction_id' => $order->id,
            'user_id' => $order->driver_id,
            'user_type' => 'driver',
            'order_type' => 'city',
            'note' => 'Ride amount credited',
        ]);
        DriverUser::where('id', $order->driver_id)->increment('wallet_amount', $netAmount);

        if ($commissionAmount > 0) {
            WalletTransaction::create([
                'amount' => -$commissionAmount,
                'payment_type' => $order->payment_type ?? 'cash',
                'transaction_id' => $order->id,
                'user_id' => $order->driver_id,
                'user_type' => 'driver',
                'order_type' => 'city',
                'note' => 'Admin commission debited',
            ]);
            DriverUser::where('id', $order->driver_id)->decrement('wallet_amount', $commissionAmount);
        }
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
