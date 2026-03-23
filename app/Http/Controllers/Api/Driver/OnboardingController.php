<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\OnboardingScreen;
use Illuminate\Http\JsonResponse;

class OnboardingController extends Controller
{
    /**
     * Get onboarding screens (type=driverApp).
     */
    public function index(): JsonResponse
    {
        $screens = OnboardingScreen::where('type', 'driverApp')
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Onboarding screens retrieved successfully.',
            'data' => $screens,
        ]);
    }

    /**
     * Get enabled languages.
     */
    public function languages(): JsonResponse
    {
        $languages = Language::where('enable', true)->get();

        return response()->json([
            'success' => true,
            'message' => 'Languages retrieved successfully.',
            'data' => $languages,
        ]);
    }
}
