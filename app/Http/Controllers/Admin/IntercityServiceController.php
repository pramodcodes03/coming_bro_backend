<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntercityService;
use Illuminate\Http\Request;

class IntercityServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = IntercityService::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $intercityServices = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.intercity-services.index', compact('intercityServices'));
    }

    public function create()
    {
        return view('admin.intercity-services.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                     => 'required|string|max:255',
            'image'                    => 'nullable|string|max:500',
            'km_charge'                => 'nullable|numeric|min:0',
            'basic_fare_km'            => 'nullable|numeric|min:0',
            'basic_fare_charges'       => 'nullable|numeric|min:0',
            'holding_charge_minute'    => 'nullable|numeric|min:0',
            'holding_charges'          => 'nullable|numeric|min:0',
            'ride_time_fare_per_minute'=> 'nullable|numeric|min:0',
            'ac_charges'               => 'nullable|numeric|min:0',
            'is_ac'                    => 'nullable|boolean',
            'enable'                   => 'nullable|boolean',
            'offer_rate'               => 'nullable|boolean',
            'admin_commission'         => 'nullable|array',
        ]);

        $validated['is_ac'] = $request->boolean('is_ac');
        $validated['enable'] = $request->boolean('enable');
        $validated['offer_rate'] = $request->boolean('offer_rate');

        IntercityService::create($validated);

        return redirect()->route('admin.intercity-services.index')
            ->with('success', 'Intercity service created successfully.');
    }

    public function edit($id)
    {
        $intercityService = IntercityService::findOrFail($id);

        return view('admin.intercity-services.form', compact('intercityService'));
    }

    public function update(Request $request, $id)
    {
        $service = IntercityService::findOrFail($id);

        $validated = $request->validate([
            'name'                     => 'required|string|max:255',
            'image'                    => 'nullable|string|max:500',
            'km_charge'                => 'nullable|numeric|min:0',
            'basic_fare_km'            => 'nullable|numeric|min:0',
            'basic_fare_charges'       => 'nullable|numeric|min:0',
            'holding_charge_minute'    => 'nullable|numeric|min:0',
            'holding_charges'          => 'nullable|numeric|min:0',
            'ride_time_fare_per_minute'=> 'nullable|numeric|min:0',
            'ac_charges'               => 'nullable|numeric|min:0',
            'is_ac'                    => 'nullable|boolean',
            'enable'                   => 'nullable|boolean',
            'offer_rate'               => 'nullable|boolean',
            'admin_commission'         => 'nullable|array',
        ]);

        $validated['is_ac'] = $request->boolean('is_ac');
        $validated['enable'] = $request->boolean('enable');
        $validated['offer_rate'] = $request->boolean('offer_rate');

        $service->update($validated);

        return redirect()->route('admin.intercity-services.index')
            ->with('success', 'Intercity service updated successfully.');
    }

    public function destroy($id)
    {
        $service = IntercityService::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.intercity-services.index')
            ->with('success', 'Intercity service deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $service = IntercityService::findOrFail($id);
        $service->enable = !$service->enable;
        $service->save();

        return redirect()->back()
            ->with('success', 'Intercity service status updated successfully.');
    }
}
