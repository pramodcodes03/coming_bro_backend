<?php

namespace Tests\Feature;

use App\Models\DriverUser;
use App\Services\RazorpayService;

/**
 * Server-side Razorpay verification for ride recharges. No live gateway calls
 * and no real money — verifyPayment is faked for the happy path; the failure
 * paths exercise the real signature verification (which short-circuits before
 * any HTTP call).
 */
class RechargePaymentTest extends FlowTestCase
{
    public function test_verified_payment_activates_and_credits_quota(): void
    {
        $driver = $this->loginDriver();

        $this->rechargeDriver($driver['token'], $this->dailyPlanId, 49)
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('remaining_rides', 10);

        $this->assertSame(10, (int) DriverUser::find($driver['id'])->remaining_rides);
    }

    public function test_forged_signature_is_rejected_with_422(): void
    {
        $driver = $this->loginDriver();

        // No fake bound → the real RazorpayService runs and rejects the bad
        // signature before any network call.
        $this->withToken($driver['token'])->postJson('/api/driver/wallet/transactions', [
            'amount' => 49,
            'recharge_plan_id' => $this->dailyPlanId,
            'order_type' => 'wallet_recharge',
            'razorpay_order_id' => 'order_x',
            'razorpay_payment_id' => 'pay_x',
            'razorpay_signature' => 'totally-forged',
        ])->assertStatus(422);

        $this->assertSame(0, (int) DriverUser::find($driver['id'])->remaining_rides);
    }

    public function test_missing_signature_is_rejected_with_422(): void
    {
        $driver = $this->loginDriver();

        $this->withToken($driver['token'])->postJson('/api/driver/wallet/transactions', [
            'amount' => 49,
            'recharge_plan_id' => $this->dailyPlanId,
            'order_type' => 'wallet_recharge',
            'razorpay_order_id' => 'order_x',
            'razorpay_payment_id' => 'pay_x',
            // razorpay_signature omitted
        ])->assertStatus(422);
    }

    public function test_same_payment_cannot_grant_quota_twice(): void
    {
        $driver = $this->loginDriver();
        $this->bindFakeRazorpay(4900);

        $payload = [
            'amount' => 49,
            'recharge_plan_id' => $this->dailyPlanId,
            'order_type' => 'wallet_recharge',
            'razorpay_order_id' => 'order_dup',
            'razorpay_payment_id' => 'pay_dup',   // same id both times
            'razorpay_signature' => 'sig_dup',
        ];

        $this->withToken($driver['token'])->postJson('/api/driver/wallet/transactions', $payload)
            ->assertStatus(200)->assertJsonPath('remaining_rides', 10);

        // Replaying the identical captured payment must not credit again.
        $this->withToken($driver['token'])->postJson('/api/driver/wallet/transactions', $payload)
            ->assertStatus(200);

        $this->assertSame(10, (int) DriverUser::find($driver['id'])->remaining_rides);
    }

    public function test_paid_amount_less_than_price_is_rejected_with_422(): void
    {
        $driver = $this->loginDriver();

        // Captured only ₹48 (4800 paise) for a ₹49 plan.
        $this->rechargeDriver($driver['token'], $this->dailyPlanId, 49, paidPaise: 4800)
            ->assertStatus(422);

        $this->assertSame(0, (int) DriverUser::find($driver['id'])->remaining_rides);
    }

    public function test_payment_settings_never_leak_the_secret(): void
    {
        $driver = $this->loginDriver();

        $res = $this->withToken($driver['token'])->getJson('/api/driver/payment-settings')
            ->assertStatus(200);

        $body = $res->getContent();
        $this->assertStringNotContainsString('razorpaySecret', $body);
        $this->assertStringNotContainsString('test_secret_123', $body);
    }

    public function test_real_signature_verification_accepts_valid_and_rejects_tampered(): void
    {
        /** @var RazorpayService $svc */
        $svc = app(RazorpayService::class);

        $orderId = 'order_abc';
        $paymentId = 'pay_abc';
        $valid = hash_hmac('sha256', $orderId.'|'.$paymentId, 'test_secret_123');

        $this->assertTrue($svc->verifySignature($orderId, $paymentId, $valid));
        $this->assertFalse($svc->verifySignature($orderId, $paymentId, $valid.'tampered'));
        $this->assertFalse($svc->verifySignature($orderId, $paymentId, ''));
    }
}
