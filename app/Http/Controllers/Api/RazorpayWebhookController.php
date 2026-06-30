<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DriverUser;
use App\Models\RechargePlan;
use App\Models\WalletTransaction;
use App\Services\RazorpayService;
use App\Services\RideWalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Server-to-server backstop for Razorpay. If a driver/customer pays but the app
 * never reaches the synchronous verify endpoint (network drop, app killed), the
 * `payment.captured` webhook still fulfils the recharge — idempotently, so it
 * never double-credits when the sync flow already ran.
 *
 * Configure in Razorpay Dashboard → Webhooks: URL `/api/razorpay/webhook`,
 * events `payment.captured` + `order.paid`, secret saved to
 * settings → payment → razorpay → razorpayWebhookSecret.
 */
class RazorpayWebhookController extends Controller
{
    public function __construct(
        private readonly RazorpayService $razorpay,
        private readonly RideWalletService $rideWallet,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $raw = $request->getContent();
        $signature = (string) $request->header('X-Razorpay-Signature', '');

        if (! $this->razorpay->verifyWebhookSignature($raw, $signature)) {
            Log::warning('[RECHARGE] webhook rejected — invalid/absent signature');

            return response()->json(['success' => false, 'message' => 'Invalid signature.'], 400);
        }

        $event = $request->input('event');
        $payment = data_get($request->all(), 'payload.payment.entity');

        Log::info('[RECHARGE] webhook received', [
            'event' => $event,
            'razorpay_payment_id' => $payment['id'] ?? null,
            'razorpay_order_id' => $payment['order_id'] ?? null,
            'amount_paise' => $payment['amount'] ?? null,
        ]);

        if (in_array($event, ['payment.captured', 'order.paid'], true) && is_array($payment)) {
            try {
                $this->fulfil($payment);
            } catch (\Throwable $e) {
                // Swallow (e.g. a race with the sync flow hitting the unique index)
                // and still 200 so Razorpay doesn't retry an already-handled event.
                Log::warning('Razorpay webhook fulfilment skipped', ['error' => $e->getMessage()]);
            }
        }

        return response()->json(['success' => true]);
    }

    private function fulfil(array $payment): void
    {
        $paymentId = $payment['id'] ?? null;
        $orderId = $payment['order_id'] ?? null;
        $amountPaise = (int) ($payment['amount'] ?? 0);

        if (! $paymentId || ! $orderId) {
            return;
        }

        // Already handled by the synchronous verify flow (the normal case).
        if (WalletTransaction::where('razorpay_payment_id', $paymentId)->exists()) {
            Log::info('[RECHARGE] webhook backstop skipped — already fulfilled by app', [
                'razorpay_payment_id' => $paymentId,
            ]);

            return;
        }

        Log::warning('[RECHARGE] webhook backstop fulfilling (app did not confirm)', [
            'razorpay_payment_id' => $paymentId,
            'razorpay_order_id' => $orderId,
        ]);

        // Read what the payment was for from the order notes we set at creation.
        $order = $this->razorpay->fetchOrder($orderId);
        $notes = $order['notes'] ?? ($payment['notes'] ?? []);
        $purpose = $notes['purpose'] ?? 'wallet';
        $userType = $notes['user_type'] ?? null;
        $userId = (int) ($notes['user_id'] ?? 0);
        $planId = ((int) ($notes['recharge_plan_id'] ?? 0)) ?: null;

        if (! $userId) {
            return;
        }

        // One shared ride wallet: any driver recharge credits ride balance,
        // whether bought from the normal recharge screen or the return-ride one.
        if ($userType === 'driver') {
            $driver = DriverUser::find($userId);
            if (! $driver) {
                return;
            }
            $plan = $planId
                ? RechargePlan::find($planId)
                : (RechargePlan::where('is_active', true)->where('price', $amountPaise / 100)->orderBy('sort_order')->first()
                    ?? ($purpose === 'return_ride_recharge' ? RechargePlan::where('label', 'like', 'Return Ride%')->first() : null));
            $price = $plan ? (float) $plan->price : $amountPaise / 100;
            $transaction = $this->record('driver', $userId, $price, $plan?->id, $orderId, $paymentId, 'wallet_recharge', (float) ($plan?->gst_percent ?? 0));

            // Credit as a ride lot (FIFO + the plan's own expiry). The service
            // keeps remaining_rides / total_rides in sync from the lot ledger.
            $this->rideWallet->creditFromPlan(
                driver: $driver,
                plan: $plan,
                source: $purpose === 'return_ride_recharge' ? 'return_recharge' : 'recharge',
                walletTransactionId: $transaction->id,
            );

            return;
        }

        if ($userType === 'customer') {
            $customer = Customer::find($userId);
            if (! $customer) {
                return;
            }
            $amount = $amountPaise / 100;
            $this->record('customer', $userId, $amount, null, $orderId, $paymentId, 'wallet_recharge', 0);
            $customer->wallet_amount = (float) $customer->wallet_amount + $amount;
            $customer->save();
        }
    }

    private function record(string $userType, int $userId, float $amount, ?int $planId, string $orderId, string $paymentId, string $orderType, float $gstPercent): WalletTransaction
    {
        return WalletTransaction::create([
            'user_id' => $userId,
            'user_type' => $userType,
            'amount' => $amount,
            'base_amount' => $amount,
            'gst_percent' => $gstPercent,
            'total_amount' => $amount,
            'recharge_plan_id' => $planId,
            'payment_type' => 'razorpay',
            'transaction_id' => $paymentId,
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => $paymentId,
            'order_type' => $orderType,
            'note' => 'Razorpay webhook fulfilment',
            'created_date' => now(),
        ]);
    }
}
