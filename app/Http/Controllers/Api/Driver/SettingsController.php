<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    /**
     * Get all settings combined (globalKey, notification_setting, globalValue, referral, global, contact_us).
     */
    public function index(): JsonResponse
    {
        $settingKeys = [
            'globalKey',
            'notification_setting',
            'globalValue',
            'referral',
            'global',
            'contact_us',
        ];

        $settings = Setting::whereIn('key', $settingKeys)->get();

        $data = [];
        foreach ($settings as $setting) {
            $data[$setting->key] = $setting->value;
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings retrieved successfully.',
            'data' => $data,
        ]);
    }

    /**
     * Get payment settings.
     */
    public function paymentSettings(): JsonResponse
    {
        $setting = Setting::where('key', 'payment')->first();

        if (!$setting) {
            return response()->json([
                'success' => true,
                'message' => 'No payment settings found.',
                'data' => null,
            ]);
        }

        // The value is stored as a JSON string — decode it so Flutter
        // receives a proper object with razorpay, strip, cash, etc.
        $data = json_decode($setting->value, true) ?? [];

        return response()->json([
            'success' => true,
            'message' => 'Payment settings retrieved successfully.',
            'data' => $data,
        ]);
    }

    /**
     * Get active currency.
     */
    public function currency(): JsonResponse
    {
        $currency = Currency::where('enable', true)->first();

        return response()->json([
            'success' => true,
            'message' => 'Currency retrieved successfully.',
            'data' => $currency,
        ]);
    }
}
