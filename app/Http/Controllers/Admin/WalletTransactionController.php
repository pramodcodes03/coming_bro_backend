<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PaymentsExport;
use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WalletTransactionController extends Controller
{
    public function driverTransactions(Request $request)
    {
        $transactions = $this->buildQuery($request, 'driver', 'driver_users')
            ->paginate(15)
            ->withQueryString();

        return view('admin.wallet.driver', compact('transactions'));
    }

    public function exportDriver(Request $request)
    {
        $filename = 'driver_payments_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new PaymentsExport($this->buildQuery($request, 'driver', 'driver_users')), $filename);
    }

    public function userTransactions(Request $request)
    {
        $transactions = $this->buildQuery($request, 'customer', 'customers')
            ->paginate(15)
            ->withQueryString();

        return view('admin.wallet.user', compact('transactions'));
    }

    public function exportUser(Request $request)
    {
        $filename = 'user_payments_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new PaymentsExport($this->buildQuery($request, 'customer', 'customers')), $filename);
    }

    /**
     * Build the filtered wallet-transaction query shared by the list and the
     * export. Selects the `user_name` / `user_mobile` / `plan_label` aliases the
     * PaymentsExport and views rely on.
     */
    private function buildQuery(Request $request, string $userType, string $ownerTable)
    {
        $query = WalletTransaction::query()
            ->where('wallet_transactions.user_type', $userType)
            ->leftJoin($ownerTable, 'wallet_transactions.user_id', '=', "{$ownerTable}.id")
            ->leftJoin('recharge_plans', 'wallet_transactions.recharge_plan_id', '=', 'recharge_plans.id')
            ->select(
                'wallet_transactions.*',
                "{$ownerTable}.full_name as user_name",
                "{$ownerTable}.phone_number as user_mobile",
                'recharge_plans.label as plan_label'
            );

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($ownerTable, $search) {
                $q->where("{$ownerTable}.full_name", 'like', "%{$search}%")
                    ->orWhere("{$ownerTable}.phone_number", 'like', "%{$search}%")
                    ->orWhere('wallet_transactions.transaction_id', 'like', "%{$search}%");
            });
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('wallet_transactions.created_at', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('wallet_transactions.created_at', '<=', $to);
        }

        return $query->orderBy('wallet_transactions.created_at', 'desc');
    }
}
