<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\DriverUser;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|integer',
            'rating' => 'required',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create([
            'customer_id' => $request->user()->id,
            'driver_id' => $request->driver_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'type' => 'customer',
            'date' => now(),
        ]);

        $driver = DriverUser::find($request->driver_id);
        if ($driver) {
            $driver->reviews_count = (float) $driver->reviews_count + 1;
            $driver->reviews_sum = (float) $driver->reviews_sum + (float) $request->rating;
            $driver->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Review submitted.',
            'data' => $review,
        ], 201);
    }

    public function driverReviews(string $driverId): JsonResponse
    {
        $reviews = Review::where('driver_id', $driverId)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Reviews retrieved.',
            'data' => $reviews,
        ]);
    }
}
