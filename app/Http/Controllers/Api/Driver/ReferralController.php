<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\DriverReferral;
use App\Models\ReferralLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    /**
     * Get driver referral data.
     */
    public function show(Request $request): JsonResponse
    {
        $driver = $request->user();
        $referral = DriverReferral::where('driver_id', $driver->id)->first();

        if (!$referral) {
            return response()->json([
                'success' => false,
                'message' => 'Referral data not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Referral data retrieved successfully.',
            'data' => $referral,
        ]);
    }

    /**
     * Update driver referral data.
     */
    public function update(Request $request): JsonResponse
    {
        $driver = $request->user();

        $referral = DriverReferral::where('driver_id', $driver->id)->first();

        if (!$referral) {
            return response()->json([
                'success' => false,
                'message' => 'Referral data not found.',
                'data' => null,
            ], 404);
        }

        $fillableFields = (new DriverReferral())->getFillable();
        $updateData = $request->only($fillableFields);
        unset($updateData['id'], $updateData['driver_id']);

        $referral->fill($updateData);
        $referral->save();

        return response()->json([
            'success' => true,
            'message' => 'Referral data updated successfully.',
            'data' => $referral->fresh(),
        ]);
    }

    /**
     * Create driver referral record.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'referral_code' => 'required|string',
        ]);

        $driver = $request->user();

        // Check if referral already exists
        $existing = DriverReferral::where('driver_id', $driver->id)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Referral record already exists for this driver.',
                'data' => $existing,
            ], 409);
        }

        $referral = DriverReferral::create([
            'driver_id' => $driver->id,
            'referral_code' => $request->referral_code,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Referral record created successfully.',
            'data' => $referral,
        ]);
    }

    /**
     * Get referral logs.
     */
    public function logs(Request $request): JsonResponse
    {
        $driver = $request->user();

        $logs = ReferralLog::where('driver_id', $driver->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Referral logs retrieved successfully.',
            'data' => $logs,
        ]);
    }

    /**
     * Update referral amount (city order).
     */
    public function updateAmount(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $referral = DriverReferral::where('driver_id', $request->driver_id)->first();

        if (!$referral) {
            return response()->json([
                'success' => false,
                'message' => 'Referral not found for this driver.',
                'data' => null,
            ], 404);
        }

        // Log the referral amount update
        ReferralLog::create([
            'driver_id' => $request->driver_id,
            'user_id' => $request->user()->id,
            'scanned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Referral amount updated successfully.',
            'data' => $referral->fresh(),
        ]);
    }

    /**
     * Update referral amount (intercity order).
     */
    public function updateIntercityAmount(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $referral = DriverReferral::where('driver_id', $request->driver_id)->first();

        if (!$referral) {
            return response()->json([
                'success' => false,
                'message' => 'Referral not found for this driver.',
                'data' => null,
            ], 404);
        }

        // Log the referral amount update
        ReferralLog::create([
            'driver_id' => $request->driver_id,
            'user_id' => $request->user()->id,
            'scanned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Intercity referral amount updated successfully.',
            'data' => $referral->fresh(),
        ]);
    }
}
