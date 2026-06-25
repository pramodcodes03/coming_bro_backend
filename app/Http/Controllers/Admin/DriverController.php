<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DriverExport;
use App\Http\Controllers\Controller;
use App\Models\DriverUser;
use App\Models\Service;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DriverController extends Controller
{
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

        return view('admin.drivers.view', compact('driver'));
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
}
