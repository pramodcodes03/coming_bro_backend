<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index(Request $request)
    {
        $query = Tax::query();

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $taxes = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.taxes.index', compact('taxes'));
    }

    public function create()
    {
        return view('admin.taxes.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'type'    => 'required|string|max:50',
            'tax'     => 'required|numeric|min:0',
            'country' => 'nullable|string|max:100',
            'enable'  => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        Tax::create($validated);

        return redirect()->route('admin.taxes.index')
            ->with('success', 'Tax created successfully.');
    }

    public function edit($id)
    {
        $tax = Tax::findOrFail($id);

        return view('admin.taxes.form', compact('tax'));
    }

    public function update(Request $request, $id)
    {
        $tax = Tax::findOrFail($id);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'type'    => 'required|string|max:50',
            'tax'     => 'required|numeric|min:0',
            'country' => 'nullable|string|max:100',
            'enable'  => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        $tax->update($validated);

        return redirect()->route('admin.taxes.index')
            ->with('success', 'Tax updated successfully.');
    }

    public function destroy($id)
    {
        $tax = Tax::findOrFail($id);
        $tax->delete();

        return redirect()->route('admin.taxes.index')
            ->with('success', 'Tax deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $tax = Tax::findOrFail($id);
        $tax->enable = !$tax->enable;
        $tax->save();

        return redirect()->back()
            ->with('success', 'Tax status updated successfully.');
    }
}
