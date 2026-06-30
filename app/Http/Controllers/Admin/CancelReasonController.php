<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancelReason;
use Illuminate\Http\Request;

class CancelReasonController extends Controller
{
    public function index(Request $request)
    {
        $query = CancelReason::query();

        if ($search = trim((string) $request->input('search'))) {
            $query->where('reason', 'like', "%{$search}%");
        }

        if (in_array($request->input('applies_to'), ['customer', 'driver', 'both'], true)) {
            $query->where('applies_to', $request->input('applies_to'));
        }

        $reasons = $query->orderBy('sort_order')->orderBy('id')->paginate(20)->withQueryString();

        return view('admin.cancel-reasons.index', compact('reasons'));
    }

    public function create()
    {
        return view('admin.cancel-reasons.form');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        CancelReason::create($validated);

        return redirect()->route('admin.cancel-reasons.index')
            ->with('success', 'Cancel reason created successfully.');
    }

    public function edit($id)
    {
        $reason = CancelReason::findOrFail($id);

        return view('admin.cancel-reasons.form', compact('reason'));
    }

    public function update(Request $request, $id)
    {
        $reason = CancelReason::findOrFail($id);

        $validated = $this->validateData($request);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $reason->update($validated);

        return redirect()->route('admin.cancel-reasons.index')
            ->with('success', 'Cancel reason updated successfully.');
    }

    public function destroy($id)
    {
        CancelReason::findOrFail($id)->delete();

        return redirect()->route('admin.cancel-reasons.index')
            ->with('success', 'Cancel reason deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $reason = CancelReason::findOrFail($id);
        $reason->is_active = ! $reason->is_active;
        $reason->save();

        return redirect()->back()->with('success', 'Cancel reason status updated successfully.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'reason'     => 'required|string|max:255',
            'applies_to' => 'required|in:customer,driver,both',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);
    }
}
