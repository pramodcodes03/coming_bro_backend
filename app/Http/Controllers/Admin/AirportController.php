<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function index(Request $request)
    {
        $query = Airport::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('airport_name', 'like', "%{$search}%")
                  ->orWhere('city_location', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%");
            });
        }

        $airports = $query->latest()->paginate(15)->withQueryString();

        return view('admin.airports.index', compact('airports'));
    }

    public function create()
    {
        return view('admin.airports.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'airport_name'  => 'required|string|max:255',
            'airport_lat'   => 'required|numeric',
            'airport_lng'   => 'required|numeric',
            'city_location' => 'required|string|max:255',
            'state'         => 'required|string|max:255',
            'country'       => 'required|string|max:255',
            'enable'        => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        Airport::create($validated);

        return redirect()->route('admin.airports.index')
            ->with('success', 'Airport created successfully.');
    }

    public function edit($id)
    {
        $airport = Airport::findOrFail($id);

        return view('admin.airports.form', compact('airport'));
    }

    public function update(Request $request, $id)
    {
        $airport = Airport::findOrFail($id);

        $validated = $request->validate([
            'airport_name'  => 'required|string|max:255',
            'airport_lat'   => 'required|numeric',
            'airport_lng'   => 'required|numeric',
            'city_location' => 'required|string|max:255',
            'state'         => 'required|string|max:255',
            'country'       => 'required|string|max:255',
            'enable'        => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        $airport->update($validated);

        return redirect()->route('admin.airports.index')
            ->with('success', 'Airport updated successfully.');
    }

    public function destroy($id)
    {
        $airport = Airport::findOrFail($id);
        $airport->delete();

        return redirect()->route('admin.airports.index')
            ->with('success', 'Airport deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $airport = Airport::findOrFail($id);
        $airport->enable = !$airport->enable;
        $airport->save();

        return redirect()->route('admin.airports.index')
            ->with('success', 'Airport status updated successfully.');
    }
}
