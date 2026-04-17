<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreightVehicle;
use Illuminate\Http\Request;

class FreightVehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = FreightVehicle::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $freightVehicles = $query->latest()->paginate(15)->withQueryString();

        return view('admin.freight-vehicles.index', compact('freightVehicles'));
    }

    public function create()
    {
        return view('admin.freight-vehicles.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                    => 'required|string|max:255',
            'image'                   => 'nullable|image|max:2048',
            'description'             => 'nullable|string',
            'length'                  => 'nullable|numeric|min:0',
            'width'                   => 'nullable|numeric|min:0',
            'height'                  => 'nullable|numeric|min:0',
            'weight'                  => 'nullable|numeric|min:0',
            'km_charge'               => 'nullable|numeric|min:0',
            'basic_fare_km'           => 'nullable|numeric|min:0',
            'basic_fare_charges'      => 'nullable|numeric|min:0',
            'holding_charge_minute'   => 'nullable|numeric|min:0',
            'holding_charges'         => 'nullable|numeric|min:0',
            'loading_unloading_charges' => 'nullable|numeric|min:0',
            'enable'                  => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('freight-vehicles', 'public');
        }

        $validated['enable'] = $request->boolean('enable');

        FreightVehicle::create($validated);

        return redirect()->route('admin.freight-vehicles.index')
            ->with('success', 'Freight vehicle created successfully.');
    }

    public function edit($id)
    {
        $freightVehicle = FreightVehicle::findOrFail($id);

        return view('admin.freight-vehicles.form', compact('freightVehicle'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = FreightVehicle::findOrFail($id);

        $validated = $request->validate([
            'name'                    => 'required|string|max:255',
            'image'                   => 'nullable|image|max:2048',
            'description'             => 'nullable|string',
            'length'                  => 'nullable|numeric|min:0',
            'width'                   => 'nullable|numeric|min:0',
            'height'                  => 'nullable|numeric|min:0',
            'weight'                  => 'nullable|numeric|min:0',
            'km_charge'               => 'nullable|numeric|min:0',
            'basic_fare_km'           => 'nullable|numeric|min:0',
            'basic_fare_charges'      => 'nullable|numeric|min:0',
            'holding_charge_minute'   => 'nullable|numeric|min:0',
            'holding_charges'         => 'nullable|numeric|min:0',
            'loading_unloading_charges' => 'nullable|numeric|min:0',
            'enable'                  => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('freight-vehicles', 'public');
        } else {
            unset($validated['image']);
        }

        $validated['enable'] = $request->boolean('enable');

        $vehicle->update($validated);

        return redirect()->route('admin.freight-vehicles.index')
            ->with('success', 'Freight vehicle updated successfully.');
    }

    public function destroy($id)
    {
        $vehicle = FreightVehicle::findOrFail($id);
        $vehicle->delete();

        return redirect()->route('admin.freight-vehicles.index')
            ->with('success', 'Freight vehicle deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $vehicle = FreightVehicle::findOrFail($id);
        $vehicle->enable = !$vehicle->enable;
        $vehicle->save();

        return redirect()->route('admin.freight-vehicles.index')
            ->with('success', 'Freight vehicle status updated successfully.');
    }
}
