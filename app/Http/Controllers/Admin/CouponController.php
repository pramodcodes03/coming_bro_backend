<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::where('is_deleted', false);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $coupons = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'code'      => 'required|string|max:50|unique:coupons,code',
            'amount'    => 'required|numeric|min:0',
            'type'      => 'required|string|max:50',
            'enable'    => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
            'validity'  => 'nullable|date',
        ]);

        $validated['enable'] = $request->boolean('enable');
        $validated['is_public'] = $request->boolean('is_public');
        $validated['is_deleted'] = false;

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function edit($id)
    {
        $coupon = Coupon::where('is_deleted', false)->findOrFail($id);

        return view('admin.coupons.form', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::where('is_deleted', false)->findOrFail($id);

        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'code'      => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'amount'    => 'required|numeric|min:0',
            'type'      => 'required|string|max:50',
            'enable'    => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
            'validity'  => 'nullable|date',
        ]);

        $validated['enable'] = $request->boolean('enable');
        $validated['is_public'] = $request->boolean('is_public');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->is_deleted = true;
        $coupon->save();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $coupon = Coupon::where('is_deleted', false)->findOrFail($id);
        $coupon->enable = !$coupon->enable;
        $coupon->save();

        return redirect()->back()
            ->with('success', 'Coupon status updated successfully.');
    }
}
