<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DriverUser;
use App\Models\Order;
use App\Models\IntercityOrder;
use App\Models\Review;
use App\Models\Service;
use App\Models\WalletTransaction;
use App\Models\WithdrawalHistory;
use App\Models\SubscriptionHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $now = Carbon::now();
        $today = $now->copy()->startOfDay();
        $yesterday = $now->copy()->subDay()->startOfDay();
        $yesterdayEnd = $now->copy()->subDay()->endOfDay();
        $thisMonthStart = $now->copy()->startOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // ── Core Counts ──
        $totalCustomers = Customer::count();
        $totalDrivers = DriverUser::count();
        $totalCityOrders = Order::count();
        $totalIntercityOrders = IntercityOrder::count();
        $totalOrders = $totalCityOrders + $totalIntercityOrders;

        // ── Order Status Counts ──
        $activeOrders = Order::whereNotIn('status', ['completed', 'cancelled'])->count()
            + IntercityOrder::whereNotIn('status', ['completed', 'cancelled'])->count();

        $completedOrders = Order::where('status', 'completed')->count()
            + IntercityOrder::where('status', 'completed')->count();

        $cancelledOrders = Order::where('status', 'cancelled')->count()
            + IntercityOrder::where('status', 'cancelled')->count();

        $placedOrders = Order::where('status', 'placed')->count()
            + IntercityOrder::where('status', 'placed')->count();

        // ── Today's Stats ──
        $todayOrders = Order::where('created_date', '>=', $today)->count()
            + IntercityOrder::where('created_date', '>=', $today)->count();

        $yesterdayOrders = Order::whereBetween('created_date', [$yesterday, $yesterdayEnd])->count()
            + IntercityOrder::whereBetween('created_date', [$yesterday, $yesterdayEnd])->count();

        $todayCompleted = Order::where('status', 'completed')->where('created_date', '>=', $today)->count()
            + IntercityOrder::where('status', 'completed')->where('created_date', '>=', $today)->count();

        $todayRevenue = (float) Order::where('status', 'completed')
                ->where('created_date', '>=', $today)
                ->sum('final_rate')
            + (float) IntercityOrder::where('status', 'completed')
                ->where('created_date', '>=', $today)
                ->sum('final_rate');

        $yesterdayRevenue = (float) Order::where('status', 'completed')
                ->whereBetween('created_date', [$yesterday, $yesterdayEnd])
                ->sum('final_rate')
            + (float) IntercityOrder::where('status', 'completed')
                ->whereBetween('created_date', [$yesterday, $yesterdayEnd])
                ->sum('final_rate');

        // ── This Month Stats ──
        $thisMonthOrders = Order::where('created_date', '>=', $thisMonthStart)->count()
            + IntercityOrder::where('created_date', '>=', $thisMonthStart)->count();

        $lastMonthOrders = Order::whereBetween('created_date', [$lastMonthStart, $lastMonthEnd])->count()
            + IntercityOrder::whereBetween('created_date', [$lastMonthStart, $lastMonthEnd])->count();

        $thisMonthRevenue = (float) Order::where('status', 'completed')
                ->where('created_date', '>=', $thisMonthStart)
                ->sum('final_rate')
            + (float) IntercityOrder::where('status', 'completed')
                ->where('created_date', '>=', $thisMonthStart)
                ->sum('final_rate');

        $lastMonthRevenue = (float) Order::where('status', 'completed')
                ->whereBetween('created_date', [$lastMonthStart, $lastMonthEnd])
                ->sum('final_rate')
            + (float) IntercityOrder::where('status', 'completed')
                ->whereBetween('created_date', [$lastMonthStart, $lastMonthEnd])
                ->sum('final_rate');

        // ── Total Revenue ──
        $totalRevenue = (float) Order::where('status', 'completed')->sum('final_rate')
            + (float) IntercityOrder::where('status', 'completed')->sum('final_rate');

        // ── Driver Stats ──
        $onlineDrivers = DriverUser::where('is_online', true)->count();
        $verifiedDrivers = DriverUser::where('document_verification', true)->count();
        $subscribedDrivers = DriverUser::where('is_subscription_enable', true)->count();

        $newDriversThisMonth = DriverUser::where('created_at', '>=', $thisMonthStart)->count();
        $newCustomersThisMonth = Customer::where('created_at', '>=', $thisMonthStart)->count();

        // ── Financial ──
        $pendingPayouts = WithdrawalHistory::where('payment_status', 'pending')->count();
        $pendingPayoutAmount = (float) WithdrawalHistory::where('payment_status', 'pending')->sum('amount');

        $totalWalletBalance = (float) DriverUser::sum('wallet_amount')
            + (float) Customer::sum('wallet_amount');

        // ── Reviews ──
        $totalReviews = Review::count();
        $averageRating = (float) Review::avg('rating');

        // ── Monthly Order Trend (Last 6 months) ──
        $monthlyTrend = collect();
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = $now->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $now->copy()->subMonths($i)->endOfMonth();
            $label = $monthStart->format('M Y');

            $cityCount = Order::whereBetween('created_date', [$monthStart, $monthEnd])->count();
            $intercityCount = IntercityOrder::whereBetween('created_date', [$monthStart, $monthEnd])->count();
            $monthRevenue = (float) Order::where('status', 'completed')
                    ->whereBetween('created_date', [$monthStart, $monthEnd])
                    ->sum('final_rate')
                + (float) IntercityOrder::where('status', 'completed')
                    ->whereBetween('created_date', [$monthStart, $monthEnd])
                    ->sum('final_rate');

            $monthlyTrend->push([
                'label' => $label,
                'city' => $cityCount,
                'intercity' => $intercityCount,
                'total' => $cityCount + $intercityCount,
                'revenue' => round($monthRevenue, 2),
            ]);
        }

        // ── Daily Order Trend (Last 7 days) ──
        $dailyTrend = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dayStart = $now->copy()->subDays($i)->startOfDay();
            $dayEnd = $now->copy()->subDays($i)->endOfDay();

            $cityCount = Order::whereBetween('created_date', [$dayStart, $dayEnd])->count();
            $intercityCount = IntercityOrder::whereBetween('created_date', [$dayStart, $dayEnd])->count();

            $dailyTrend->push([
                'label' => $dayStart->format('D'),
                'date' => $dayStart->format('d M'),
                'city' => $cityCount,
                'intercity' => $intercityCount,
                'total' => $cityCount + $intercityCount,
            ]);
        }

        // ── Order Status Distribution ──
        $orderStatusDist = [
            'completed' => $completedOrders,
            'active' => $activeOrders,
            'cancelled' => $cancelledOrders,
            'placed' => $placedOrders,
        ];

        // ── Top 5 Drivers (by completed orders) ──
        $topDrivers = DriverUser::select('driver_users.*')
            ->selectRaw('(SELECT COUNT(*) FROM orders WHERE orders.driver_id = driver_users.id AND orders.status = "completed") +
                         (SELECT COUNT(*) FROM orders_intercity WHERE orders_intercity.driver_id = driver_users.id AND orders_intercity.status = "completed") as total_completed')
            ->selectRaw('(SELECT COALESCE(SUM(final_rate), 0) FROM orders WHERE orders.driver_id = driver_users.id AND orders.status = "completed") +
                         (SELECT COALESCE(SUM(final_rate), 0) FROM orders_intercity WHERE orders_intercity.driver_id = driver_users.id AND orders_intercity.status = "completed") as total_earnings')
            ->orderByDesc('total_completed')
            ->take(5)
            ->get();

        // ── Recent Orders ──
        $recentOrders = Order::with('customer', 'driver')
            ->orderByDesc('created_date')
            ->take(8)
            ->get();

        // ── Recent Intercity Orders ──
        $recentIntercityOrders = IntercityOrder::orderByDesc('created_date')
            ->take(5)
            ->get();

        // ── Growth Percentages ──
        $orderGrowth = $yesterdayOrders > 0
            ? round((($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100, 1)
            : ($todayOrders > 0 ? 100 : 0);

        $revenueGrowth = $yesterdayRevenue > 0
            ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1)
            : ($todayRevenue > 0 ? 100 : 0);

        $monthlyOrderGrowth = $lastMonthOrders > 0
            ? round((($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1)
            : ($thisMonthOrders > 0 ? 100 : 0);

        $monthlyRevenueGrowth = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($thisMonthRevenue > 0 ? 100 : 0);

        // ── Completion Rate ──
        $completionRate = $totalOrders > 0
            ? round(($completedOrders / $totalOrders) * 100, 1)
            : 0;

        // ── Cancellation Rate ──
        $cancellationRate = $totalOrders > 0
            ? round(($cancelledOrders / $totalOrders) * 100, 1)
            : 0;

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalDrivers',
            'totalOrders',
            'totalCityOrders',
            'totalIntercityOrders',
            'activeOrders',
            'completedOrders',
            'cancelledOrders',
            'placedOrders',
            'todayOrders',
            'yesterdayOrders',
            'todayCompleted',
            'todayRevenue',
            'yesterdayRevenue',
            'thisMonthOrders',
            'lastMonthOrders',
            'thisMonthRevenue',
            'lastMonthRevenue',
            'totalRevenue',
            'onlineDrivers',
            'verifiedDrivers',
            'subscribedDrivers',
            'newDriversThisMonth',
            'newCustomersThisMonth',
            'pendingPayouts',
            'pendingPayoutAmount',
            'totalWalletBalance',
            'totalReviews',
            'averageRating',
            'monthlyTrend',
            'dailyTrend',
            'orderStatusDist',
            'topDrivers',
            'recentOrders',
            'recentIntercityOrders',
            'orderGrowth',
            'revenueGrowth',
            'monthlyOrderGrowth',
            'monthlyRevenueGrowth',
            'completionRate',
            'cancellationRate'
        ));
    }
}
