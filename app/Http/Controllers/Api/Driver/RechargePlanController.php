<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\RechargePlan;
use Illuminate\Http\JsonResponse;

class RechargePlanController extends Controller
{
    /**
     * Get all active recharge plans.
     */
    public function index(): JsonResponse
    {
        $plans = RechargePlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Recharge plans retrieved successfully.',
            'data' => $plans,
        ]);
    }

    /**
     * Get a single recharge plan.
     */
    public function show(int $id): JsonResponse
    {
        $plan = RechargePlan::find($id);

        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Recharge plan not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Recharge plan retrieved successfully.',
            'data' => $plan,
        ]);
    }
}
