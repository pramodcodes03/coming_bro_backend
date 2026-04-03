<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsuranceCompany;
use Illuminate\Http\Request;

class InsuranceCompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = InsuranceCompany::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $companies = $query->latest()->paginate(15)->withQueryString();

        return view('admin.insurance-companies.index', compact('companies'));
    }

    public function create()
    {
        return view('admin.insurance-companies.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        InsuranceCompany::create($validated);

        return redirect()->route('admin.insurance-companies.index')
            ->with('success', 'Insurance company created successfully.');
    }

    public function edit($id)
    {
        $company = InsuranceCompany::findOrFail($id);

        return view('admin.insurance-companies.form', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $company = InsuranceCompany::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $company->update($validated);

        return redirect()->route('admin.insurance-companies.index')
            ->with('success', 'Insurance company updated successfully.');
    }

    public function destroy($id)
    {
        $company = InsuranceCompany::findOrFail($id);
        $company->delete();

        return redirect()->route('admin.insurance-companies.index')
            ->with('success', 'Insurance company deleted successfully.');
    }
}
