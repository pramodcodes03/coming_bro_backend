<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DriverUser;
use App\Models\Order;
use App\Models\IntercityOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $totalCustomers = Customer::count();
        $totalDrivers = DriverUser::count();

        $totalOrders = Order::count() + IntercityOrder::count();

        $activeOrders = Order::whereNotIn('status', ['completed', 'cancelled'])->count()
            + IntercityOrder::whereNotIn('status', ['completed', 'cancelled'])->count();

        $completedOrders = Order::where('status', 'completed')->count()
            + IntercityOrder::where('status', 'completed')->count();

        $cancelledOrders = Order::where('status', 'cancelled')->count()
            + IntercityOrder::where('status', 'cancelled')->count();

        $recentOrders = Order::with('customer', 'driver')->orderByDesc('created_date')->take(10)->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalDrivers',
            'totalOrders',
            'activeOrders',
            'completedOrders',
            'cancelledOrders',
            'recentOrders'
        ));
    }
}
