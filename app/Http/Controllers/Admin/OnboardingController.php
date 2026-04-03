<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnboardingScreen;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function index(Request $request)
    {
        $query = OnboardingScreen::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        $screens = $query->orderBy('order')->paginate(15)->withQueryString();

        return view('admin.onboarding.index', compact('screens'));
    }

    public function create()
    {
        return view('admin.onboarding.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'nullable|string|max:500',
            'type'        => 'required|string|max:100',
            'order'       => 'required|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        OnboardingScreen::create($validated);

        return redirect()->route('admin.onboarding.index')
            ->with('success', 'Onboarding screen created successfully.');
    }

    public function edit($id)
    {
        $screen = OnboardingScreen::findOrFail($id);

        return view('admin.onboarding.form', compact('screen'));
    }

    public function update(Request $request, $id)
    {
        $screen = OnboardingScreen::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'nullable|string|max:500',
            'type'        => 'required|string|max:100',
            'order'       => 'required|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $screen->update($validated);

        return redirect()->route('admin.onboarding.index')
            ->with('success', 'Onboarding screen updated successfully.');
    }

    public function destroy($id)
    {
        $screen = OnboardingScreen::findOrFail($id);
        $screen->delete();

        return redirect()->route('admin.onboarding.index')
            ->with('success', 'Onboarding screen deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $screen = OnboardingScreen::findOrFail($id);
        $screen->is_active = !$screen->is_active;
        $screen->save();

        return redirect()->route('admin.onboarding.index')
            ->with('success', 'Onboarding screen status updated successfully.');
    }
}
