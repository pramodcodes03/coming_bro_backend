<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleCompany;
use Illuminate\Http\Request;

class VehicleCompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = VehicleCompany::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $companies = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.vehicle-companies.index', compact('companies'));
    }

    public function create()
    {
        return view('admin.vehicle-companies.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'enable' => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        VehicleCompany::create($validated);

        return redirect()->route('admin.vehicle-companies.index')
            ->with('success', 'Vehicle company created successfully.');
    }

    public function edit($id)
    {
        $company = VehicleCompany::findOrFail($id);

        return view('admin.vehicle-companies.form', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $company = VehicleCompany::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'enable' => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        $company->update($validated);

        return redirect()->route('admin.vehicle-companies.index')
            ->with('success', 'Vehicle company updated successfully.');
    }

    public function destroy($id)
    {
        $company = VehicleCompany::findOrFail($id);
        $company->delete();

        return redirect()->route('admin.vehicle-companies.index')
            ->with('success', 'Vehicle company deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $company = VehicleCompany::findOrFail($id);
        $company->enable = !$company->enable;
        $company->save();

        return redirect()->back()
            ->with('success', 'Vehicle company status updated successfully.');
    }
}
