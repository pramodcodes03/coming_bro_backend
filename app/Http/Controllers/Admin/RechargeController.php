<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PaymentsExport;
use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RechargeController extends Controller
{
    /**
     * Recharge register with the per-recharge GST breakdown.
     */
    public function index(Request $request)
    {
        $query = $this->buildQuery($request);

        // Summary totals over the whole filtered set (not just the page).
        // Replace the base select() with aggregates only — appending them to the
        // `wallet_transactions.*` columns would mix aggregate/non-aggregate
        // columns without a GROUP BY (MySQL error 1140).
        $summary = (clone $query)
            ->reorder()
            ->select([
                DB::raw('COUNT(*) as cnt'),
                DB::raw('COALESCE(SUM(COALESCE(total_amount, amount)), 0) as total_collected'),
                DB::raw('COALESCE(SUM(gst_amount), 0) as total_gst'),
                DB::raw('COALESCE(SUM(COALESCE(base_amount, amount)), 0) as total_base'),
            ])
            ->first();

        $recharges = $query->paginate(15)->withQueryString();

        return view('admin.recharges.index', compact('recharges', 'summary'));
    }

    /**
     * Download the filtered recharge register as an Excel (.xlsx) file.
     */
    public function export(Request $request)
    {
        $filename = 'recharges_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new PaymentsExport($this->buildQuery($request)), $filename);
    }

    /**
     * Build the filtered recharge query shared by the list and the export.
     * Recharges are wallet transactions tagged `wallet_recharge` or linked to a
     * recharge plan. Names/mobiles are resolved from whichever owning table the
     * `user_type` points at.
     */
    private function buildQuery(Request $request)
    {
        $query = WalletTransaction::query()
            ->leftJoin('driver_users', function ($join) {
                $join->on('wallet_transactions.user_id', '=', 'driver_users.id')
                    ->where('wallet_transactions.user_type', '=', 'driver');
            })
            ->leftJoin('customers', function ($join) {
                $join->on('wallet_transactions.user_id', '=', 'customers.id')
                    ->where('wallet_transactions.user_type', '=', 'customer');
            })
            ->leftJoin('recharge_plans', 'wallet_transactions.recharge_plan_id', '=', 'recharge_plans.id')
            ->where(function ($q) {
                $q->where('wallet_transactions.order_type', 'wallet_recharge')
                    ->orWhereNotNull('wallet_transactions.recharge_plan_id');
            })
            ->select(
                'wallet_transactions.*',
                DB::raw('COALESCE(driver_users.full_name, customers.full_name) as user_name'),
                DB::raw('COALESCE(driver_users.phone_number, customers.phone_number) as user_mobile'),
                'recharge_plans.label as plan_label'
            );

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('driver_users.full_name', 'like', "%{$search}%")
                    ->orWhere('customers.full_name', 'like', "%{$search}%")
                    ->orWhere('driver_users.phone_number', 'like', "%{$search}%")
                    ->orWhere('customers.phone_number', 'like', "%{$search}%")
                    ->orWhere('wallet_transactions.transaction_id', 'like', "%{$search}%");
            });
        }

        if (($type = $request->input('user_type')) && in_array($type, ['driver', 'customer'], true)) {
            $query->where('wallet_transactions.user_type', $type);
        }

        if ($paymentType = trim((string) $request->input('payment_type'))) {
            $query->where('wallet_transactions.payment_type', $paymentType);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('wallet_transactions.created_date', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('wallet_transactions.created_date', '<=', $to);
        }

        return $query->orderBy('wallet_transactions.created_date', 'desc');
    }
}
