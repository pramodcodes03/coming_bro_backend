<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverRule;
use Illuminate\Http\Request;

class DriverRuleController extends Controller
{
    public function index(Request $request)
    {
        $query = DriverRule::where('is_deleted', false);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $rules = $query->latest()->paginate(15)->withQueryString();

        return view('admin.driver-rules.index', compact('rules'));
    }

    public function create()
    {
        return view('admin.driver-rules.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'image'  => 'nullable|string|max:500',
            'enable' => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');
        $validated['is_deleted'] = false;

        DriverRule::create($validated);

        return redirect()->route('admin.driver-rules.index')
            ->with('success', 'Driver rule created successfully.');
    }

    public function edit($id)
    {
        $rule = DriverRule::where('is_deleted', false)->findOrFail($id);

        return view('admin.driver-rules.form', compact('rule'));
    }

    public function update(Request $request, $id)
    {
        $rule = DriverRule::where('is_deleted', false)->findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'image'  => 'nullable|string|max:500',
            'enable' => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        $rule->update($validated);

        return redirect()->route('admin.driver-rules.index')
            ->with('success', 'Driver rule updated successfully.');
    }

    public function destroy($id)
    {
        $rule = DriverRule::where('is_deleted', false)->findOrFail($id);
        $rule->update(['is_deleted' => true]);

        return redirect()->route('admin.driver-rules.index')
            ->with('success', 'Driver rule deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $rule = DriverRule::where('is_deleted', false)->findOrFail($id);
        $rule->enable = !$rule->enable;
        $rule->save();

        return redirect()->route('admin.driver-rules.index')
            ->with('success', 'Driver rule status updated successfully.');
    }
}
