<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionHistory;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Get all enabled subscription plans.
     */
    public function index(): JsonResponse
    {
        $plans = SubscriptionPlan::where('enable', true)->get();

        return response()->json([
            'success' => true,
            'message' => 'Subscription plans retrieved successfully.',
            'data' => $plans,
        ]);
    }

    /**
     * Get subscription history for the current driver.
     */
    public function history(Request $request): JsonResponse
    {
        $driver = $request->user();
        $history = SubscriptionHistory::whereJsonContains('user->id', $driver->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Subscription history retrieved successfully.',
            'data' => $history,
        ]);
    }

    /**
     * Create subscription history record.
     */
    public function createHistory(Request $request): JsonResponse
    {
        $request->validate([
            'subscription_plan' => 'nullable|array',
            'subscription_amount' => 'required|string',
            'gst_amount' => 'nullable|string',
            'subscription_role' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'remaining_days' => 'nullable|string',
        ]);

        $driver = $request->user();

        $history = SubscriptionHistory::create([
            'subscription_plan' => $request->subscription_plan,
            'subscription_amount' => $request->subscription_amount,
            'gst_amount' => $request->gst_amount,
            'subscription_role' => $request->subscription_role,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'date' => now(),
            'remaining_days' => $request->remaining_days,
            'user' => $driver->only(['id', 'full_name', 'email', 'phone_number']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription history created successfully.',
            'data' => $history,
        ]);
    }
}
