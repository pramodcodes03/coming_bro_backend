<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Models\DriverUser;
use Illuminate\Http\Request;

class WalletTransactionController extends Controller
{
    public function driverTransactions(Request $request)
    {
        $query = WalletTransaction::query()
            ->where('user_type', 'driver');

        $query->leftJoin('driver_users', 'wallet_transactions.user_id', '=', 'driver_users.id')
            ->select('wallet_transactions.*', 'driver_users.full_name as user_name');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('driver_users.full_name', 'like', "%{$search}%");
        }

        $transactions = $query->orderBy('wallet_transactions.created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.wallet.driver', compact('transactions'));
    }

    public function userTransactions(Request $request)
    {
        $query = WalletTransaction::query()
            ->where('user_type', 'customer');

        $query->leftJoin('customers', 'wallet_transactions.user_id', '=', 'customers.id')
            ->select('wallet_transactions.*', 'customers.full_name as user_name');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('customers.full_name', 'like', "%{$search}%");
        }

        $transactions = $query->orderBy('wallet_transactions.created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.wallet.user', compact('transactions'));
    }
}
