<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $services = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                    => 'required|string|max:255',
            'image'                    => 'nullable|image|max:2048',
            'enable'                   => 'nullable|boolean',
            'offer_rate'               => 'nullable|boolean',
            'intercity_type'           => 'nullable|boolean',
            'basic_fare_charges'       => 'nullable|numeric|min:0',
            'basic_fare_km'            => 'nullable|numeric|min:0',
            'km_charge'                => 'nullable|numeric|min:0',
            'ride_time_fare_per_minute'=> 'nullable|numeric|min:0',
            'holding_charge_minute'    => 'nullable|numeric|min:0',
            'holding_charges'          => 'nullable|numeric|min:0',
            'admin_commission'         => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $validated['enable'] = $request->boolean('enable');
        $validated['offer_rate'] = $request->boolean('offer_rate');
        $validated['intercity_type'] = $request->boolean('intercity_type');

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);

        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'title'                    => 'required|string|max:255',
            'image'                    => 'nullable|image|max:2048',
            'enable'                   => 'nullable|boolean',
            'offer_rate'               => 'nullable|boolean',
            'intercity_type'           => 'nullable|boolean',
            'basic_fare_charges'       => 'nullable|numeric|min:0',
            'basic_fare_km'            => 'nullable|numeric|min:0',
            'km_charge'                => 'nullable|numeric|min:0',
            'ride_time_fare_per_minute'=> 'nullable|numeric|min:0',
            'holding_charge_minute'    => 'nullable|numeric|min:0',
            'holding_charges'          => 'nullable|numeric|min:0',
            'admin_commission'         => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        } else {
            unset($validated['image']);
        }

        $validated['enable'] = $request->boolean('enable');
        $validated['offer_rate'] = $request->boolean('offer_rate');
        $validated['intercity_type'] = $request->boolean('intercity_type');

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);
        $service->enable = !$service->enable;
        $service->save();

        return redirect()->back()
            ->with('success', 'Service status updated successfully.');
    }
}
