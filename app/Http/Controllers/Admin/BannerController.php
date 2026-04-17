<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::where('is_deleted', false);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('position', 'like', "%{$search}%");
        }

        $banners = $query->latest()->paginate(15)->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image'       => 'required|image|max:2048',
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'redirect_url'=> 'nullable|string|max:500',
            'position'    => 'required|string|max:255',
            'enable'      => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        $validated['enable'] = $request->boolean('enable');
        $validated['is_deleted'] = false;

        Banner::create($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    public function edit($id)
    {
        $banner = Banner::where('is_deleted', false)->findOrFail($id);

        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::where('is_deleted', false)->findOrFail($id);

        $validated = $request->validate([
            'image'       => 'nullable|image|max:2048',
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'redirect_url'=> 'nullable|string|max:500',
            'position'    => 'required|string|max:255',
            'enable'      => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        } else {
            unset($validated['image']);
        }

        $validated['enable'] = $request->boolean('enable');

        $banner->update($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy($id)
    {
        $banner = Banner::where('is_deleted', false)->findOrFail($id);
        $banner->update(['is_deleted' => true]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $banner = Banner::where('is_deleted', false)->findOrFail($id);
        $banner->enable = !$banner->enable;
        $banner->save();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner status updated successfully.');
    }
}
