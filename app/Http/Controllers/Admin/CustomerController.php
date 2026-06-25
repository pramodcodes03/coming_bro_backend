<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomerExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = $this->filteredQuery($request)
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Download the currently-filtered customer list as an Excel (.xlsx) file.
     */
    public function export(Request $request)
    {
        $filename = 'customers_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new CustomerExport($this->filteredQuery($request)->orderBy('id', 'desc')),
            $filename
        );
    }

    /**
     * Apply the list filters (shared by the table view and the Excel export so
     * the export always matches exactly what the admin is looking at).
     */
    private function filteredQuery(Request $request)
    {
        $query = Customer::query();

        // Broad search box (name / email / phone / IP).
        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('register_ip', 'like', "%{$search}%")
                  ->orWhere('last_login_ip', 'like', "%{$search}%");
            });
        }

        // Dedicated field filters.
        if ($name = trim((string) $request->input('name'))) {
            $query->where('full_name', 'like', "%{$name}%");
        }

        if ($email = trim((string) $request->input('email'))) {
            $query->where('email', 'like', "%{$email}%");
        }

        if ($mobile = trim((string) $request->input('mobile'))) {
            $query->where('phone_number', 'like', "%{$mobile}%");
        }

        if ($ip = trim((string) $request->input('ip'))) {
            $query->where(function ($q) use ($ip) {
                $q->where('register_ip', 'like', "%{$ip}%")
                  ->orWhere('last_login_ip', 'like', "%{$ip}%");
            });
        }

        if (($status = $request->input('status')) !== null && $status !== '') {
            $query->where('is_active', $status === 'active' ? 1 : 0);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
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
            'phone_number' => 'required|string|max:20|unique:customers,phone_number',
            'country_code' => 'nullable|string|max:10',
            'is_active'    => 'nullable|boolean',
        ], [
            'phone_number.unique' => 'A customer with this phone number already exists.',
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
            'phone_number' => 'required|string|max:20|unique:customers,phone_number,' . $id,
            'country_code' => 'nullable|string|max:10',
            'is_active'    => 'nullable|boolean',
        ], [
            'phone_number.unique' => 'A customer with this phone number already exists.',
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
