<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\DriverReferral;
use App\Models\Referral;
use App\Models\ReferralLog;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $referral = Referral::where('referral_by', $request->user()->id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Referral retrieved.',
            'data' => $referral,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['referral_code' => 'required|string']);

        $referral = Referral::updateOrCreate(
            ['referral_by' => $request->user()->id],
            ['referral_code' => $request->referral_code]
        );

        return response()->json([
            'success' => true,
            'message' => 'Referral saved.',
            'data' => $referral,
        ]);
    }

    public function checkDriverCode(string $code): JsonResponse
    {
        $driverReferral = DriverReferral::where('referral_code', $code)->first();

        return response()->json([
            'success' => true,
            'message' => 'Check completed.',
            'data' => [
                'valid' => $driverReferral !== null,
                'driver_referral' => $driverReferral,
            ],
        ]);
    }

    public function addLog(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|integer',
            'referral_code' => 'required|string',
        ]);

        $customer = $request->user();

        $alreadyScanned = ReferralLog::where('driver_id', $request->driver_id)
            ->where('user_id', $customer->id)
            ->exists();

        if ($alreadyScanned) {
            return response()->json([
                'success' => false,
                'message' => 'Referral already used.',
                'data' => null,
            ], 409);
        }

        $referralSetting = Setting::where('key', 'referral')->first();
        $referralRide = $referralSetting?->value['referralRide'] ?? 0;
        $referralCustomer = $referralSetting?->value['referralCustomer'] ?? 0;

        $driverReferral = DriverReferral::where('referral_code', $request->referral_code)
            ->where('driver_id', $request->driver_id)
            ->first();

        if ($driverReferral && $driverReferral->referral_users_count < $referralCustomer) {
            $driverReferral->increment('referral_users_count');
            $driverReferral->increment('bonus_rides_remaining', $referralRide);
        }

        $log = ReferralLog::create([
            'driver_id' => $request->driver_id,
            'user_id' => $customer->id,
            'referral_code' => $request->referral_code,
            'scanned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Referral log added.',
            'data' => $log,
        ], 201);
    }
}
