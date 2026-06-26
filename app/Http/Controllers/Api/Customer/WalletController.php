<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function createTransaction(Request $request, RazorpayService $razorpay): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'nullable|string',
            'note' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'order_type' => 'nullable|string',
            'razorpay_order_id' => 'nullable|string',
            'razorpay_payment_id' => 'nullable|string',
            'razorpay_signature' => 'nullable|string',
        ]);

        $customer = $request->user();

        // Gateway (online) top-ups must be verified before they are credited.
        $gatewayTypes = ['razorpay', 'online', 'card', 'upi', 'netbanking'];
        $isGateway = in_array(strtolower((string) $request->payment_type), $gatewayTypes, true)
            || $request->filled('razorpay_payment_id');

        $creditAmount = (float) $request->amount;

        if ($isGateway) {
            $gw = $request->validate([
                'razorpay_order_id' => 'required|string',
                'razorpay_payment_id' => 'required|string',
                'razorpay_signature' => 'required|string',
            ]);

            // Idempotency: never credit the same captured payment twice.
            $existing = WalletTransaction::where('razorpay_payment_id', $gw['razorpay_payment_id'])->first();
            if ($existing) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction already recorded for this payment.',
                    'data' => $existing,
                    'wallet_amount' => $customer->wallet_amount,
                ]);
            }

            $result = $razorpay->verifyPayment(
                $gw['razorpay_order_id'],
                $gw['razorpay_payment_id'],
                $gw['razorpay_signature'],
            );
            if (! $result['ok']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Payment verification failed.',
                ], 422);
            }

            // Credit the amount Razorpay actually captured, not the client's claim.
            $creditAmount = (int) ($result['payment']['amount'] ?? 0) / 100;
        }

        $transaction = WalletTransaction::create([
            'user_id' => $customer->id,
            'amount' => $creditAmount,
            'total_amount' => $creditAmount,
            'payment_type' => $request->payment_type,
            'note' => $request->note,
            'transaction_id' => $request->razorpay_payment_id ?: $request->transaction_id,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'order_type' => $request->order_type,
            'user_type' => 'customer',
            'created_date' => now(),
        ]);

        // For verified online top-ups, credit the wallet here so there is a
        // single, verified money-in action (no blind updateWallet needed).
        if ($isGateway) {
            $customer->wallet_amount = (float) $customer->wallet_amount + $creditAmount;
            $customer->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully.',
            'data' => $transaction,
            'wallet_amount' => $customer->wallet_amount,
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

    public function createRazorpayOrder(Request $request, RazorpayService $razorpay): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'purpose' => 'nullable|string',
        ]);

        if (! $razorpay->isConfigured()) {
            return response()->json(['success' => false, 'message' => 'Razorpay is not configured.'], 500);
        }

        $customer = $request->user();

        $result = $razorpay->createOrder(
            (int) round($request->amount * 100),
            'wallet_'.$customer->id.'_'.time(),
            [
                'user_id' => (string) $customer->id,
                'user_type' => 'customer',
                'purpose' => (string) $request->input('purpose', 'wallet'),
            ],
        );

        if (! $result['ok']) {
            return response()->json(['success' => false, 'message' => 'Failed to create Razorpay order.'], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Razorpay order created.',
            'data' => array_merge($result['order'], ['key_id' => $razorpay->key()]),
        ]);
    }
}
