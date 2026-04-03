<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class WalletController extends Controller
{
    /**
     * Get driver's wallet transactions.
     */
    public function transactions(Request $request): JsonResponse
    {
        $driver = $request->user();

        $transactions = WalletTransaction::where('user_id', $driver->id)
            ->orderBy('created_date', 'desc')
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
            'amount' => 'required',
            'payment_type' => 'nullable|string',
            'note' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'order_type' => 'nullable|string',
            'user_type' => 'nullable|string',
        ]);

        $driver = $request->user();

        $transaction = WalletTransaction::create([
            'user_id' => $driver->id,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'note' => $request->note,
            'transaction_id' => $request->transaction_id,
            'order_type' => $request->order_type,
            'user_type' => $request->user_type ?? 'driver',
            'created_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully.',
            'data' => $transaction,
        ]);
    }

    /**
     * Update driver wallet amount (increment by given amount).
     */
    public function updateWallet(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric',
        ]);

        $driver = $request->user();
        $driver->wallet_amount = (double) $driver->wallet_amount + (double) $request->amount;
        $driver->save();

        return response()->json([
            'success' => true,
            'message' => 'Wallet amount updated successfully.',
            'data' => [
                'wallet_amount' => $driver->wallet_amount,
            ],
        ]);
    }

    /**
     * Create a Razorpay order for wallet topup.
     */
    public function createRazorpayOrder(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        // Get Razorpay credentials from settings
        $setting = Setting::where('key', 'payment')->first();
        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Payment settings not configured.',
            ], 500);
        }

        $paymentConfig = $setting->value;
        $razorpay = $paymentConfig['razorpay'] ?? null;

        if (!$razorpay || empty($razorpay['razorpayKey']) || empty($razorpay['razorpaySecret'])) {
            return response()->json([
                'success' => false,
                'message' => 'Razorpay not configured.',
            ], 500);
        }

        $amountInPaise = (int) ($request->amount * 100);
        $receiptId = 'wallet_' . time();

        try {
            $response = Http::withBasicAuth($razorpay['razorpayKey'], $razorpay['razorpaySecret'])
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => $amountInPaise,
                    'currency' => 'INR',
                    'receipt' => $receiptId,
                ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Razorpay order created successfully.',
                    'data' => $response->json(),
                ]);
            }

            Log::error('Razorpay order creation failed', ['response' => $response->body()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Razorpay order.',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Razorpay order creation exception', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Payment service unavailable.',
            ], 500);
        }
    }
}
