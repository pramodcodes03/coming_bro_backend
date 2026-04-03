<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsController extends Controller
{
    public function index(Request $request)
    {
        $query = CmsPage::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $pages = $query->latest()->paginate(15)->withQueryString();

        return view('admin.cms.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.cms.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:cms_pages,slug',
            'description' => 'required|string',
            'publish'     => 'nullable|boolean',
        ]);

        $validated['publish'] = $request->boolean('publish');

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        CmsPage::create($validated);

        return redirect()->route('admin.cms.index')
            ->with('success', 'CMS page created successfully.');
    }

    public function edit($id)
    {
        $page = CmsPage::findOrFail($id);

        return view('admin.cms.form', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = CmsPage::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:cms_pages,slug,' . $id,
            'description' => 'required|string',
            'publish'     => 'nullable|boolean',
        ]);

        $validated['publish'] = $request->boolean('publish');

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $page->update($validated);

        return redirect()->route('admin.cms.index')
            ->with('success', 'CMS page updated successfully.');
    }

    public function destroy($id)
    {
        $page = CmsPage::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.cms.index')
            ->with('success', 'CMS page deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $page = CmsPage::findOrFail($id);
        $page->publish = !$page->publish;
        $page->save();

        return redirect()->route('admin.cms.index')
            ->with('success', 'CMS page status updated successfully.');
    }
}
