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
     * Create subscription history record.
     */
    public function createHistory(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|string',
            'subscription_id' => 'required|string',
            'subscription_data' => 'nullable|array',
            'amount' => 'required|numeric',
            'payment_type' => 'required|string',
            'transaction_id' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $history = SubscriptionHistory::create([
            'driver_id' => $request->driver_id,
            'subscription_id' => $request->subscription_id,
            'subscription_data' => $request->subscription_data,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'transaction_id' => $request->transaction_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription history created successfully.',
            'data' => $history,
        ]);
    }
}
