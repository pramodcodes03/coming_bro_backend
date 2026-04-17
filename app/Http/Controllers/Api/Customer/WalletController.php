<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    public function transactions(Request $request): JsonResponse
    {
        $transactions = WalletTransaction::where('user_id', $request->user()->id)
            ->where('user_type', 'customer')
            ->orderBy('created_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Wallet transactions retrieved successfully.',
            'data' => $transactions,
        ]);
    }

    public function createTransaction(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required',
            'payment_type' => 'nullable|string',
            'note' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'order_type' => 'nullable|string',
        ]);

        $transaction = WalletTransaction::create([
            'user_id' => $request->user()->id,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'note' => $request->note,
            'transaction_id' => $request->transaction_id,
            'order_type' => $request->order_type,
            'user_type' => 'customer',
            'created_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully.',
            'data' => $transaction,
        ]);
    }

    public function updateWallet(Request $request): JsonResponse
    {
        $request->validate(['amount' => 'required|numeric']);

        $customer = $request->user();
        $customer->wallet_amount = (float) $customer->wallet_amount + (float) $request->amount;
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Wallet updated successfully.',
            'data' => ['wallet_amount' => $customer->wallet_amount],
        ]);
    }

    public function createRazorpayOrder(Request $request): JsonResponse
    {
        $request->validate(['amount' => 'required|numeric|min:1']);

        $setting = Setting::where('key', 'payment')->first();
        if (!$setting) {
            return response()->json(['success' => false, 'message' => 'Payment settings not configured.'], 500);
        }

        $razorpay = $setting->value['razorpay'] ?? null;
        if (!$razorpay || empty($razorpay['razorpayKey']) || empty($razorpay['razorpaySecret'])) {
            return response()->json(['success' => false, 'message' => 'Razorpay not configured.'], 500);
        }

        try {
            $response = Http::withBasicAuth($razorpay['razorpayKey'], $razorpay['razorpaySecret'])
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => (int) ($request->amount * 100),
                    'currency' => 'INR',
                    'receipt' => 'wallet_' . time(),
                ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Razorpay order created.', 'data' => $response->json()]);
            }

            return response()->json(['success' => false, 'message' => 'Failed to create Razorpay order.'], 500);
        } catch (\Exception $e) {
            Log::error('Razorpay error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Payment service unavailable.'], 500);
        }
    }
}
