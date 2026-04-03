<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FuelType;
use Illuminate\Http\Request;

class FuelTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = FuelType::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $fuelTypes = $query->latest()->paginate(15)->withQueryString();

        return view('admin.fuel-types.index', compact('fuelTypes'));
    }

    public function create()
    {
        return view('admin.fuel-types.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'enable' => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        FuelType::create($validated);

        return redirect()->route('admin.fuel-types.index')
            ->with('success', 'Fuel type created successfully.');
    }

    public function edit($id)
    {
        $fuelType = FuelType::findOrFail($id);

        return view('admin.fuel-types.form', compact('fuelType'));
    }

    public function update(Request $request, $id)
    {
        $fuelType = FuelType::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'enable' => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        $fuelType->update($validated);

        return redirect()->route('admin.fuel-types.index')
            ->with('success', 'Fuel type updated successfully.');
    }

    public function destroy($id)
    {
        $fuelType = FuelType::findOrFail($id);
        $fuelType->delete();

        return redirect()->route('admin.fuel-types.index')
            ->with('success', 'Fuel type deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $fuelType = FuelType::findOrFail($id);
        $fuelType->enable = !$fuelType->enable;
        $fuelType->save();

        return redirect()->route('admin.fuel-types.index')
            ->with('success', 'Fuel type status updated successfully.');
    }
}
