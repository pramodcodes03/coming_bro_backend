<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RechargePlan;
use Illuminate\Http\Request;

class RechargePlanController extends Controller
{
    public function index(Request $request)
    {
        $query = RechargePlan::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('label', 'like', "%{$search}%");
        }

        $plans = $query->orderBy('sort_order')->paginate(15)->withQueryString();

        return view('admin.recharge-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.recharge-plans.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label'          => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'required|numeric|min:0',
            'discount_pct'   => 'nullable|integer|min:0|max:100',
            'is_best_value'  => 'nullable|boolean',
            'is_active'      => 'nullable|boolean',
            'sort_order'     => 'nullable|integer|min:0',
            'terms_title'    => 'nullable|string|max:255',
            'terms_footer'   => 'nullable|string',
            'benefits'       => 'nullable|array',
            'benefits.*.icon'     => 'nullable|string',
            'benefits.*.title'    => 'required|string',
            'benefits.*.subtitle' => 'nullable|string',
            'terms_points'   => 'nullable|array',
            'terms_points.*' => 'nullable|string',
        ]);

        $validated['is_best_value'] = $request->boolean('is_best_value');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['discount_pct'] = $validated['discount_pct'] ?? 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Filter out empty benefits and terms
        if (isset($validated['benefits'])) {
            $validated['benefits'] = array_values(array_filter($validated['benefits'], fn($b) => !empty($b['title'])));
        }
        if (isset($validated['terms_points'])) {
            $validated['terms_points'] = array_values(array_filter($validated['terms_points'], fn($t) => !empty($t)));
        }

        RechargePlan::create($validated);

        return redirect()->route('admin.recharge-plans.index')
            ->with('success', 'Recharge plan created successfully.');
    }

    public function edit($id)
    {
        $plan = RechargePlan::findOrFail($id);

        return view('admin.recharge-plans.form', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = RechargePlan::findOrFail($id);

        $validated = $request->validate([
            'label'          => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'required|numeric|min:0',
            'discount_pct'   => 'nullable|integer|min:0|max:100',
            'is_best_value'  => 'nullable|boolean',
            'is_active'      => 'nullable|boolean',
            'sort_order'     => 'nullable|integer|min:0',
            'terms_title'    => 'nullable|string|max:255',
            'terms_footer'   => 'nullable|string',
            'benefits'       => 'nullable|array',
            'benefits.*.icon'     => 'nullable|string',
            'benefits.*.title'    => 'required|string',
            'benefits.*.subtitle' => 'nullable|string',
            'terms_points'   => 'nullable|array',
            'terms_points.*' => 'nullable|string',
        ]);

        $validated['is_best_value'] = $request->boolean('is_best_value');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['discount_pct'] = $validated['discount_pct'] ?? 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if (isset($validated['benefits'])) {
            $validated['benefits'] = array_values(array_filter($validated['benefits'], fn($b) => !empty($b['title'])));
        }
        if (isset($validated['terms_points'])) {
            $validated['terms_points'] = array_values(array_filter($validated['terms_points'], fn($t) => !empty($t)));
        }

        $plan->update($validated);

        return redirect()->route('admin.recharge-plans.index')
            ->with('success', 'Recharge plan updated successfully.');
    }

    public function destroy($id)
    {
        $plan = RechargePlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.recharge-plans.index')
            ->with('success', 'Recharge plan deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $plan = RechargePlan::findOrFail($id);
        $plan->is_active = !$plan->is_active;
        $plan->save();

        return redirect()->route('admin.recharge-plans.index')
            ->with('success', 'Recharge plan status updated successfully.');
    }
}
