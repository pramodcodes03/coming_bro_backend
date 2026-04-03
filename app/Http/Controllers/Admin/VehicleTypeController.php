<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = VehicleType::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $vehicleTypes = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        // Eager load service for display
        $serviceIds = $vehicleTypes->pluck('service_id')->unique()->filter();
        $services = Service::whereIn('id', $serviceIds)->pluck('title', 'id');

        return view('admin.vehicle-types.index', compact('vehicleTypes', 'services'));
    }

    public function create()
    {
        $services = Service::where('enable', true)->get();

        return view('admin.vehicle-types.form', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'enable'      => 'nullable|boolean',
            'front_image' => 'nullable|string|max:500',
            'back_image'  => 'nullable|string|max:500',
            'service_id'  => 'nullable|integer|exists:services,id',
        ]);

        $validated['enable'] = $request->boolean('enable');

        VehicleType::create($validated);

        return redirect()->route('admin.vehicle-types.index')
            ->with('success', 'Vehicle type created successfully.');
    }

    public function edit($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        $services = Service::where('enable', true)->get();

        return view('admin.vehicle-types.form', compact('vehicleType', 'services'));
    }

    public function update(Request $request, $id)
    {
        $vehicleType = VehicleType::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'enable'      => 'nullable|boolean',
            'front_image' => 'nullable|string|max:500',
            'back_image'  => 'nullable|string|max:500',
            'service_id'  => 'nullable|integer|exists:services,id',
        ]);

        $validated['enable'] = $request->boolean('enable');

        $vehicleType->update($validated);

        return redirect()->route('admin.vehicle-types.index')
            ->with('success', 'Vehicle type updated successfully.');
    }

    public function destroy($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        $vehicleType->delete();

        return redirect()->route('admin.vehicle-types.index')
            ->with('success', 'Vehicle type deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        $vehicleType->enable = !$vehicleType->enable;
        $vehicleType->save();

        return redirect()->back()
            ->with('success', 'Vehicle type status updated successfully.');
    }
}
