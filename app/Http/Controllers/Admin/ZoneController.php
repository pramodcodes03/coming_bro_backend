<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index(Request $request)
    {
        $query = Zone::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $zones = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.zones.index', compact('zones'));
    }

    public function create()
    {
        return view('admin.zones.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'area'      => 'nullable|array',
            'publish'   => 'nullable|boolean',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $validated['publish'] = $request->boolean('publish');

        Zone::create($validated);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone created successfully.');
    }

    public function edit($id)
    {
        $zone = Zone::findOrFail($id);

        return view('admin.zones.form', compact('zone'));
    }

    public function update(Request $request, $id)
    {
        $zone = Zone::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'area'      => 'nullable|array',
            'publish'   => 'nullable|boolean',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $validated['publish'] = $request->boolean('publish');

        $zone->update($validated);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone updated successfully.');
    }

    public function destroy($id)
    {
        $zone = Zone::findOrFail($id);
        $zone->delete();

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $zone = Zone::findOrFail($id);
        $zone->publish = !$zone->publish;
        $zone->save();

        return redirect()->back()
            ->with('success', 'Zone status updated successfully.');
    }
}
