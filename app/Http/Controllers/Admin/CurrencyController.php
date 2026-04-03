<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Currency::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%");
            });
        }

        $currencies = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.currencies.index', compact('currencies'));
    }

    public function create()
    {
        return view('admin.currencies.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'symbol'          => 'required|string|max:10',
            'code'            => 'required|string|max:10',
            'name'            => 'required|string|max:100',
            'enable'          => 'nullable|boolean',
            'symbol_at_right' => 'nullable|boolean',
            'decimal_digits'  => 'nullable|integer|min:0|max:10',
        ]);

        $validated['enable'] = $request->boolean('enable');
        $validated['symbol_at_right'] = $request->boolean('symbol_at_right');

        Currency::create($validated);

        return redirect()->route('admin.currencies.index')
            ->with('success', 'Currency created successfully.');
    }

    public function edit($id)
    {
        $currency = Currency::findOrFail($id);

        return view('admin.currencies.form', compact('currency'));
    }

    public function update(Request $request, $id)
    {
        $currency = Currency::findOrFail($id);

        $validated = $request->validate([
            'symbol'          => 'required|string|max:10',
            'code'            => 'required|string|max:10',
            'name'            => 'required|string|max:100',
            'enable'          => 'nullable|boolean',
            'symbol_at_right' => 'nullable|boolean',
            'decimal_digits'  => 'nullable|integer|min:0|max:10',
        ]);

        $validated['enable'] = $request->boolean('enable');
        $validated['symbol_at_right'] = $request->boolean('symbol_at_right');

        $currency->update($validated);

        return redirect()->route('admin.currencies.index')
            ->with('success', 'Currency updated successfully.');
    }

    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);
        $currency->delete();

        return redirect()->route('admin.currencies.index')
            ->with('success', 'Currency deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $currency = Currency::findOrFail($id);
        $currency->enable = !$currency->enable;
        $currency->save();

        return redirect()->back()
            ->with('success', 'Currency status updated successfully.');
    }
}
