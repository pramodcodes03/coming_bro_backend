<?php

namespace Tests\Feature;

use App\Models\RechargePlan;
use App\Models\Service;
use App\Models\Setting;
use App\Services\RazorpayService;
use Database\Seeders\RechargePlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Shared harness for the end-to-end ride flow tests.
 *
 * Runs on the configured test connection (sqlite :memory: per phpunit.xml,
 * portable to MySQL in CI) with RefreshDatabase. Seeds the master data the
 * flows need, registers the trig/min-max SQL functions the Haversine
 * discovery queries rely on (MySQL has them natively; sqlite does not), and
 * provides OTP login + verified-Razorpay recharge helpers.
 */
abstract class FlowTestCase extends TestCase
{
    use RefreshDatabase;

    protected int $serviceId;
    protected int $dailyPlanId;   // 10 rides @ ₹49
    protected int $returnPlanId;  // 10 rides @ ₹99

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerSqliteMathFunctions();

        $this->seed(RechargePlanSeeder::class);
        $this->dailyPlanId = (int) RechargePlan::where('label', 'Daily Ride (Regular)')->value('id');
        $this->returnPlanId = (int) RechargePlan::where('label', 'like', 'Return Ride%')->value('id');

        $this->serviceId = (int) Service::create([
            'title' => 'Cab', 'enable' => true, 'offer_rate' => '0',
        ])->id;

        // Razorpay config so the service is "configured" and verifySignature has
        // a secret. No live calls are ever made (verifyPayment is faked).
        Setting::updateOrCreate(['key' => 'payment'], ['value' => [
            'razorpay' => [
                'razorpayKey' => 'rzp_test_key',
                'razorpaySecret' => 'test_secret_123',
                'enable' => true,
            ],
        ]]);
    }

    /** MySQL has cos/sin/acos/radians/least/greatest; sqlite needs them registered. */
    private function registerSqliteMathFunctions(): void
    {
        $connection = DB::connection();
        if ($connection->getDriverName() !== 'sqlite') {
            return;
        }

        $pdo = $connection->getPdo();
        $pdo->sqliteCreateFunction('cos', 'cos', 1);
        $pdo->sqliteCreateFunction('sin', 'sin', 1);
        $pdo->sqliteCreateFunction('acos', 'acos', 1);
        $pdo->sqliteCreateFunction('radians', 'deg2rad', 1);
        $pdo->sqliteCreateFunction('least', fn (...$a) => min($a), -1);
        $pdo->sqliteCreateFunction('greatest', fn (...$a) => max($a), -1);
    }

    // ── Auth (master OTP 2526 bypasses the stored-OTP lookup) ───────────────
    protected function loginDriver(string $phone = '9300000001'): array
    {
        $res = $this->postJson('/api/driver/verify-otp', [
            'verification_id' => 'test-vid',
            'otp' => '2526',
            'phone_number' => $phone,
            'country_code' => '+91',
        ])->assertStatus(200);

        return ['token' => $res->json('data.token'), 'id' => $res->json('data.driver.id')];
    }

    protected function loginCustomer(string $phone = '9000000001'): array
    {
        $res = $this->postJson('/api/customer/verify-otp', [
            'verification_id' => 'test-vid',
            'otp' => '2526',
            'phone_number' => $phone,
            'country_code' => '+91',
        ])->assertStatus(200);

        return ['token' => $res->json('data.token'), 'id' => $res->json('data.customer.id')];
    }

    // ── Payments — bind a fake Razorpay that returns a verified, captured payment ─
    protected function bindFakeRazorpay(int $amountPaise, bool $ok = true): void
    {
        $fake = new class($amountPaise, $ok) extends RazorpayService
        {
            public function __construct(private int $amt, private bool $ok) {}

            public function isConfigured(): bool
            {
                return true;
            }

            public function verifyPayment(string $orderId, string $paymentId, string $signature): array
            {
                return $this->ok
                    ? ['ok' => true, 'payment' => ['amount' => $this->amt, 'status' => 'captured', 'order_id' => $orderId], 'error' => null]
                    : ['ok' => false, 'payment' => null, 'error' => 'Payment signature verification failed.'];
            }
        };

        $this->app->instance(RazorpayService::class, $fake);
    }

    /** Recharge the driver via the verified-Razorpay wallet endpoint. */
    protected function rechargeDriver(string $token, int $planId, float $price, ?int $paidPaise = null): TestResponse
    {
        $this->bindFakeRazorpay($paidPaise ?? (int) round($price * 100));

        return $this->withToken($token)->postJson('/api/driver/wallet/transactions', [
            'amount' => $price,
            'recharge_plan_id' => $planId,
            'order_type' => 'wallet_recharge',
            'razorpay_order_id' => 'order_'.uniqid(),
            'razorpay_payment_id' => 'pay_'.uniqid(),
            'razorpay_signature' => 'sig_'.uniqid(),
        ]);
    }

    /** Drive a city ride create → accept → complete (consumes one ride quota). */
    protected function completeCityRide(array $driver, array $customer): string
    {
        $orderId = $this->withToken($customer['token'])
            ->postJson('/api/customer/orders', $this->cityOrderPayload())
            ->json('data.id');

        $this->withToken($driver['token'])
            ->postJson("/api/driver/orders/{$orderId}/accept", ['driver_id' => (string) $driver['id']]);

        // Assigning the driver + moving into a completed state in one update is
        // enough for the backend to consume a ride (driver_id must be set).
        $this->withToken($driver['token'])
            ->putJson("/api/driver/orders/{$orderId}", [
                'driver_id' => $driver['id'],
                'status' => 'Completed',
            ]);

        // Server-side quota changes (decrement + auto-offline) happen on a
        // separate query path; drop the cached guard user so the next request
        // re-reads the driver fresh (mirrors production's per-request state).
        $this->app['auth']->forgetGuards();

        return (string) $orderId;
    }

    /** A valid customer city-ride create payload (status starts as Ride Placed). */
    protected function cityOrderPayload(): array
    {
        return [
            'service_id' => $this->serviceId,
            'status' => \App\Models\Order::STATUS_RIDE_PLACED,
            'source_location_name' => 'Pickup',
            'source_latitude' => 18.5204,
            'source_longitude' => 73.8567,
            'destination_location_name' => 'Drop',
            'destination_latitude' => 18.5310,
            'destination_longitude' => 73.8470,
            'offer_rate' => '250',
            'distance' => '5',
            'duration' => '15 min',
        ];
    }
}
