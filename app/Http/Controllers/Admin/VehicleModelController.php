<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleCompany;
use App\Models\VehicleModelEntry;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    public function index(Request $request)
    {
        $query = VehicleModelEntry::query()
            ->leftJoin('vehicle_companies', 'vehicle_models.company_id', '=', 'vehicle_companies.id')
            ->select('vehicle_models.*', 'vehicle_companies.name as company_name');

        if ($search = $request->input('search')) {
            $query->where('vehicle_models.name', 'like', "%{$search}%");
        }

        $models = $query->orderBy('vehicle_models.id', 'desc')->paginate(15)->withQueryString();

        return view('admin.vehicle-models.index', compact('models'));
    }

    public function create()
    {
        $companies = VehicleCompany::where('enable', true)->get();

        return view('admin.vehicle-models.form', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'company_id' => 'required|integer|exists:vehicle_companies,id',
            'enable'     => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        VehicleModelEntry::create($validated);

        return redirect()->route('admin.vehicle-models.index')
            ->with('success', 'Vehicle model created successfully.');
    }

    public function edit($id)
    {
        $model = VehicleModelEntry::findOrFail($id);
        $companies = VehicleCompany::where('enable', true)->get();

        return view('admin.vehicle-models.form', compact('model', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $model = VehicleModelEntry::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'company_id' => 'required|integer|exists:vehicle_companies,id',
            'enable'     => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        $model->update($validated);

        return redirect()->route('admin.vehicle-models.index')
            ->with('success', 'Vehicle model updated successfully.');
    }

    public function destroy($id)
    {
        $model = VehicleModelEntry::findOrFail($id);
        $model->delete();

        return redirect()->route('admin.vehicle-models.index')
            ->with('success', 'Vehicle model deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $model = VehicleModelEntry::findOrFail($id);
        $model->enable = !$model->enable;
        $model->save();

        return redirect()->back()
            ->with('success', 'Vehicle model status updated successfully.');
    }
}
