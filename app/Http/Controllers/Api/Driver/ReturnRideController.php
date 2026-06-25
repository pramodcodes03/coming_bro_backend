<?php

namespace App\Http\Controllers\Api\Driver;

use App\Events\NewReturnRideOffer;
use App\Events\ReturnRideOfferUpdated;
use App\Http\Controllers\Controller;
use App\Models\RechargePlan;
use App\Models\ReturnRide;
use App\Models\ReturnRideOffer;
use App\Models\WalletTransaction;
use App\Services\PushNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Driver side of the Return Ride feature (scheduled-ride bidding):
 *   1. Browse the scheduled rides customers have posted (gated by an active
 *      Return Ride recharge).
 *   2. Submit a fare offer (price + optional description) on a ride.
 *   3. Track submitted offers; once accepted the ride becomes a normal Order
 *      and flows through the standard active-ride screens.
 */
class ReturnRideController extends Controller
{
    public function __construct(private readonly PushNotificationService $push) {}

    /** Returns whether the driver currently holds an active Return Ride recharge. */
    public function rechargeStatus(Request $request): JsonResponse
    {
        $driver = $request->user();

        return response()->json([
            'success' => true,
            'message' => 'Return ride recharge status retrieved.',
            'data'    => [
                'active'     => $driver->hasActiveReturnRideRecharge(),
                'expires_at' => $driver->return_ride_recharge_expires_at,
            ],
        ]);
    }

    /**
     * Record a Return Ride recharge purchase and unlock the feature for the
     * driver. Wires the previously-stubbed purchase to the wallet ledger and
     * the dedicated expiry column.
     */
    public function purchaseRecharge(Request $request): JsonResponse
    {
        $driver = $request->user();

        $plan = RechargePlan::where('label', 'like', 'Return Ride%')->first();

        $months = 3; // Promotional 3-month validity (see RechargePlanSeeder).
        $expiresAt = ($driver->hasActiveReturnRideRecharge()
            ? $driver->return_ride_recharge_expires_at
            : now())->copy()->addMonths($months);

        WalletTransaction::create([
            'amount'           => $plan?->price ?? 99,
            'user_id'          => $driver->id,
            'user_type'        => 'driver',
            'recharge_plan_id' => $plan?->id,
            'base_amount'      => $plan?->price ?? 99,
            'gst_percent'      => $plan?->gst_percent ?? 0,
            'total_amount'     => $plan?->price ?? 99,
            'payment_type'     => $request->input('payment_type', 'online'),
            'order_type'       => 'return_ride_recharge',
            'note'             => 'Return Ride recharge',
            'created_date'     => now(),
        ]);

        $driver->return_ride_recharge_expires_at = $expiresAt;
        $driver->save();

        return response()->json([
            'success' => true,
            'message' => 'Return Ride recharge activated successfully.',
            'data'    => [
                'active'     => true,
                'expires_at' => $driver->return_ride_recharge_expires_at,
            ],
        ]);
    }

    /**
     * Browse open scheduled rides. Each ride is annotated with the distance from
     * the driver's current location to the pickup and the driver's own offer (if
     * any) so the app can show "Offer sent / Accepted" state.
     */
    public function available(Request $request): JsonResponse
    {
        $driver = $request->user();
        $lat = $driver->location_latitude ?? $driver->position_latitude;
        $lng = $driver->location_longitude ?? $driver->position_longitude;

        $query = ReturnRide::query()
            ->where('status', ReturnRide::STATUS_SCHEDULED)
            ->where('scheduled_at', '>=', now())
            ->with('customer');

        if ($lat !== null && $lng !== null) {
            $query->selectRaw(
                "return_rides.*, (6371 * acos(least(1, greatest(-1, cos(radians(?)) * cos(radians(pickup_latitude)) * cos(radians(pickup_longitude) - radians(?)) + sin(radians(?)) * sin(radians(pickup_latitude)))))) AS pickup_distance",
                [$lat, $lng, $lat]
            )->orderBy('pickup_distance');
        } else {
            $query->orderByDesc('created_date');
        }

        $rides = $query->get();

        // Annotate each ride with this driver's offer (if one exists).
        $myOffers = ReturnRideOffer::whereIn('return_ride_id', $rides->pluck('id'))
            ->where('driver_id', $driver->id)
            ->get()
            ->keyBy('return_ride_id');

        $rides->each(function (ReturnRide $ride) use ($myOffers) {
            $offer = $myOffers->get($ride->id);
            $ride->setAttribute('my_offer_status', $offer?->status);
            $ride->setAttribute('my_offer_id', $offer?->id);
            $ride->setAttribute('my_offered_fare', $offer?->offered_fare);
        });

        return response()->json([
            'success' => true,
            'message' => 'Scheduled return rides retrieved successfully.',
            'data'    => $rides,
        ]);
    }

