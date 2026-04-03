<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone_number' => 'required|string|max:20',
            'country_code' => 'nullable|string|max:10',
            'is_active'    => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Customer::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('admin.customers.form', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone_number' => 'required|string|max:20',
            'country_code' => 'nullable|string|max:10',
            'is_active'    => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->is_active = !$customer->is_active;
        $customer->save();

        return redirect()->back()
            ->with('success', 'Customer status updated successfully.');
    }
}
