<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\AcceptedDriver;
use App\Models\CancelReason;
use App\Models\DriverUser;
use App\Models\Order;
use App\Models\Referral;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Events\OrderUpdated;
use App\Events\NewOrderPlaced;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['driver'])
            ->where('user_id', $request->user()->id);


        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', filter_var($request->payment_status, FILTER_VALIDATE_BOOLEAN));
        }

        $orders = $query->orderBy('created_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully.',
            'data' => $orders,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        Log::debug($request->all());

        // The Flutter app sends Firebase-style camelCase keys (and some nested
        // objects). Map every incoming key to the matching DB column so no data
        // is silently dropped. For each column we accept the snake_case key first,
        // then fall back to the camelCase key the app actually sends.
        $data = $this->mapOrderPayload($request);

        $data['user_id'] = $request->user()->id;
        $data['payment_status'] = false;
        $data['created_date'] = now();
        $data['update_date'] = now();

        // One fare everywhere — keep offer_rate (customer view) and final_rate
        // (driver view + payment) identical so both apps show the same price.
        $fare = Order::singleFare($data['offer_rate'] ?? null, $data['final_rate'] ?? null);
        if ($fare !== null) {
            $data['offer_rate'] = $fare;
            $data['final_rate'] = $fare;
        }

        $order = Order::create($data);

        event(new OrderUpdated($order));

        // Notify all online drivers in real time that a new ride is available.
        if ($order->status === Order::STATUS_RIDE_PLACED) {
            event(new NewOrderPlaced($order));
        }

        Log::channel('stack')->info('[RIDE_FLOW] Customer created ride request', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'status' => $order->status,
            'service_id' => $order->service_id,
            'zone_id' => $order->zone_id,
            'source' => [
                'name' => $order->source_location_name,
                'lat' => $order->source_latitude,
                'lng' => $order->source_longitude,
            ],
            'destination' => [
                'name' => $order->destination_location_name,
                'lat' => $order->destination_latitude,
                'lng' => $order->destination_longitude,
            ],
            'offer_rate' => $order->offer_rate,
            'created_at' => now()->toDateTimeString(),
        ]);

        if ($order->status !== Order::STATUS_RIDE_PLACED) {
            Log::channel('stack')->warning('[RIDE_FLOW] Order status is NOT "Ride Placed" — drivers will NOT see this ride in nearby search', [
                'order_id' => $order->id,
                'status' => $order->status,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully.',
            'data' => $order,
        ], 201);
    }

    /** Admin-managed cancel reasons shown on the customer cancel sheet. */
    public function cancelReasons(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Cancel reasons retrieved successfully.',
            'data' => CancelReason::forAudience('customer')->get(['id', 'reason', 'applies_to']),
        ]);
    }

    /**
     * Cancel a ride and record why. The customer app sends the chosen reason
     * (and optional free text when "My reason is not listed" is picked). Only
     * the order's owner can cancel it, and only before it is completed/cancelled.
     */
    public function cancel(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:255',
            'cancel_note'   => 'nullable|string|max:500',
        ]);

        $order = Order::where('id', $id)
            ->where('user_id', $request->user()->id)
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
        $order->cancelled_by = 'customer';
        $order->cancelled_at = now();
        $order->update_date = now();
        $order->save();

        event(new OrderUpdated($order));

        Log::channel('stack')->info('[RIDE_FLOW] Customer cancelled ride', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'cancel_reason' => $reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ride cancelled successfully.',
            'data' => $order,
        ]);
    }

    /**
     * Normalise the incoming ride payload (camelCase / nested / snake_case)
     * into the snake_case DB columns of the orders table.
     */
    private function mapOrderPayload(Request $request): array
    {
        // column => list of candidate request keys (checked in order)
        $map = [
            'source_location_name'      => ['source_location_name', 'sourceLocationName'],
            'destination_location_name' => ['destination_location_name', 'destinationLocationName'],
            'payment_type'              => ['payment_type', 'paymentType'],
            'source_latitude'           => ['source_latitude', 'sourceLatitude'],
            'source_longitude'          => ['source_longitude', 'sourceLongitude'],
            'destination_latitude'      => ['destination_latitude', 'destinationLatitude'],
            'destination_longitude'     => ['destination_longitude', 'destinationLongitude'],
            'service_id'                => ['service_id', 'serviceId'],
            'offer_rate'                => ['offer_rate', 'offerRate'],
            'final_rate'                => ['final_rate', 'finalRate'],
            'distance'                  => ['distance'],
            'duration'                  => ['duration'],
            'distance_type'             => ['distance_type', 'distanceType'],
            'ride_hold_time'            => ['ride_hold_time', 'rideHoldTime'],
            'holding_charge_minute'     => ['holding_charge_minute', 'holdingChargeMinute'],
            'total_holding_charges'     => ['total_holding_charges', 'totalHoldingCharges'],
            'holding_charges'           => ['holding_charges', 'holdingCharges'],
            'status'                    => ['status'],
            'driver_id'                 => ['driver_id', 'driverId'],
            'ride_time_fare_per_minute' => ['ride_time_fare_per_minute', 'rideTimeFarePerMinute'],
            'total_ride_time'           => ['total_ride_time', 'totalRideTime'],
            'ac_non_ac_charges'         => ['ac_non_ac_charges', 'acNonAcCharges'],
            'otp'                       => ['otp'],
            'accepted_driver_id'        => ['accepted_driver_id', 'acceptedDriverId'],
            'rejected_driver_id'        => ['rejected_driver_id', 'rejectedDriverId'],
            'position_geohash'          => ['position_geohash', 'geohash'],
            'position_latitude'         => ['position_latitude', 'positionLatitude'],
            'position_longitude'        => ['position_longitude', 'positionLongitude'],
            'is_ac_selected'            => ['is_ac_selected', 'isAcSelected'],
            'tax_list'                  => ['tax_list', 'taxList'],
            'some_one_else'             => ['some_one_else', 'someOneElse'],
            'coupon'                    => ['coupon'],
            'service'                   => ['service'],
            'admin_commission'          => ['admin_commission', 'adminCommission'],
            'zone'                      => ['zone'],
            'zone_id'                   => ['zone_id', 'zoneId'],
        ];

        $data = [];
        foreach ($map as $column => $candidates) {
            foreach ($candidates as $key) {
                if ($request->has($key) && $request->input($key) !== null) {
                    $data[$column] = $request->input($key);
                    break;
                }
            }
        }

        // Nested coordinate objects sent by the app (Firestore GeoPoint style).
        $data['source_latitude']  = $data['source_latitude']  ?? $request->input('sourceLocationLAtLng.latitude');
        $data['source_longitude'] = $data['source_longitude'] ?? $request->input('sourceLocationLAtLng.longitude');
        $data['destination_latitude']  = $data['destination_latitude']  ?? $request->input('destinationLocationLAtLng.latitude');
        $data['destination_longitude'] = $data['destination_longitude'] ?? $request->input('destinationLocationLAtLng.longitude');
        $data['position_geohash']   = $data['position_geohash']   ?? $request->input('position.geohash');
        $data['position_latitude']  = $data['position_latitude']  ?? $request->input('position.geopoint.latitude');
        $data['position_longitude'] = $data['position_longitude'] ?? $request->input('position.geopoint.longitude');

        // Drop any nulls so DB defaults / nullable columns stay clean.
        return array_filter($data, fn ($value) => $value !== null);
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
            'status',
            'driver_id',
            'payment_status',
            'payment_type',
            'final_rate',
            'otp',
            'accepted_driver_id',
            'rejected_driver_id',
            'ride_hold_time',
            'holding_charges',
            'total_holding_charges',
            'position_latitude',
            'position_longitude',
            'position_geohash',
            'total_ride_time',
            'ac_non_ac_charges',
            'coupon',
        ]));

        // One fare everywhere — keep offer_rate and final_rate identical when
        // either is changed, so the customer and driver always see one price.
        if ($request->hasAny(['final_rate', 'offer_rate'])) {
            $fare = Order::singleFare($request->input('offer_rate'), $request->input('final_rate'));
            if ($fare !== null) {
                $order->offer_rate = $fare;
                $order->final_rate = $fare;
            }
        }

        $order->update_date = now();
        $order->save();

        event(new OrderUpdated($order));

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
