<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin Razorpay helper that keeps the secret on the server and turns a raw
 * checkout result (order_id + payment_id + signature) into an authoritative,
 * verified, captured payment. Used by every gateway-backed recharge / top-up.
 */
class RazorpayService
{
    private const BASE = 'https://api.razorpay.com/v1';

    /** Razorpay sub-config from settings → payment → razorpay. */
    public function config(): array
    {
        $payment = optional(Setting::find('payment'))->value ?? [];

        return $payment['razorpay'] ?? [];
    }

    public function isConfigured(): bool
    {
        $c = $this->config();

        return ! empty($c['razorpayKey']) && ! empty($c['razorpaySecret']);
    }

    /** Public key id — safe to hand to the app. */
    public function key(): ?string
    {
        return $this->config()['razorpayKey'] ?? null;
    }

    private function secret(): ?string
    {
        return $this->config()['razorpaySecret'] ?? null;
    }

    /**
     * Create a Razorpay order (auto-capture). Returns ['ok'=>bool, 'order'=>array].
     */
    public function createOrder(int $amountPaise, string $receipt, array $notes = []): array
    {
        try {
            $response = Http::withBasicAuth($this->key(), $this->secret())
                ->post(self::BASE.'/orders', [
                    'amount' => $amountPaise,
                    'currency' => 'INR',
                    'receipt' => $receipt,
                    'payment_capture' => 1,
                    'notes' => $notes,
                ]);

            if ($response->successful()) {
                return ['ok' => true, 'order' => $response->json()];
            }

            Log::error('Razorpay order creation failed', ['response' => $response->body()]);
        } catch (\Throwable $e) {
            Log::error('Razorpay order creation exception', ['error' => $e->getMessage()]);
        }

        return ['ok' => false, 'order' => null];
    }

    /** HMAC-SHA256 signature check: hash(order_id|payment_id, secret) === signature. */
    public function verifySignature(string $orderId, string $paymentId, string $signature): bool
    {
        $secret = $this->secret();

        if (! $secret || $signature === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $orderId.'|'.$paymentId, $secret);

        return hash_equals($expected, $signature);
    }

    public function fetchOrder(string $orderId): ?array
    {
        try {
            $response = Http::withBasicAuth($this->key(), $this->secret())
                ->get(self::BASE.'/orders/'.$orderId);

            return $response->successful() ? $response->json() : null;
        } catch (\Throwable $e) {
            Log::error('Razorpay fetch order exception', ['error' => $e->getMessage()]);

            return null;
        }
    }

    public function fetchPayment(string $paymentId): ?array
    {
        try {
            $response = Http::withBasicAuth($this->key(), $this->secret())
                ->get(self::BASE.'/payments/'.$paymentId);

            return $response->successful() ? $response->json() : null;
        } catch (\Throwable $e) {
            Log::error('Razorpay fetch payment exception', ['error' => $e->getMessage()]);

            return null;
        }
    }

    public function capture(string $paymentId, int $amountPaise): ?array
    {
        try {
            $response = Http::withBasicAuth($this->key(), $this->secret())
                ->post(self::BASE.'/payments/'.$paymentId.'/capture', [
                    'amount' => $amountPaise,
                    'currency' => 'INR',
                ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Throwable $e) {
            Log::error('Razorpay capture exception', ['error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Full verification of a checkout result. Confirms the signature, that the
     * payment belongs to the given order, captures it if only authorised, and
     * returns the authoritative captured payment (with the real paid amount).
     *
     * @return array{ok:bool, payment:?array, error:?string}
     */
    public function verifyPayment(string $orderId, string $paymentId, string $signature): array
    {
        if (! $this->isConfigured()) {
            return ['ok' => false, 'payment' => null, 'error' => 'Razorpay is not configured.'];
        }

        if (! $this->verifySignature($orderId, $paymentId, $signature)) {
            return ['ok' => false, 'payment' => null, 'error' => 'Payment signature verification failed.'];
        }

        $payment = $this->fetchPayment($paymentId);

        if (! $payment) {
            return ['ok' => false, 'payment' => null, 'error' => 'Unable to verify the payment with Razorpay.'];
        }

        if (($payment['order_id'] ?? null) !== $orderId) {
            return ['ok' => false, 'payment' => null, 'error' => 'Payment does not belong to this order.'];
        }

        $status = $payment['status'] ?? '';

        // Money is held but not yet captured — capture it now.
        if ($status === 'authorized') {
            $captured = $this->capture($paymentId, (int) ($payment['amount'] ?? 0));
            if ($captured) {
                $payment = $captured;
                $status = $payment['status'] ?? $status;
            }
        }

        if ($status !== 'captured') {
            return ['ok' => false, 'payment' => null, 'error' => 'Payment was not captured (status: '.$status.').'];
        }

        return ['ok' => true, 'payment' => $payment, 'error' => null];
    }

    /** Verify a webhook body against the configured webhook secret. */
    public function verifyWebhookSignature(string $rawBody, string $signature): bool
    {
        $webhookSecret = $this->config()['razorpayWebhookSecret'] ?? null;

        if (! $webhookSecret || $signature === '') {
            return false;
        }

        return hash_equals(hash_hmac('sha256', $rawBody, $webhookSecret), $signature);
    }

    /**
     * The whole payment settings tree with every secret stripped — safe to
     * return to the mobile apps (they only need public keys + display fields).
     */
    public static function publicPaymentConfig(): array
    {
        $cfg = optional(Setting::find('payment'))->value ?? [];

        return self::stripSecrets($cfg);
    }

    private static function stripSecrets(array $arr): array
    {
        $out = [];

        foreach ($arr as $key => $value) {
            if (is_string($key) && preg_match('/secret|private|webhook|password/i', $key)) {
                continue; // never expose secret/private/webhook fields
            }

            $out[$key] = is_array($value) ? self::stripSecrets($value) : $value;
        }

        return $out;
    }
}
