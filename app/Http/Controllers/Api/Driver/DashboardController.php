<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Order;
use App\Models\IntercityOrder;
use App\Models\Setting;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Home dashboard — aggregated data for the driver home screen.
     */
    public function index(Request $request): JsonResponse
    {
        $driver = $request->user();
        $today = Carbon::today();

        // ── Today's earnings (wallet topups received today) ──────────────
        $todayEarnings = WalletTransaction::where('user_id', $driver->id)
            ->whereDate('created_date', $today)
            ->sum('amount');

        // ── Ride counts ──────────────────────────────────────────────────
        $totalRides = Order::where('driver_id', $driver->id)
            ->where('status', 'Ride Completed')
            ->count();

        $totalIntercityRides = IntercityOrder::where('driver_id', $driver->id)
            ->where('status', 'Ride Completed')
            ->count();

        $todayRides = Order::where('driver_id', $driver->id)
            ->where('status', 'Ride Completed')
            ->whereDate('created_date', $today)
            ->count();

        $todayIntercityRides = IntercityOrder::where('driver_id', $driver->id)
            ->where('status', 'Ride Completed')
            ->whereDate('created_date', $today)
            ->count();

        // ── Remaining rides from subscription ────────────────────────────
        $remainingRides = (int) ($driver->remaining_rides ?? 0);
        $complimentaryRides = (int) ($driver->complimentary_rides ?? 0);

        // ── Rating ───────────────────────────────────────────────────────
        $reviewsCount = (int) ($driver->reviews_count ?? 0);
        $reviewsSum = (float) ($driver->reviews_sum ?? 0);
        $avgRating = $reviewsCount > 0
            ? round($reviewsSum / $reviewsCount, 1)
            : 0.0;

        // ── Support contact ──────────────────────────────────────────────
        $contactSetting = Setting::where('key', 'contact_us')->first();
        $supportContact = $contactSetting ? $contactSetting->value : null;

        // ── Driver tips ──────────────────────────────────────────────────
        $tipsSetting = Setting::where('key', 'driver_tips')->first();
        $driverTips = $tipsSetting ? $tipsSetting->value : $this->defaultDriverTips();

        // ── Ads / Banners ────────────────────────────────────────────────
        $banners = Banner::where('enable', true)
            ->orderBy('id')
            ->get(['id', 'title', 'description', 'image', 'redirect_url']);

        return response()->json([
            'success' => true,
            'message' => 'Dashboard data retrieved successfully.',
            'data' => [
                // Earnings
                'today_earnings' => round((float) $todayEarnings, 2),
                'wallet_amount' => round((float) ($driver->wallet_amount ?? 0), 2),

                // Ride stats
                'today_rides' => $todayRides + $todayIntercityRides,
                'total_rides' => $totalRides + $totalIntercityRides,
                'remaining_rides' => $remainingRides + $complimentaryRides,

                // Rating
                'avg_rating' => $avgRating,
                'reviews_count' => $reviewsCount,

                // Subscription
                'is_subscription_enable' => (bool) $driver->is_subscription_enable,

                // Online time (placeholder — needs real tracking implementation)
                'total_online_time' => '—',

                // Today's summary
                'today_summary' => [
                    'earnings' => round((float) $todayEarnings, 2),
                    'completed_rides' => $todayRides + $todayIntercityRides,
                    'rating' => $avgRating,
                ],

                // Driver tips
                'driver_tips' => $driverTips,

                // Ads / Banners for carousel
                'banners' => $banners,

                // Support contact
                'support_contact' => $supportContact,
            ],
        ]);
    }

    /**
     * Default driver tips when no custom tips are configured.
     */
    private function defaultDriverTips(): array
    {
        return [
            [
                'icon' => 'access_time_filled_rounded',
                'color' => '#018DBD',
                'title' => 'Drive During Peak Hours',
                'description' => 'Earn more by being online during morning (7–10 AM) and evening (5–9 PM) rush hours.',
            ],
            [
                'icon' => 'battery_charging_full_rounded',
                'color' => '#2ECC71',
                'title' => 'Keep Your Vehicle Ready',
                'description' => 'Check fuel, tyre pressure and charging before going online to avoid mid-trip issues.',
            ],
            [
                'icon' => 'star_rounded',
                'color' => '#FFC107',
                'title' => 'Maintain a High Rating',
                'description' => 'Greet passengers, keep the cab clean and drive smoothly to consistently earn 5-star ratings.',
            ],
            [
                'icon' => 'shield_rounded',
                'color' => '#3B82F6',
                'title' => 'Follow Traffic Rules',
                'description' => 'Safe driving protects you, your passengers and your licence — always wear your seatbelt.',
            ],
        ];
    }
}
