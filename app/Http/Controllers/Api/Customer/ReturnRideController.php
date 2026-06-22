<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Events\ReturnRideBookingUpdated;
use App\Events\ReturnRideUpdated;
use App\Models\ReturnRide;
use App\Models\ReturnRideBooking;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Passenger side of the Return Ride feature: discover available return rides
 * along a corridor within a pickup time window, then book a seat.
 */
class ReturnRideController extends Controller
{
    /**
     * Discover available return rides.
     *
     * Implements the quotation's "time-window-based availability filtering" and
     * "route-based ride discovery":
     *   1. Only Active, non-expired rides with free seats.
     *   2. Source within `radius` km of the passenger's pickup (corridor entry).
     *   3. Optional drop within `corridor_radius` km of the ride destination
     *      (passenger travels towards the driver's destination).
     *   4. Optional desired pickup time must fall inside the ride's pickup window.
     */
    public function available(Request $request): JsonResponse
    {
        $request->validate([
            'pickup_latitude' => 'required|numeric',
            'pickup_longitude' => 'required|numeric',
            'drop_latitude' => 'nullable|numeric',
            'drop_longitude' => 'nullable|numeric',
            'when' => 'nullable|date',
            'radius' => 'nullable|numeric',
            'corridor_radius' => 'nullable|numeric',
        ]);

        $pLat = $request->pickup_latitude;
        $pLng = $request->pickup_longitude;
        $radius = $request->radius ?? 15;            // km around pickup
        $corridorRadius = $request->corridor_radius ?? 35; // km around destination

        $query = ReturnRide::selectRaw(
            "*, (6371 * acos(cos(radians(?)) * cos(radians(source_latitude)) * cos(radians(source_longitude) - radians(?)) + sin(radians(?)) * sin(radians(source_latitude)))) AS pickup_distance",
            [$pLat, $pLng, $pLat]
        )
            ->where('status', ReturnRide::STATUS_ACTIVE)
            ->where('seats_available', '>', 0)
            ->where('pickup_window_end', '>=', now())
            ->having('pickup_distance', '<=', $radius);

        // Direction filter — only rides heading towards the passenger's drop.
        if ($request->filled('drop_latitude') && $request->filled('drop_longitude')) {
            $dLat = $request->drop_latitude;
            $dLng = $request->drop_longitude;
            $query->selectRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(destination_latitude)) * cos(radians(destination_longitude) - radians(?)) + sin(radians(?)) * sin(radians(destination_latitude)))) AS drop_distance",
                [$dLat, $dLng, $dLat]
            )->having('drop_distance', '<=', $corridorRadius);
        }

        // Time-window filter.
        if ($request->filled('when')) {
            $when = Carbon::parse($request->when);
            $query->where('pickup_window_start', '<=', $when)
                ->where('pickup_window_end', '>=', $when);
        }

        $rides = $query->with('driver')
            ->orderBy('pickup_distance')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Available return rides retrieved successfully.',
            'data' => $rides,
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $ride = ReturnRide::with('driver')->find($id);

        if (!$ride) {
            return response()->json([
                'success' => false,
                'message' => 'Return ride not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Return ride retrieved successfully.',
            'data' => $ride,
        ]);
    }

    /**
     * Book a seat on a published return ride.
     */
    public function book(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'pickup_location_name' => 'nullable|string',
            'pickup_latitude' => 'required|numeric',
            'pickup_longitude' => 'required|numeric',
            'drop_location_name' => 'nullable|string',
            'drop_latitude' => 'nullable|numeric',
            'drop_longitude' => 'nullable|numeric',
            'number_of_passenger' => 'nullable|string',
            'fare' => 'nullable',
            'payment_type' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        $ride = ReturnRide::find($id);

        if (!$ride) {
            return response()->json([
                'success' => false,
                'message' => 'Return ride not found.',
                'data' => null,
            ], 404);
        }

        if ($ride->status !== ReturnRide::STATUS_ACTIVE) {
            return response()->json([
                'success' => false,
                'message' => 'This return ride is no longer available for booking.',
                'data' => null,
            ], 409);
        }

        $userId = $request->user()->id;

        // Prevent a passenger from double-booking the same ride.
        $existing = ReturnRideBooking::where('return_ride_id', $id)
            ->where('user_id', $userId)
            ->where('status', ReturnRideBooking::STATUS_CONFIRMED)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already booked this return ride.',
                'data' => $existing,
            ], 409);
        }

        $seatsRequested = max(1, (int) ($data['number_of_passenger'] ?? 1));
        if ($ride->seats_available < $seatsRequested) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough seats available on this return ride.',
                'data' => null,
            ], 409);
        }

        $data['return_ride_id'] = $ride->id;
        $data['user_id'] = $userId;
        $data['driver_id'] = $ride->driver_id;
        $data['number_of_passenger'] = (string) $seatsRequested;
        $data['fare'] = $data['fare'] ?? $ride->fare_per_seat;
        $data['status'] = ReturnRideBooking::STATUS_CONFIRMED;
        $data['otp'] = (string) random_int(1000, 9999);
        $data['payment_status'] = false;
        $data['created_date'] = now();
        $data['update_date'] = now();

        $booking = ReturnRideBooking::create($data);

        // Decrement remaining seats on the parent ride.
        $ride->seats_available = max(0, $ride->seats_available - $seatsRequested);
        $ride->update_date = now();
        $ride->save();

        // Booking confirmation triggers notifications for BOTH parties.
        event(new ReturnRideBookingUpdated($booking));
        event(new ReturnRideUpdated($ride->fresh()));

        return response()->json([
            'success' => true,
            'message' => 'Return ride booked successfully.',
            'data' => $booking->load('returnRide.driver'),
        ], 201);
    }

    /**
     * List the authenticated passenger's return ride bookings.
     */
    public function myBookings(Request $request): JsonResponse
    {
        $bookings = ReturnRideBooking::where('user_id', $request->user()->id)
            ->with(['returnRide.driver', 'driver'])
            ->orderByDesc('created_date')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Return ride bookings retrieved successfully.',
            'data' => $bookings,
        ]);
    }

    /**
     * Cancel one of the passenger's bookings and free up the seat.
     */
    public function cancelBooking(Request $request, string $bookingId): JsonResponse
    {
        $booking = ReturnRideBooking::where('id', $bookingId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Return ride booking not found.',
                'data' => null,
            ], 404);
        }

        if ($booking->status === ReturnRideBooking::STATUS_CANCELLED) {
            return response()->json([
                'success' => true,
                'message' => 'Booking already cancelled.',
                'data' => $booking,
            ]);
        }

        $wasConfirmed = $booking->status === ReturnRideBooking::STATUS_CONFIRMED;

        $booking->status = ReturnRideBooking::STATUS_CANCELLED;
        $booking->update_date = now();
        $booking->save();

        // Restore the seat on the parent ride.
        if ($wasConfirmed && $booking->returnRide) {
            $ride = $booking->returnRide;
            $seats = max(1, (int) $booking->number_of_passenger);
            $ride->seats_available = min($ride->seats_total, $ride->seats_available + $seats);
            $ride->update_date = now();
            $ride->save();
            event(new ReturnRideUpdated($ride->fresh()));
        }

        event(new ReturnRideBookingUpdated($booking));

        return response()->json([
            'success' => true,
            'message' => 'Return ride booking cancelled successfully.',
            'data' => $booking->fresh(),
        ]);
    }
}