    /** Show a single scheduled ride (driver view). */
    public function show(Request $request, string $id): JsonResponse
    {
        $ride = ReturnRide::with('customer')->find($id);

        if (! $ride) {
            return $this->notFound();
        }

        $offer = ReturnRideOffer::where('return_ride_id', $id)
            ->where('driver_id', $request->user()->id)
            ->first();

        $ride->setAttribute('my_offer_status', $offer?->status);
        $ride->setAttribute('my_offer_id', $offer?->id);
        $ride->setAttribute('my_offered_fare', $offer?->offered_fare);

        return response()->json([
            'success' => true,
            'message' => 'Return ride retrieved successfully.',
            'data'    => $ride,
        ]);
    }

    /**
     * Submit (or update) a fare offer on a scheduled ride. One active offer per
     * driver per ride — re-submitting updates the existing offer back to Pending.
     */
    public function submitOffer(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'offered_fare' => 'required|numeric|min:1',
            'description'  => 'nullable|string|max:500',
        ]);

        $ride = ReturnRide::find($id);

        if (! $ride) {
            return $this->notFound();
        }

        if ($ride->status !== ReturnRide::STATUS_SCHEDULED) {
            return response()->json([
                'success' => false,
                'message' => 'This return ride is no longer accepting offers.',
                'data'    => null,
            ], 409);
        }

        $driverId = $request->user()->id;

        $offer = ReturnRideOffer::firstOrNew([
            'return_ride_id' => $ride->id,
            'driver_id'      => $driverId,
        ]);

        if ($offer->exists && $offer->status === ReturnRideOffer::STATUS_ACCEPTED) {
            return response()->json([
                'success' => false,
                'message' => 'Your offer on this ride has already been accepted.',
                'data'    => $offer,
            ], 409);
        }

        $offer->offered_fare = (string) $data['offered_fare'];
        $offer->description  = $data['description'] ?? null;
        $offer->status       = ReturnRideOffer::STATUS_PENDING;
        $offer->created_date = $offer->created_date ?? now();
        $offer->update_date  = now();
        $offer->save();

        // Notify the customer in real time (alert sound via push) + WebSocket.
        $offer->setRelation('returnRide', $ride);
        event(new NewReturnRideOffer($offer));

        $this->push->sendToToken(
            $ride->customer?->fcm_token,
            'New Return Ride offer',
            'A driver sent you a fare offer for your return ride.',
            ['type' => 'return_ride_offer', 'return_ride_id' => (string) $ride->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Offer submitted successfully.',
            'data'    => $offer->fresh(),
        ], 201);
    }

    /** List the authenticated driver's submitted offers (newest first). */
    public function myOffers(Request $request): JsonResponse
    {
        $offers = ReturnRideOffer::where('driver_id', $request->user()->id)
            ->with(['returnRide.customer', 'returnRide.order'])
            ->orderByDesc('created_date')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Your return ride offers retrieved successfully.',
            'data'    => $offers,
        ]);
    }

    /** Withdraw a pending offer. */
    public function withdrawOffer(Request $request, string $offerId): JsonResponse
    {
        $offer = ReturnRideOffer::where('id', $offerId)
            ->where('driver_id', $request->user()->id)
            ->first();

        if (! $offer) {
            return response()->json([
                'success' => false,
                'message' => 'Offer not found.',
                'data'    => null,
            ], 404);
        }

        if ($offer->status !== ReturnRideOffer::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending offers can be withdrawn.',
                'data'    => $offer,
            ], 409);
        }

        $offer->status = ReturnRideOffer::STATUS_WITHDRAWN;
        $offer->update_date = now();
        $offer->save();

        event(new ReturnRideOfferUpdated($offer));

        return response()->json([
            'success' => true,
            'message' => 'Offer withdrawn successfully.',
            'data'    => $offer->fresh(),
        ]);
    }

    private function notFound(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Return ride not found.',
            'data'    => null,
        ], 404);
    }
}
