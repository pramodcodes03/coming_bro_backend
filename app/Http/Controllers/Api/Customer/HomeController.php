<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\FreightVehicle;
use App\Models\IntercityService;
use App\Models\Language;
use App\Models\OnBoarding;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Tax;
use App\Models\Zone;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function settings(): JsonResponse
    {
        $settings = Setting::whereIn('key', ['globalKey', 'globalValue', 'global', 'contact_us', 'notification_setting'])
            ->get()
            ->mapWithKeys(fn($s) => [$s->key => $s->value]);

        return response()->json(['success' => true, 'message' => 'Settings retrieved.', 'data' => $settings]);
    }

    public function paymentSettings(): JsonResponse
    {
        $setting = Setting::where('key', 'payment')->first();

        return response()->json(['success' => true, 'message' => 'Payment settings retrieved.', 'data' => $setting?->value]);
    }

    public function currency(): JsonResponse
    {
        $currency = Currency::first();

        return response()->json(['success' => true, 'message' => 'Currency retrieved.', 'data' => $currency]);
    }

    public function services(): JsonResponse
    {
        $services = Service::where('is_active', true)->get();

        return response()->json(['success' => true, 'message' => 'Services retrieved.', 'data' => $services]);
    }

    public function banners(): JsonResponse
    {
        $banners = Banner::all();

        return response()->json(['success' => true, 'message' => 'Banners retrieved.', 'data' => $banners]);
    }

    public function taxes(): JsonResponse
    {
        $taxes = Tax::where('is_active', true)->get();

        return response()->json(['success' => true, 'message' => 'Taxes retrieved.', 'data' => $taxes]);
    }

    public function airports(): JsonResponse
    {
        $airports = Airport::all();

        return response()->json(['success' => true, 'message' => 'Airports retrieved.', 'data' => $airports]);
    }

    public function zones(): JsonResponse
    {
        $zones = Zone::all();

        return response()->json(['success' => true, 'message' => 'Zones retrieved.', 'data' => $zones]);
    }

    public function freightVehicles(): JsonResponse
    {
        $vehicles = FreightVehicle::where('is_active', true)->get();

        return response()->json(['success' => true, 'message' => 'Freight vehicles retrieved.', 'data' => $vehicles]);
    }

    public function intercityServices(): JsonResponse
    {
        $services = IntercityService::where('is_active', true)->get();

        return response()->json(['success' => true, 'message' => 'Intercity services retrieved.', 'data' => $services]);
    }

    public function onboarding(): JsonResponse
    {
        $items = OnBoarding::orderBy('id')->get();

        return response()->json(['success' => true, 'message' => 'Onboarding retrieved.', 'data' => $items]);
    }

    public function languages(): JsonResponse
    {
        $languages = Language::all();

        return response()->json(['success' => true, 'message' => 'Languages retrieved.', 'data' => $languages]);
    }

    public function faqs(): JsonResponse
    {
        $faqs = Faq::all();

        return response()->json(['success' => true, 'message' => 'FAQs retrieved.', 'data' => $faqs]);
    }

    public function coupon(string $code): JsonResponse
    {
        $coupon = Coupon::where('coupon_code', $code)->where('is_active', true)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Coupon not found or inactive.', 'data' => null], 404);
        }

        return response()->json(['success' => true, 'message' => 'Coupon retrieved.', 'data' => $coupon]);
    }
}
