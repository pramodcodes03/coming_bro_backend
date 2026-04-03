<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverUser;
use App\Models\Service;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = DriverUser::with('bankDetail');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $drivers = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.drivers.index', compact('drivers'));
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
            'full_name'              => 'required|string|max:255',
            'email'                  => 'nullable|email|max:255',
            'phone_number'           => 'nullable|string|max:20',
            'country_code'           => 'nullable|string|max:10',
            'document_verification'  => 'nullable|boolean',
            'is_online'              => 'nullable|boolean',
            'service_id'             => 'nullable|integer|exists:services,id',
            'city'                   => 'nullable|string|max:255',
            'state'                  => 'nullable|string|max:255',
            'gender'                 => 'nullable|string|max:20',
            'address'                => 'nullable|string|max:500',
            'vehicle_number'         => 'nullable|string|max:50',
            'vehicle_color'          => 'nullable|string|max:50',
            'vehicle_model'          => 'nullable|string|max:100',
            'company_name'           => 'nullable|string|max:100',
        ]);

        $validated['document_verification'] = $request->boolean('document_verification');
        $validated['is_online'] = $request->boolean('is_online');

        $driver->update($validated);

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
        $driver->is_online = !$driver->is_online;
        $driver->save();

        return redirect()->back()
            ->with('success', 'Driver online status updated successfully.');
    }
}
