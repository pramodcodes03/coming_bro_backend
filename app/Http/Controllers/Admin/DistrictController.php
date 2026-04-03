<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $query = District::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $districts = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.districts.index', compact('districts'));
    }

    public function create()
    {
        return view('admin.districts.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'publish' => 'nullable|boolean',
        ]);

        $validated['publish'] = $request->boolean('publish');

        District::create($validated);

        return redirect()->route('admin.districts.index')
            ->with('success', 'District created successfully.');
    }

    public function edit($id)
    {
        $district = District::findOrFail($id);

        return view('admin.districts.form', compact('district'));
    }

    public function update(Request $request, $id)
    {
        $district = District::findOrFail($id);

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'publish' => 'nullable|boolean',
        ]);

        $validated['publish'] = $request->boolean('publish');

        $district->update($validated);

        return redirect()->route('admin.districts.index')
            ->with('success', 'District updated successfully.');
    }

    public function destroy($id)
    {
        $district = District::findOrFail($id);
        $district->delete();

        return redirect()->route('admin.districts.index')
            ->with('success', 'District deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $district = District::findOrFail($id);
        $district->publish = !$district->publish;
        $district->save();

        return redirect()->back()
            ->with('success', 'District status updated successfully.');
    }
}
