<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index(Request $request)
    {
        $query = Language::where('is_deleted', false);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $languages = $query->latest()->paginate(15)->withQueryString();

        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:10|unique:languages,code',
            'image'  => 'nullable|string|max:500',
            'enable' => 'nullable|boolean',
            'is_rtl' => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');
        $validated['is_rtl'] = $request->boolean('is_rtl');
        $validated['is_deleted'] = false;

        Language::create($validated);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language created successfully.');
    }

    public function edit($id)
    {
        $language = Language::where('is_deleted', false)->findOrFail($id);

        return view('admin.languages.form', compact('language'));
    }

    public function update(Request $request, $id)
    {
        $language = Language::where('is_deleted', false)->findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:10|unique:languages,code,' . $id,
            'image'  => 'nullable|string|max:500',
            'enable' => 'nullable|boolean',
            'is_rtl' => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');
        $validated['is_rtl'] = $request->boolean('is_rtl');

        $language->update($validated);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language updated successfully.');
    }

    public function destroy($id)
    {
        $language = Language::where('is_deleted', false)->findOrFail($id);
        $language->update(['is_deleted' => true]);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $language = Language::where('is_deleted', false)->findOrFail($id);
        $language->enable = !$language->enable;
        $language->save();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language status updated successfully.');
    }
}
