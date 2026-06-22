<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Events\NewReturnRidePublished;
use App\Events\ReturnRideBookingUpdated;
use App\Events\ReturnRideUpdated;
use App\Models\ReturnRide;
use App\Models\ReturnRideBooking;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Driver side of the Return Ride feature: publish a return journey, manage
 * the published rides and view passenger bookings.
 */
class ReturnRideController extends Controller
{
    /**
     * List the authenticated driver's published return rides (newest first).
     * Optional ?status= filter and eager-loaded bookings.
     */
    public function index(Request $request): JsonResponse
    {
        $driverId = $request->user()->id;

        $query = ReturnRide::where('driver_id', $driverId)
            ->withCount(['bookings as bookings_count' => function ($q) {
                $q->where('status', ReturnRideBooking::STATUS_CONFIRMED);
            }])
            ->with(['bookings' => function ($q) {
                $q->whereIn('status', [
                    ReturnRideBooking::STATUS_CONFIRMED,
                    ReturnRideBooking::STATUS_COMPLETED,
                ])->orderByDesc('created_date');
            }]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rides = $query->orderByDesc('created_date')->get();

        return response()->json([
            'success' => true,
            'message' => 'Return rides retrieved successfully.',
            'data' => $rides,
        ]);
    }

    /**
     * Publish a new return ride.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'service_id' => 'nullable|integer',
            'source_location_name' => 'required|string',
            'source_latitude' => 'required|numeric',
            'source_longitude' => 'required|numeric',
            'destination_location_name' => 'required|string',
            'destination_latitude' => 'required|numeric',
            'destination_longitude' => 'required|numeric',
            'route_polyline' => 'nullable|string',
            'route_coordinates' => 'nullable|array',
            'distance' => 'nullable',
            'distance_type' => 'nullable|string',
            'duration' => 'nullable|string',
            'departure_time' => 'required|date',
            'pickup_window_hours' => 'required|integer|in:1,2',
            'seats_total' => 'nullable|integer|min:1|max:10',
            'fare_per_seat' => 'nullable',
            'offer_rate' => 'nullable',
            'zone' => 'nullable|array',
            'zone_id' => 'nullable|integer',
            'comments' => 'nullable|string',
            'position_latitude' => 'nullable|numeric',
            'position_longitude' => 'nullable|numeric',
            'position_geohash' => 'nullable|string',
        ]);

        $departure = Carbon::parse($data['departure_time']);
        $windowHours = (int) $data['pickup_window_hours'];
        $seats = (int) ($data['seats_total'] ?? 4);

        $data['driver_id'] = $request->user()->id;
        $data['departure_time'] = $departure;
        $data['pickup_window_start'] = $departure->copy();
        $data['pickup_window_end'] = $departure->copy()->addHours($windowHours);
        $data['seats_total'] = $seats;
        $data['seats_available'] = $seats;
        $data['status'] = ReturnRide::STATUS_ACTIVE;
        $data['distance_type'] = $data['distance_type'] ?? 'Km';
        $data['created_date'] = now();
        $data['update_date'] = now();

        $ride = ReturnRide::create($data);

        // Notify all online passengers that a new return ride is available.
        event(new NewReturnRidePublished($ride));

        return response()->json([
            'success' => true,
            'message' => 'Return ride published successfully.',
            'data' => $ride,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $ride = ReturnRide::with(['bookings', 'driver'])->find($id);

        if (!$ride) {
            return $this->notFound();
        }

        return response()->json([
            'success' => true,
            'message' => 'Return ride retrieved successfully.',
            'data' => $ride,
        ]);
    }

    /**
     * Update a return ride (e.g. cancel, complete, edit window). Only the
     * owning driver may modify it.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $ride = ReturnRide::find($id);

        if (!$ride) {
            return $this->notFound();
        }

        if ((string) $ride->driver_id !== (string) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to modify this return ride.',
                'data' => null,
            ], 403);
        }

        $fillable = (new ReturnRide())->getFillable();
        $updateData = $request->only($fillable);
        unset($updateData['id'], $updateData['driver_id']);

        // Re-derive the pickup window when the schedule changes.
        if ($request->filled('departure_time')) {
            $departure = Carbon::parse($request->departure_time);
            $hours = (int) ($request->pickup_window_hours ?? $ride->pickup_window_hours);
            $updateData['departure_time'] = $departure;
            $updateData['pickup_window_start'] = $departure->copy();
            $updateData['pickup_window_end'] = $departure->copy()->addHours($hours);
        }

        $updateData['update_date'] = now();
        $ride->fill($updateData);
        $ride->save();

        // Cancelling a ride cancels every confirmed booking on it.
        if ($ride->status === ReturnRide::STATUS_CANCELLED) {
            $ride->bookings()
                ->where('status', ReturnRideBooking::STATUS_CONFIRMED)
                ->get()
                ->each(function (ReturnRideBooking $booking) {
                    $booking->status = ReturnRideBooking::STATUS_CANCELLED;
                    $booking->update_date = now();
                    $booking->save();
                    event(new ReturnRideBookingUpdated($booking));
                });
        }

        event(new ReturnRideUpdated($ride->fresh()));

        return response()->json([
            'success' => true,
            'message' => 'Return ride updated successfully.',
            'data' => $ride->fresh(),
        ]);
    }

    /**
     * List the passenger bookings on one of the driver's published rides.
     */
    public function bookings(Request $request, string $id): JsonResponse
    {
        $ride = ReturnRide::find($id);

        if (!$ride) {
            return $this->notFound();
        }

        if ((string) $ride->driver_id !== (string) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to view these bookings.',
                'data' => null,
            ], 403);
        }

        $bookings = ReturnRideBooking::where('return_ride_id', $id)
            ->with('customer')
            ->orderByDesc('created_date')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Return ride bookings retrieved successfully.',
            'data' => $bookings,
        ]);
    }

    /**
     * Update a single booking on the driver's ride (e.g. mark Completed).
     */
    public function updateBooking(Request $request, string $bookingId): JsonResponse
    {
        $booking = ReturnRideBooking::find($bookingId);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Return ride booking not found.',
                'data' => null,
            ], 404);
        }

        if ((string) $booking->driver_id !== (string) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to modify this booking.',
                'data' => null,
            ], 403);
        }

        $booking->fill($request->only([
            'status', 'payment_status', 'payment_type', 'final_rate', 'otp',
        ]));
        $booking->update_date = now();
        $booking->save();

        event(new ReturnRideBookingUpdated($booking));

        return response()->json([
            'success' => true,
            'message' => 'Return ride booking updated successfully.',
            'data' => $booking->fresh(),
        ]);
    }

    private function notFound(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Return ride not found.',
            'data' => null,
        ], 404);
    }
}
