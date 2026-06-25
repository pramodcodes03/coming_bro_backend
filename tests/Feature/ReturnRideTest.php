<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\DriverUser;
use App\Models\Order;
use App\Models\ReturnRide;
use App\Models\ReturnRideOffer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Feature tests for the Return Ride feature (scheduled-ride bidding): a customer
 * posts a scheduled ride; recharged drivers submit fare offers; the customer
 * accepts one (others auto-rejected) which spawns a normal Order.
 *
 * Like the rest of the suite, these run against the real MySQL connection in a
 * rolled-back transaction because the project's migrations contain MySQL-only
 * DDL that SQLite cannot parse.
 */
class ReturnRideTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $database = 'coming_bro';
        $envPath = base_path('.env');
        if (is_file($envPath) && preg_match('/^DB_DATABASE=(.*)$/m', file_get_contents($envPath), $m)) {
            $database = trim($m[1]);
        }

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => $database,
        ]);

        \DB::purge('mysql');
    }

    private function driver(bool $withRecharge = true): DriverUser
    {
        return DriverUser::create([
            'full_name' => 'Return Driver '.uniqid(),
            'phone_number' => '93'.random_int(10000000, 99999999),
            'is_online' => true,
            'location_latitude' => 18.5204,
            'location_longitude' => 73.8567,
            'return_ride_recharge_expires_at' => $withRecharge ? now()->addMonths(3) : null,
        ]);
    }

    private function customer(): Customer
    {
        return Customer::create([
            'full_name' => 'Return Passenger '.uniqid(),
            'phone_number' => '90'.random_int(10000000, 99999999),
        ]);
    }

    /** Pickup near Pune Airport, drop in Thane, scheduled in 2 hours. */
    private function schedulePayload(): array
    {
        return [
            'pickup_location_name' => 'Pune Airport',
            'pickup_latitude' => 18.5793,
            'pickup_longitude' => 73.9089,
            'drop_location_name' => 'Thane',
            'drop_latitude' => 19.2183,
            'drop_longitude' => 72.9781,
            'passengers' => 2,
            'scheduled_at' => now()->addHours(2)->toIso8601String(),
            'payment_type' => 'cash',
            'distance' => '120',
            'duration' => '2h 30m',
        ];
    }

    private function scheduleRide(Customer $customer): string
    {
        return $this->actingAs($customer, 'customer')
            ->postJson('/api/customer/return-rides', $this->schedulePayload())
            ->json('data.id');
    }

    public function test_customer_can_schedule_a_return_ride(): void
    {
        $customer = $this->customer();

        $res = $this->actingAs($customer, 'customer')
            ->postJson('/api/customer/return-rides', $this->schedulePayload());

        $res->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', ReturnRide::STATUS_SCHEDULED)
            ->assertJsonPath('data.passengers', 2);

        $ride = ReturnRide::find($res->json('data.id'));
        $this->assertNotNull($ride);
        $this->assertEquals($customer->id, $ride->user_id);
    }

    public function test_driver_without_recharge_is_blocked(): void
    {
        $customer = $this->customer();
        $rideId = $this->scheduleRide($customer);

        $driver = $this->driver(withRecharge: false);

        $this->actingAs($driver, 'sanctum')
            ->getJson('/api/driver/return-rides/available')
            ->assertStatus(403)
            ->assertJsonPath('error_code', 'RETURN_RIDE_RECHARGE_REQUIRED');

        $this->actingAs($driver, 'sanctum')
            ->postJson("/api/driver/return-rides/{$rideId}/offers", ['offered_fare' => 300])
            ->assertStatus(403);
    }

    public function test_driver_with_recharge_can_browse_and_submit_offer(): void
    {
        $customer = $this->customer();
        $rideId = $this->scheduleRide($customer);

        $driver = $this->driver();

        $list = $this->actingAs($driver, 'sanctum')
            ->getJson('/api/driver/return-rides/available');
        $list->assertStatus(200)->assertJsonPath('success', true);
        $this->assertGreaterThanOrEqual(1, count($list->json('data')));

        $offer = $this->actingAs($driver, 'sanctum')
            ->postJson("/api/driver/return-rides/{$rideId}/offers", [
                'offered_fare' => 350,
                'description' => 'AC sedan, 5 min away.',
            ]);

        $offer->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', ReturnRideOffer::STATUS_PENDING)
            ->assertJsonPath('data.offered_fare', '350');
    }

    public function test_accepting_an_offer_creates_order_and_rejects_others(): void
    {
        $customer = $this->customer();
        $rideId = $this->scheduleRide($customer);

        $driverA = $this->driver();
        $driverB = $this->driver();

        $offerA = $this->actingAs($driverA, 'sanctum')
            ->postJson("/api/driver/return-rides/{$rideId}/offers", ['offered_fare' => 350])
            ->json('data.id');
        $offerB = $this->actingAs($driverB, 'sanctum')
            ->postJson("/api/driver/return-rides/{$rideId}/offers", ['offered_fare' => 400])
            ->json('data.id');

        // Customer sees both offers.
        $offers = $this->actingAs($customer, 'customer')
            ->getJson("/api/customer/return-rides/{$rideId}/offers");
        $offers->assertStatus(200);
        $this->assertCount(2, $offers->json('data'));

        // Accept driver A's offer.
        $accept = $this->actingAs($customer, 'customer')
            ->postJson("/api/customer/return-rides/{$rideId}/offers/{$offerA}/accept");
        $accept->assertStatus(200)->assertJsonPath('success', true);

        $ride = ReturnRide::find($rideId);
        $this->assertEquals(ReturnRide::STATUS_ACCEPTED, $ride->status);
        $this->assertEquals($driverA->id, $ride->assigned_driver_id);
        $this->assertNotNull($ride->order_id);

        // An Order was spawned, assigned to driver A, with an OTP.
        $order = Order::find($ride->order_id);
        $this->assertNotNull($order);
        $this->assertEquals($driverA->id, $order->driver_id);
        $this->assertNotNull($order->otp);
        $this->assertEquals('350', $order->final_rate);

        // Offer A accepted, offer B auto-rejected.
        $this->assertEquals(ReturnRideOffer::STATUS_ACCEPTED, ReturnRideOffer::find($offerA)->status);
        $this->assertEquals(ReturnRideOffer::STATUS_REJECTED, ReturnRideOffer::find($offerB)->status);

        // No more offers can be submitted once accepted.
        $this->actingAs($this->driver(), 'sanctum')
            ->postJson("/api/driver/return-rides/{$rideId}/offers", ['offered_fare' => 320])
            ->assertStatus(409);
    }

    public function test_customer_can_reject_a_single_offer(): void
    {
        $customer = $this->customer();
        $rideId = $this->scheduleRide($customer);
        $driver = $this->driver();

        $offerId = $this->actingAs($driver, 'sanctum')
            ->postJson("/api/driver/return-rides/{$rideId}/offers", ['offered_fare' => 350])
            ->json('data.id');

        $this->actingAs($customer, 'customer')
            ->postJson("/api/customer/return-rides/{$rideId}/offers/{$offerId}/reject")
            ->assertStatus(200)
            ->assertJsonPath('data.status', ReturnRideOffer::STATUS_REJECTED);
    }

    public function test_driver_can_withdraw_a_pending_offer(): void
    {
        $customer = $this->customer();
        $rideId = $this->scheduleRide($customer);
        $driver = $this->driver();

        $offerId = $this->actingAs($driver, 'sanctum')
            ->postJson("/api/driver/return-rides/{$rideId}/offers", ['offered_fare' => 350])
            ->json('data.id');

        $this->actingAs($driver, 'sanctum')
            ->putJson("/api/driver/return-ride-offers/{$offerId}")
            ->assertStatus(200)
            ->assertJsonPath('data.status', ReturnRideOffer::STATUS_WITHDRAWN);
    }

    public function test_customer_can_cancel_a_scheduled_ride(): void
    {
        $customer = $this->customer();
        $rideId = $this->scheduleRide($customer);

        $this->actingAs($customer, 'customer')
            ->putJson("/api/customer/return-rides/{$rideId}/cancel")
            ->assertStatus(200)
            ->assertJsonPath('data.status', ReturnRide::STATUS_CANCELLED);
    }

    public function test_purchasing_recharge_activates_the_feature(): void
    {
        $driver = $this->driver(withRecharge: false);

        $this->actingAs($driver, 'sanctum')
            ->getJson('/api/driver/return-rides/recharge-status')
            ->assertStatus(200)
            ->assertJsonPath('data.active', false);

        $this->actingAs($driver, 'sanctum')
            ->postJson('/api/driver/return-rides/recharge', ['payment_type' => 'online'])
            ->assertStatus(200)
            ->assertJsonPath('data.active', true);

        $this->assertTrue($driver->fresh()->hasActiveReturnRideRecharge());
    }
}
