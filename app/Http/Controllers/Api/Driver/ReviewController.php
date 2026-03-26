<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * List reviews for the current driver.
     */
    public function index(Request $request): JsonResponse
    {
        $driver = $request->user();
        $reviews = Review::where('driver_id', $driver->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Reviews retrieved successfully.',
            'data' => $reviews,
        ]);
    }

    /**
     * Create review.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string',
            'type' => 'nullable|string',
        ]);

        $review = Review::create([
            'driver_id' => $request->driver_id,
            'customer_id' => $request->customer_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'type' => $request->type ?? 'city',
            'date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review created successfully.',
            'data' => $review,
        ]);
    }

    /**
     * Get review by order ID.
     */
    public function show(string $id): JsonResponse
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Review retrieved successfully.',
            'data' => $review,
        ]);
    }
}
