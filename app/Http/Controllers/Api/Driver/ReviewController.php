<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    /**
     * Create review.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|string',
            'driver_id' => 'required|string',
            'customer_id' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Check if review already exists for this order
        $existing = Review::where('order_id', $request->order_id)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Review already exists for this order.',
                'data' => $existing,
            ], 409);
        }

        $review = Review::create([
            'id' => Str::uuid()->toString(),
            'order_id' => $request->order_id,
            'driver_id' => $request->driver_id,
            'customer_id' => $request->customer_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
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
    public function show(string $orderId): JsonResponse
    {
        $review = Review::where('order_id', $orderId)->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found for this order.',
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
