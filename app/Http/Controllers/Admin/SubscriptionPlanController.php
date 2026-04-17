<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index(Request $request)
    {
        $query = SubscriptionPlan::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $plans = $query->latest()->paginate(15)->withQueryString();

        return view('admin.subscription-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.subscription-plans.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'duration'    => 'required|integer|min:1',
            'description' => 'nullable|string',
            'enable'      => 'nullable|boolean',
            'gst'         => 'nullable|numeric|min:0',
            'tds'         => 'nullable|numeric|min:0',
            'ride'        => 'nullable|integer|min:0',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('subscription-plans', 'public');
        }

        $validated['enable'] = $request->boolean('enable');

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    public function edit($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        return view('admin.subscription-plans.form', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'duration'    => 'required|integer|min:1',
            'description' => 'nullable|string',
            'enable'      => 'nullable|boolean',
            'gst'         => 'nullable|numeric|min:0',
            'tds'         => 'nullable|numeric|min:0',
            'ride'        => 'nullable|integer|min:0',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('subscription-plans', 'public');
        } else {
            unset($validated['image']);
        }

        $validated['enable'] = $request->boolean('enable');

        $plan->update($validated);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    public function destroy($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->enable = !$plan->enable;
        $plan->save();

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan status updated successfully.');
    }
}
