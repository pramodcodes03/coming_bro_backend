<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    /**
     * Get driver's wallet transactions.
     */
    public function transactions(Request $request): JsonResponse
    {
        $driver = $request->user();

        $transactions = WalletTransaction::where('user_id', $driver->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Wallet transactions retrieved successfully.',
            'data' => $transactions,
        ]);
    }

    /**
     * Create wallet transaction.
     */
    public function createTransaction(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric',
            'transaction_type' => 'required|string',
            'note' => 'nullable|string',
            'order_id' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ]);

        $driver = $request->user();

        $transaction = WalletTransaction::create([
            'id' => Str::uuid()->toString(),
            'user_id' => $driver->id,
            'amount' => $request->amount,
            'transaction_type' => $request->transaction_type,
            'note' => $request->note,
            'order_id' => $request->order_id,
            'payment_method' => $request->payment_method,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully.',
            'data' => $transaction,
        ]);
    }

    /**
     * Update driver wallet amount.
     */
    public function updateWallet(Request $request): JsonResponse
    {
        $request->validate([
            'wallet_amount' => 'required|numeric',
        ]);

        $driver = $request->user();
        $driver->wallet_amount = $request->wallet_amount;
        $driver->save();

        return response()->json([
            'success' => true,
            'message' => 'Wallet amount updated successfully.',
            'data' => [
                'wallet_amount' => $driver->wallet_amount,
            ],
        ]);
    }
}
