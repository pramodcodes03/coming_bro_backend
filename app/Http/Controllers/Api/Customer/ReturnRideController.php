<?php

namespace App\Http\Controllers\Api\Customer;

use App\Events\NewReturnRidePublished;
use App\Events\ReturnRideOfferUpdated;
use App\Events\ReturnRideUpdated;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ReturnRide;
use App\Models\ReturnRideOffer;
use App\Services\PushNotificationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Customer side of the Return Ride feature (scheduled-ride bidding):
 *   1. Post a scheduled ride requirement (pickup, drop, passengers, date/time).
 *   2. Review the fare offers submitted by drivers.
 *   3. Accept one offer (others auto-rejected) — which hands the ride off to the
 *      standard `orders` execution flow — or reject offers individually.
 */
class ReturnRideController extends Controller
{
    public function __construct(private readonly PushNotificationService $push) {}

    /** Post a new scheduled return ride. */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'service_id'           => 'nullable|integer',
            'pickup_location_name' => 'required|string',
            'pickup_latitude'      => 'required|numeric',
            'pickup_longitude'     => 'required|numeric',
            'drop_location_name'   => 'required|string',
            'drop_latitude'        => 'required|numeric',
            'drop_longitude'       => 'required|numeric',
            'passengers'           => 'required|integer|min:1|max:10',
            'scheduled_at'         => 'required|date|after:now',
            'payment_type'         => 'nullable|string',
            'route_polyline'       => 'nullable|string',
            'route_coordinates'    => 'nullable|array',
            'distance'             => 'nullable',
            'distance_type'        => 'nullable|string',
            'duration'             => 'nullable|string',
            'zone'                 => 'nullable|array',
            'zone_id'              => 'nullable|integer',
            'comments'             => 'nullable|string',
        ]);

        $data['user_id']       = $request->user()->id;
        $data['scheduled_at']  = Carbon::parse($data['scheduled_at']);
        $data['payment_type']  = $data['payment_type'] ?? 'cash';
        $data['distance_type'] = $data['distance_type'] ?? 'Km';
        $data['status']        = ReturnRide::STATUS_SCHEDULED;
        $data['created_date']  = now();
        $data['update_date']   = now();

        $ride = ReturnRide::create($data);

        // Signal all online (recharged) drivers that a new scheduled ride is up.
        event(new NewReturnRidePublished($ride));

        return response()->json([
            'success' => true,
            'message' => 'Return ride scheduled successfully.',
            'data'    => $ride,
        ], 201);
    }

    /** List the authenticated customer's scheduled return rides (newest first). */
    public function index(Request $request): JsonResponse
    {
        $query = ReturnRide::where('user_id', $request->user()->id)
            ->withCount(['offers as offers_count' => function ($q) {
                $q->where('status', ReturnRideOffer::STATUS_PENDING);
            }])
            ->with(['assignedDriver', 'order', 'acceptedOffer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rides = $query->orderByDesc('created_date')->get();

        return response()->json([
            'success' => true,
            'message' => 'Return rides retrieved successfully.',
            'data'    => $rides,
        ]);
    }

    /** Show a single scheduled ride owned by the customer, with its offers. */
    public function show(Request $request, string $id): JsonResponse
    {
        $ride = ReturnRide::where('user_id', $request->user()->id)
            ->with(['offers.driver', 'assignedDriver', 'order', 'acceptedOffer'])
            ->find($id);

        if (! $ride) {
            return $this->notFound();
        }

        return response()->json([
            'success' => true,
            'message' => 'Return ride retrieved successfully.',
            'data'    => $ride,
        ]);
    }

    /** List the driver offers on the customer's ride (driver details included). */
    public function offers(Request $request, string $id): JsonResponse
    {
        $ride = ReturnRide::where('user_id', $request->user()->id)->find($id);

        if (! $ride) {
            return $this->notFound();
        }

        $offers = ReturnRideOffer::where('return_ride_id', $id)
            ->whereIn('status', [
                ReturnRideOffer::STATUS_PENDING,
                ReturnRideOffer::STATUS_ACCEPTED,
                ReturnRideOffer::STATUS_REJECTED,
            ])
            ->with('driver')
            ->orderByRaw("FIELD(status, ?, ?, ?)", [
                ReturnRideOffer::STATUS_ACCEPTED,
                ReturnRideOffer::STATUS_PENDING,
                ReturnRideOffer::STATUS_REJECTED,
            ])
            ->orderByDesc('created_date')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Return ride offers retrieved successfully.',
            'data'    => $offers,
        ]);
    }

    /**
     * Accept a driver's offer. The chosen offer is marked Accepted, every other
     * pending offer is rejected, and the ride is handed off to the standard
     * orders flow by spawning an Order (which both apps already know how to
     * execute: pickup → OTP → start → complete → payment → end).
     */
    public function acceptOffer(Request $request, string $id, string $offerId): JsonResponse
    {
        $ride = ReturnRide::where('user_id', $request->user()->id)->find($id);

        if (! $ride) {
            return $this->notFound();
        }

        if ($ride->status !== ReturnRide::STATUS_SCHEDULED) {
            return response()->json([
                'success' => false,
                'message' => 'This return ride is no longer open for accepting offers.',
                'data'    => null,
            ], 409);
        }

        $offer = ReturnRideOffer::where('id', $offerId)
            ->where('return_ride_id', $id)
            ->where('status', ReturnRideOffer::STATUS_PENDING)
            ->first();

        if (! $offer) {
            return response()->json([
                'success' => false,
                'message' => 'Offer not found or no longer available.',
                'data'    => null,
            ], 404);
        }

        $rejected = DB::transaction(function () use ($ride, $offer) {
            // Accept the chosen offer.
            $offer->status = ReturnRideOffer::STATUS_ACCEPTED;
            $offer->update_date = now();
            $offer->save();

            // Reject every other pending offer on this ride.
            $rejected = ReturnRideOffer::where('return_ride_id', $ride->id)
                ->where('id', '!=', $offer->id)
                ->where('status', ReturnRideOffer::STATUS_PENDING)
                ->get();

            foreach ($rejected as $other) {
                $other->status = ReturnRideOffer::STATUS_REJECTED;
                $other->update_date = now();
                $other->save();
            }

            // Hand off to the standard orders execution flow.
            $order = $this->spawnOrder($ride, $offer);

            $ride->status             = ReturnRide::STATUS_ACCEPTED;
            $ride->accepted_offer_id  = $offer->id;
            $ride->assigned_driver_id = $offer->driver_id;
            $ride->order_id           = $order->id;
            $ride->update_date        = now();
            $ride->save();

            return $rejected;
        });

        // Notify the chosen driver (real-time + push with sound) and refresh the
        // rejected drivers' lists.
        $offer->setRelation('returnRide', $ride);
        event(new ReturnRideOfferUpdated($offer));
        event(new ReturnRideUpdated($ride->fresh()));

        $this->push->sendToToken(
            $offer->driver?->fcm_token,
            'Return Ride accepted',
            'A customer accepted your offer. Head to the pickup location.',
            ['type' => 'return_ride_offer_accepted', 'return_ride_id' => (string) $ride->id, 'order_id' => (string) $ride->order_id]
        );

        foreach ($rejected as $other) {
            $other->setRelation('returnRide', $ride);
            event(new ReturnRideOfferUpdated($other));
            $this->push->sendToToken(
                $other->driver?->fcm_token,
                'Return Ride offer not selected',
                'The customer chose another driver for this return ride.',
                ['type' => 'return_ride_offer_rejected', 'return_ride_id' => (string) $ride->id]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Driver offer accepted. Your ride is confirmed.',
            'data'    => $ride->fresh(['assignedDriver', 'order', 'acceptedOffer.driver']),
        ]);
    }

    /** Reject a single driver offer. */
    public function rejectOffer(Request $request, string $id, string $offerId): JsonResponse
    {
        $ride = ReturnRide::where('user_id', $request->user()->id)->find($id);

        if (! $ride) {
            return $this->notFound();
        }

        $offer = ReturnRideOffer::where('id', $offerId)
            ->where('return_ride_id', $id)
            ->where('status', ReturnRideOffer::STATUS_PENDING)
            ->with('driver')
            ->first();

        if (! $offer) {
            return response()->json([
                'success' => false,
                'message' => 'Offer not found or no longer available.',
                'data'    => null,
            ], 404);
        }

        $offer->status = ReturnRideOffer::STATUS_REJECTED;
        $offer->update_date = now();
        $offer->save();

        $offer->setRelation('returnRide', $ride);
        event(new ReturnRideOfferUpdated($offer));

        $this->push->sendToToken(
            $offer->driver?->fcm_token,
            'Return Ride offer declined',
            'The customer declined your offer on this return ride.',
            ['type' => 'return_ride_offer_rejected', 'return_ride_id' => (string) $ride->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Offer rejected.',
            'data'    => $offer->fresh('driver'),
        ]);
    }

    /** Cancel a scheduled ride (and its spawned order, if already accepted). */
    public function cancel(Request $request, string $id): JsonResponse
    {
        $ride = ReturnRide::where('user_id', $request->user()->id)->find($id);

        if (! $ride) {
            return $this->notFound();
        }

        if (in_array($ride->status, [ReturnRide::STATUS_CANCELLED, ReturnRide::STATUS_COMPLETED], true)) {
            return response()->json([
                'success' => true,
                'message' => 'Return ride already closed.',
                'data'    => $ride,
            ]);
        }

        DB::transaction(function () use ($ride) {
            // Reject any still-pending offers.
            ReturnRideOffer::where('return_ride_id', $ride->id)
                ->where('status', ReturnRideOffer::STATUS_PENDING)
                ->update(['status' => ReturnRideOffer::STATUS_REJECTED, 'update_date' => now()]);

            // Cancel the spawned order if the ride was already accepted.
            if ($ride->order_id) {
                Order::where('id', $ride->order_id)->update([
                    'status'      => 'Ride Canceled',
                    'update_date' => now(),
                ]);
            }

            $ride->status      = ReturnRide::STATUS_CANCELLED;
            $ride->update_date = now();
            $ride->save();
        });

        event(new ReturnRideUpdated($ride->fresh()));

        return response()->json([
            'success' => true,
            'message' => 'Return ride cancelled successfully.',
            'data'    => $ride->fresh(),
        ]);
    }

    /**
     * Spawn a normal Order from an accepted return ride + winning offer so the
     * ride enters the existing execution flow already assigned to the driver.
     */
    private function spawnOrder(ReturnRide $ride, ReturnRideOffer $offer): Order
    {
        return Order::create([
            'user_id'                   => $ride->user_id,
            'service_id'                => $ride->service_id,
            'return_ride_id'            => $ride->id,
            'source_location_name'      => $ride->pickup_location_name,
            'source_latitude'           => $ride->pickup_latitude,
            'source_longitude'          => $ride->pickup_longitude,
            'destination_location_name' => $ride->drop_location_name,
            'destination_latitude'      => $ride->drop_latitude,
            'destination_longitude'     => $ride->drop_longitude,
            'distance'                  => $ride->distance,
            'distance_type'             => $ride->distance_type,
            'duration'                  => $ride->duration,
            'payment_type'              => $ride->payment_type ?? 'cash',
            'offer_rate'                => $offer->offered_fare,
            'final_rate'                => $offer->offered_fare,
            'otp'                       => (string) random_int(1000, 9999),
            'driver_id'                 => $offer->driver_id,
            'accepted_driver_id'        => [(string) $offer->driver_id],
            // Assigned, pre-start state — both apps treat "Ride Active" as an
            // active assigned ride heading to pickup.
            'status'                    => 'Ride Active',
            'payment_status'            => false,
            'zone'                      => $ride->zone,
            'zone_id'                   => $ride->zone_id,
            'created_date'              => now(),
            'update_date'               => now(),
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
