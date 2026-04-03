<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;

class PayoutRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = WithdrawalHistory::query();

        $query->leftJoin('driver_users', 'withdrawal_history.user_id', '=', 'driver_users.id')
            ->select('withdrawal_history.*', 'driver_users.full_name as driver_name');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('driver_users.full_name', 'like', "%{$search}%");
        }

        $requests = $query->orderBy('withdrawal_history.created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.payout-requests.index', compact('requests'));
    }

    public function updateStatus(Request $request, $id)
    {
        $payout = WithdrawalHistory::findOrFail($id);

        $validated = $request->validate([
            'payment_status' => 'required|string|in:pending,approved,rejected,completed',
            'admin_note'     => 'nullable|string|max:1000',
        ]);

        $payout->update($validated);

        return redirect()->route('admin.payout-requests.index')
            ->with('success', 'Payout request status updated successfully.');
    }
}
