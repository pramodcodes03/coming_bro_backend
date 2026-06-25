<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\RechargePlan;
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
            'recharge_plan_id' => 'nullable|integer|exists:recharge_plans,id',
            'gst_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $driver = $request->user();

        // Resolve which recharge plan this payment is for. Prefer the explicit
        // id; otherwise, for a recharge, match an active plan by its price so
        // GST is still captured for the existing fixed-plan flow.
        $rechargePlanId = $request->recharge_plan_id;
        if (! $rechargePlanId && $request->order_type === 'wallet_recharge') {
            $rechargePlanId = RechargePlan::where('is_active', true)
                ->where('price', (float) $request->amount)
                ->orderBy('sort_order')
                ->value('id');
        }

        // Resolve the GST breakdown for this payment. The amount paid is treated
        // as GST-inclusive (the driver pays the displayed price); we record how
        // much of it is base value vs GST so collected GST can be reported.
        $gst = $this->resolveGstBreakdown(
            (float) $request->amount,
            $rechargePlanId,
            $request->filled('gst_percent') ? (float) $request->gst_percent : null
        );

        $transaction = WalletTransaction::create([
            'user_id' => $driver->id,
            'amount' => $request->amount,
            'recharge_plan_id' => $rechargePlanId,
            'base_amount' => $gst['base_amount'],
            'gst_percent' => $gst['gst_percent'],
            'gst_amount' => $gst['gst_amount'],
            'total_amount' => $gst['total_amount'],
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
     * Resolve a GST-inclusive breakdown for a paid amount.
     *
     * The GST rate is taken from the explicit request value, otherwise from the
     * recharge plan being purchased, otherwise 0. Returns base/gst/total all
     * rounded to 2 decimals with total == the amount paid.
     */
    private function resolveGstBreakdown(float $total, $rechargePlanId = null, ?float $gstPercent = null): array
    {
        if ($gstPercent === null && $rechargePlanId) {
            $plan = RechargePlan::find($rechargePlanId);
            $gstPercent = $plan ? (float) $plan->gst_percent : null;
        }

        $gstPercent = $gstPercent ?? 0.0;

        if ($gstPercent > 0) {
            $base = round($total / (1 + $gstPercent / 100), 2);
            $gstAmount = round($total - $base, 2);
        } else {
            $base = round($total, 2);
            $gstAmount = 0.0;
        }

        return [
            'base_amount' => $base,
            'gst_percent' => $gstPercent,
            'gst_amount' => $gstAmount,
            'total_amount' => round($total, 2),
        ];
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
