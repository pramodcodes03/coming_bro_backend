<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\RechargePlan;
use App\Models\WalletTransaction;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    public function createTransaction(Request $request, RazorpayService $razorpay): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'nullable|string',
            'note' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'order_type' => 'nullable|string',
            'user_type' => 'nullable|string',
            'recharge_plan_id' => 'nullable|integer|exists:recharge_plans,id',
            'gst_percent' => 'nullable|numeric|min:0|max:100',
            'razorpay_order_id' => 'nullable|string',
            'razorpay_payment_id' => 'nullable|string',
            'razorpay_signature' => 'nullable|string',
        ]);

        $driver = $request->user();

        Log::info('[RECHARGE] driver wallet/transactions called', [
            'driver_id' => $driver->id,
            'amount' => $request->amount,
            'order_type' => $request->order_type,
            'payment_type' => $request->payment_type,
            'recharge_plan_id' => $request->recharge_plan_id,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'has_signature' => $request->filled('razorpay_signature'),
        ]);

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

        // A recharge / online payment must be a *verified* Razorpay payment
        // before we record it or credit any ride quota — otherwise rides could
        // be unlocked for free.
        $gatewayTypes = ['razorpay', 'online', 'card', 'upi', 'netbanking'];
        $isGateway = $request->order_type === 'wallet_recharge'
            || in_array(strtolower((string) $request->payment_type), $gatewayTypes, true)
            || $request->filled('razorpay_payment_id');

        Log::info('[RECHARGE] gateway detection', [
            'driver_id' => $driver->id,
            'is_gateway' => $isGateway,
            'resolved_plan_id' => $rechargePlanId,
        ]);

        if ($isGateway) {
            $gw = $request->validate([
                'razorpay_order_id' => 'required|string',
                'razorpay_payment_id' => 'required|string',
                'razorpay_signature' => 'required|string',
            ]);

            // Idempotency: never re-record or re-credit the same captured payment.
            $existing = WalletTransaction::where('razorpay_payment_id', $gw['razorpay_payment_id'])->first();
            if ($existing) {
                Log::warning('[RECHARGE] duplicate payment — already recorded, skipping', [
                    'driver_id' => $driver->id,
                    'razorpay_payment_id' => $gw['razorpay_payment_id'],
                    'existing_tx_id' => $existing->id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Transaction already recorded for this payment.',
                    'data' => $existing,
                    'remaining_rides' => (int) $driver->remaining_rides,
                    'total_rides' => (int) $driver->total_rides,
                ]);
            }

            $result = $razorpay->verifyPayment(
                $gw['razorpay_order_id'],
                $gw['razorpay_payment_id'],
                $gw['razorpay_signature'],
            );

            if (! $result['ok']) {
                Log::warning('[RECHARGE] payment verification FAILED', [
                    'driver_id' => $driver->id,
                    'razorpay_order_id' => $gw['razorpay_order_id'],
                    'razorpay_payment_id' => $gw['razorpay_payment_id'],
                    'error' => $result['error'] ?? 'unknown',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Payment verification failed.',
                ], 422);
            }

            Log::info('[RECHARGE] payment verified by Razorpay', [
                'driver_id' => $driver->id,
                'razorpay_payment_id' => $gw['razorpay_payment_id'],
                'captured_amount_paise' => $result['payment']['amount'] ?? null,
                'status' => $result['payment']['status'] ?? null,
            ]);

            // The captured amount must cover the plan price (or claimed amount).
            $expected = $rechargePlanId
                ? (float) RechargePlan::whereKey($rechargePlanId)->value('price')
                : (float) $request->amount;
            if ((int) ($result['payment']['amount'] ?? 0) < (int) round($expected * 100)) {
                Log::warning('[RECHARGE] paid amount less than price — rejected', [
                    'driver_id' => $driver->id,
                    'captured_amount_paise' => $result['payment']['amount'] ?? 0,
                    'expected_paise' => (int) round($expected * 100),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Paid amount is less than the recharge price.',
                ], 422);
            }
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
            'transaction_id' => $request->razorpay_payment_id ?: $request->transaction_id,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'order_type' => $request->order_type,
            'user_type' => $request->user_type ?? 'driver',
            'created_date' => now(),
        ]);

        Log::info('[RECHARGE] wallet transaction recorded', [
            'driver_id' => $driver->id,
            'tx_id' => $transaction->id,
            'order_type' => $transaction->order_type,
            'amount' => $transaction->amount,
            'razorpay_payment_id' => $transaction->razorpay_payment_id,
        ]);

        // Credit the plan's ride quota to the driver. Recharges stack: buying a
        // 10-ride plan twice leaves the driver at 20 total (e.g. 5/20 remaining).
        // Unlimited / validity-only plans carry rides = 0 and are skipped here.
        if ($rechargePlanId) {
            $rides = (int) RechargePlan::whereKey($rechargePlanId)->value('rides');
            if ($rides > 0) {
                // NULL-safe credit: a brand-new driver's quota columns may be NULL,
                // and `NULL + n` is NULL in SQL — so coalesce to 0 before adding.
                $driver->remaining_rides = (int) $driver->remaining_rides + $rides;
                $driver->total_rides = (int) $driver->total_rides + $rides;
                $driver->save();

                Log::info('[RECHARGE] ride quota credited', [
                    'driver_id' => $driver->id,
                    'plan_id' => $rechargePlanId,
                    'rides_added' => $rides,
                    'remaining_rides' => (int) $driver->remaining_rides,
                    'total_rides' => (int) $driver->total_rides,
                ]);
            } else {
                Log::info('[RECHARGE] plan grants no ride count (validity/unlimited plan)', [
                    'driver_id' => $driver->id,
                    'plan_id' => $rechargePlanId,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully.',
            'data' => $transaction,
            'remaining_rides' => (int) $driver->remaining_rides,
            'total_rides' => (int) $driver->total_rides,
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
     * Create a Razorpay order for a recharge / wallet top-up. Returns the order
     * plus the public key id so the app never needs the secret.
     */
    public function createRazorpayOrder(Request $request, RazorpayService $razorpay): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'purpose' => 'nullable|string',
            'recharge_plan_id' => 'nullable|integer|exists:recharge_plans,id',
        ]);

        if (! $razorpay->isConfigured()) {
            Log::error('[RECHARGE] create-order aborted — Razorpay not configured');

            return response()->json([
                'success' => false,
                'message' => 'Razorpay is not configured.',
            ], 500);
        }

        $driver = $request->user();

        Log::info('[RECHARGE] driver create-order requested', [
            'driver_id' => $driver->id,
            'amount' => $request->amount,
            'purpose' => $request->input('purpose', 'wallet'),
            'recharge_plan_id' => $request->input('recharge_plan_id'),
        ]);

        $result = $razorpay->createOrder(
            (int) round($request->amount * 100),
            'recharge_'.$driver->id.'_'.time(),
            [
                'user_id' => (string) $driver->id,
                'user_type' => 'driver',
                'purpose' => (string) $request->input('purpose', 'wallet'),
                'recharge_plan_id' => (string) $request->input('recharge_plan_id', ''),
            ],
        );

        if (! $result['ok']) {
            Log::error('[RECHARGE] driver create-order FAILED at Razorpay', [
                'driver_id' => $driver->id,
                'amount' => $request->amount,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create Razorpay order.',
            ], 500);
        }

        Log::info('[RECHARGE] driver Razorpay order created', [
            'driver_id' => $driver->id,
            'razorpay_order_id' => $result['order']['id'] ?? null,
            'amount_paise' => $result['order']['amount'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Razorpay order created successfully.',
            'data' => array_merge($result['order'], ['key_id' => $razorpay->key()]),
        ]);
    }
}
