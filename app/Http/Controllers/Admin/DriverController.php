<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DriverExport;
use App\Http\Controllers\Controller;
use App\Models\DriverUser;
use App\Models\RechargePlan;
use App\Models\Service;
use App\Models\WalletTransaction;
use App\Services\RideWalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DriverController extends Controller
{
    public function __construct(private readonly RideWalletService $rideWallet)
    {
    }

    public function index(Request $request)
    {
        $drivers = $this->filteredQuery($request)
            ->with('bankDetail')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.drivers.index', compact('drivers'));
    }

    /**
     * Download the currently-filtered driver list as an Excel (.xlsx) file.
     */
    public function export(Request $request)
    {
        $filename = 'drivers_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new DriverExport($this->filteredQuery($request)->orderBy('id', 'desc')),
            $filename
        );
    }

    /**
     * Apply the list filters (shared by the table view and the Excel export so
     * the export always matches exactly what the admin is looking at).
     */
    private function filteredQuery(Request $request)
    {
        $query = DriverUser::query();

        // Broad search box (name / phone / email / IP).
        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('register_ip', 'like', "%{$search}%")
                    ->orWhere('last_login_ip', 'like', "%{$search}%");
            });
        }

        // Dedicated field filters.
        if ($name = trim((string) $request->input('name'))) {
            $query->where('full_name', 'like', "%{$name}%");
        }

        if ($email = trim((string) $request->input('email'))) {
            $query->where('email', 'like', "%{$email}%");
        }

        if ($mobile = trim((string) $request->input('mobile'))) {
            $query->where('phone_number', 'like', "%{$mobile}%");
        }

        if ($ip = trim((string) $request->input('ip'))) {
            $query->where(function ($q) use ($ip) {
                $q->where('register_ip', 'like', "%{$ip}%")
                  ->orWhere('last_login_ip', 'like', "%{$ip}%");
            });
        }

        if (($online = $request->input('online')) !== null && $online !== '') {
            $query->where('is_online', $online === 'online' ? 1 : 0);
        }

        if (($verified = $request->input('verified')) !== null && $verified !== '') {
            $query->where('document_verification', $verified === 'verified' ? 1 : 0);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }

    public function view($id)
    {
        $driver = DriverUser::with(['bankDetail', 'driverDocument', 'reviews', 'orders'])
            ->findOrFail($id);

        // Free rides granted to this driver, newest first, for the history table.
        $freeRideGrants = WalletTransaction::where('user_id', $driver->id)
            ->where('order_type', 'free_ride')
            ->orderByDesc('created_date')
            ->limit(50)
            ->get();

        // Active plans this driver is buyable from the admin panel.
        $rechargePlans = RechargePlan::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.drivers.view', compact('driver', 'freeRideGrants', 'rechargePlans'));
    }

    public function edit($id)
    {
        $driver = DriverUser::findOrFail($id);
        $services = Service::where('enable', true)->get();

        return view('admin.drivers.form', compact('driver', 'services'));
    }

    public function update(Request $request, $id)
    {
        $driver = DriverUser::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20|unique:driver_users,phone_number,' . $id,
            'country_code' => 'nullable|string|max:10',
            'document_verification' => 'nullable|boolean',
            'is_online' => 'nullable|boolean',
            'service_id' => 'nullable|integer|exists:services,id',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'vehicle_number' => 'nullable|string|max:50',
            'vehicle_color' => 'nullable|string|max:50',
            'vehicle_model' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:100',
        ]);

        $validated['document_verification'] = $request->boolean('document_verification');

        // Stamp last_online_at on the offline -> online transition, consistent
        // with toggleStatus() and the driver API. Handled here rather than via
        // the mass update so the timestamp isn't lost when editing via the form.
        $driver->applyOnlineState($request->boolean('is_online'));
        unset($validated['is_online']);

        $driver->fill($validated);
        $driver->save();

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver updated successfully.');
    }

    public function destroy($id)
    {
        $driver = DriverUser::findOrFail($id);
        $driver->delete();

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $driver = DriverUser::findOrFail($id);
        $driver->applyOnlineState(! $driver->is_online);
        $driver->save();

        return redirect()->back()
            ->with('success', 'Driver online status updated successfully.');
    }

    /**
     * Grant complimentary (free) rides to a driver. These reuse the same ride
     * wallet a paid recharge fills (remaining_rides / total_rides), so the
     * driver can immediately use them for simple or return rides. We also bump
     * free_rides_total (lifetime free tally) and write a wallet_transactions row
     * with order_type='free_ride' so the grant shows up in the driver app's
     * plan/recharge history exactly like a recharge, but at ₹0.
     */
    public function grantFreeRides(Request $request, $id)
    {
        $driver = DriverUser::findOrFail($id);

        $validated = $request->validate([
            'rides'         => 'required|integer|min:1|max:1000',
            'note'          => 'nullable|string|max:255',
            'validity_days' => 'nullable|integer|min:0|max:3650',
        ]);

        $rides = (int) $validated['rides'];
        $validityDays = isset($validated['validity_days']) ? (int) $validated['validity_days'] : 0;
        $note = trim($validated['note'] ?? '') ?: "Free {$rides} ".($rides === 1 ? 'ride' : 'rides').' granted by admin';

        DB::transaction(function () use ($driver, $rides, $validityDays, $note) {
            // Audit + history row. Amount/GST are zero (it's free); we stash the
            // ride count in the note and mark order_type so the app and admin can
            // render it distinctly from a paid recharge.
            $transaction = WalletTransaction::create([
                'user_id'             => $driver->id,
                'amount'              => 0,
                'base_amount'         => 0,
                'gst_percent'         => 0,
                'gst_amount'          => 0,
                'total_amount'        => 0,
                'recharge_plan_id'    => null,
                'transaction_id'      => 'FREE-'.$driver->id.'-'.now()->format('YmdHis'),
                'payment_type'        => 'free',
                'note'                => $note,
                'order_type'          => 'free_ride',
                'user_type'           => 'driver',
                'granted_by_admin_id' => Auth::guard('admin')->id() ?? Auth::id(),
                'created_date'        => now(),
            ]);

            // Credit as a ride lot (FIFO + optional expiry). The service keeps
            // remaining_rides / total_rides in sync from the lot ledger.
            $this->rideWallet->credit(
                driver: $driver,
                rides: $rides,
                source: 'free',
                walletTransactionId: $transaction->id,
                validityDays: $validityDays > 0 ? $validityDays : null,
            );

            // free_rides_total is a separate lifetime tally of free rides given.
            $driver->free_rides_total = (int) $driver->free_rides_total + $rides;
            $driver->saveQuietly();
        });

        $driver->refresh();

        return redirect()->route('admin.drivers.view', $driver->id)
            ->with('success', "Granted {$rides} free ".($rides === 1 ? 'ride' : 'rides')." to {$driver->full_name}. They now have {$driver->remaining_rides} rides remaining.");
    }

    /**
     * Assign (admin "buy") a recharge plan to a driver. This credits the plan's
     * rides as a new lot with the plan's own validity, and records a paid-style
     * wallet_transactions row (with the plan's GST-inclusive price split) so it
     * appears in the driver app's recharge history and /current-plans exactly
     * like a self-purchased recharge — just marked as admin-assigned.
     */
    public function assignPlan(Request $request, $id)
    {
        $driver = DriverUser::findOrFail($id);

        $validated = $request->validate([
            'recharge_plan_id' => 'required|integer|exists:recharge_plans,id',
            'note'             => 'nullable|string|max:255',
        ]);

        $plan = RechargePlan::findOrFail($validated['recharge_plan_id']);
        $note = trim($validated['note'] ?? '') ?: "Plan \"{$plan->label}\" assigned by admin";

        if ((int) $plan->rides <= 0) {
            return redirect()->route('admin.drivers.view', $driver->id)
                ->withErrors(['recharge_plan_id' => 'This plan grants 0 rides — pick a plan with a ride count.']);
        }

        // GST-inclusive split of the plan price (whole-rupee base, GST = price − base).
        $price = (float) $plan->price;
        $gstPercent = (float) $plan->gst_percent;
        $base = $gstPercent > 0 ? round($price / (1 + $gstPercent / 100)) : round($price);
        $gstAmount = round($price - $base, 2);

        DB::transaction(function () use ($driver, $plan, $note, $price, $gstPercent, $base, $gstAmount) {
            $transaction = WalletTransaction::create([
                'user_id'             => $driver->id,
                'amount'              => $price,
                'base_amount'         => $base,
                'gst_percent'         => $gstPercent,
                'gst_amount'          => $gstAmount,
                'total_amount'        => $price,
                'recharge_plan_id'    => $plan->id,
                'transaction_id'      => 'ADMIN-'.$driver->id.'-'.now()->format('YmdHis'),
                'payment_type'        => 'admin_assigned',
                'note'                => $note,
                'order_type'          => 'wallet_recharge',
                'user_type'           => 'driver',
                'granted_by_admin_id' => Auth::guard('admin')->id() ?? Auth::id(),
                'created_date'        => now(),
            ]);

            // Credit as a ride lot — takes the plan's ride count and validity.
            $this->rideWallet->creditFromPlan(
                driver: $driver,
                plan: $plan,
                source: 'recharge',
                walletTransactionId: $transaction->id,
            );
        });

        $driver->refresh();

        return redirect()->route('admin.drivers.view', $driver->id)
            ->with('success', "Assigned \"{$plan->label}\" (+{$plan->rides} rides) to {$driver->full_name}. They now have {$driver->remaining_rides} rides remaining.");
    }
}
