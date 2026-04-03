<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::query()
            ->leftJoin('states', 'cities.state_id', '=', 'states.id')
            ->select('cities.*', 'states.name as state_name');

        if ($search = $request->input('search')) {
            $query->where('cities.name', 'like', "%{$search}%");
        }

        $cities = $query->orderBy('cities.id', 'desc')->paginate(15)->withQueryString();

        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        $states = State::orderBy('name')->get();

        return view('admin.cities.form', compact('states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'state_id' => 'required|integer|exists:states,id',
            'enable'   => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        City::create($validated);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City created successfully.');
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        $states = State::orderBy('name')->get();

        return view('admin.cities.form', compact('city', 'states'));
    }

    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'state_id' => 'required|integer|exists:states,id',
            'enable'   => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        $city->update($validated);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully.');
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'City deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $city = City::findOrFail($id);
        $city->enable = !$city->enable;
        $city->save();

        return redirect()->back()
            ->with('success', 'City status updated successfully.');
    }
}
