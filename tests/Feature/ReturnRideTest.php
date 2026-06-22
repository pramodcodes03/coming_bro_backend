<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\DriverUser;
use App\Models\ReturnRide;
use App\Models\ReturnRideBooking;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Feature tests for the Return Ride feature (driver publishes a return
 * journey; passengers discover it along the corridor / time window and book).
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

    private function driver(): DriverUser
    {
        return DriverUser::create([
            'full_name' => 'Return Driver '.uniqid(),
            'phone_number' => '93'.random_int(10000000, 99999999),
            'is_online' => true,
            'location_latitude' => 18.5204,
            'location_longitude' => 73.8567,
        ]);
    }

    private function customer(): Customer
    {
        return Customer::create([
            'full_name' => 'Return Passenger '.uniqid(),
            'phone_number' => '90'.random_int(10000000, 99999999),
        ]);
    }

    /** Pune Airport → Thane return ride departing in 2 hours, 2-hour window. */
    private function publishPayload(): array
    {
        return [
            'source_location_name' => 'Pune Airport',
            'source_latitude' => 18.5793,
            'source_longitude' => 73.9089,
            'destination_location_name' => 'Thane',
            'destination_latitude' => 19.2183,
            'destination_longitude' => 72.9781,
            'distance' => '120',
            'duration' => '2h 30m',
            'departure_time' => now()->addHours(2)->toIso8601String(),
            'pickup_window_hours' => 2,
            'seats_total' => 4,
            'fare_per_seat' => '350',
        ];
    }

    public function test_driver_can_publish_a_return_ride(): void
    {
        $driver = $this->driver();

        $res = $this->actingAs($driver, 'sanctum')
            ->postJson('/api/driver/return-rides', $this->publishPayload());

        $res->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', ReturnRide::STATUS_ACTIVE)
            ->assertJsonPath('data.seats_available', 4);

        $ride = ReturnRide::find($res->json('data.id'));
        $this->assertNotNull($ride);
        $this->assertEquals($driver->id, $ride->driver_id);
        // Pickup window end = departure + window hours.
        $this->assertEquals(
            $ride->departure_time->copy()->addHours(2)->timestamp,
            $ride->pickup_window_end->timestamp
        );
    }

    public function test_passenger_discovers_ride_within_corridor_and_window(): void
    {
        $driver = $this->driver();
        $this->actingAs($driver, 'sanctum')
            ->postJson('/api/driver/return-rides', $this->publishPayload())
            ->assertStatus(201);

        $passenger = $this->customer();

        // Pickup ~3km from Pune Airport, drop near Thane, time inside the window.
        $found = $this->actingAs($passenger, 'customer')->getJson(
            '/api/customer/return-rides/available?'.http_build_query([
                'pickup_latitude' => 18.5800,
                'pickup_longitude' => 73.8800,
                'drop_latitude' => 19.2000,
                'drop_longitude' => 72.9800,
                'when' => now()->addHours(3)->toIso8601String(),
            ])
        );
        $found->assertStatus(200)->assertJsonPath('success', true);
        $this->assertGreaterThanOrEqual(1, count($found->json('data')));

        // A pickup far away (Delhi) must NOT match.
        $none = $this->actingAs($passenger, 'customer')->getJson(
            '/api/customer/return-rides/available?'.http_build_query([
                'pickup_latitude' => 28.6139,
                'pickup_longitude' => 77.2090,
            ])
        );
        $none->assertStatus(200);
        $this->assertCount(0, $none->json('data'));
    }

    public function test_passenger_can_book_and_seat_is_decremented(): void
    {
        $driver = $this->driver();
        $rideId = $this->actingAs($driver, 'sanctum')
            ->postJson('/api/driver/return-rides', $this->publishPayload())
            ->json('data.id');

        $passenger = $this->customer();
        $book = $this->actingAs($passenger, 'customer')->postJson(
            "/api/customer/return-rides/{$rideId}/book",
            [
                'pickup_location_name' => 'Wakad',
                'pickup_latitude' => 18.5980,
                'pickup_longitude' => 73.7600,
                'drop_location_name' => 'Thane',
                'drop_latitude' => 19.2000,
                'drop_longitude' => 72.9800,
                'number_of_passenger' => '2',
            ]
        );

        $book->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', ReturnRideBooking::STATUS_CONFIRMED);
        $this->assertNotNull($book->json('data.otp'));

        $ride = ReturnRide::find($rideId);
        $this->assertEquals(2, $ride->seats_available); // 4 - 2

        // Double booking the same ride is rejected.
        $this->actingAs($passenger, 'customer')
            ->postJson("/api/customer/return-rides/{$rideId}/book", [
                'pickup_latitude' => 18.5980,
                'pickup_longitude' => 73.7600,
            ])
            ->assertStatus(409);
    }

    public function test_cancelling_a_booking_restores_the_seat(): void
    {
        $driver = $this->driver();
        $rideId = $this->actingAs($driver, 'sanctum')
            ->postJson('/api/driver/return-rides', $this->publishPayload())
            ->json('data.id');

        $passenger = $this->customer();
        $bookingId = $this->actingAs($passenger, 'customer')
            ->postJson("/api/customer/return-rides/{$rideId}/book", [
                'pickup_latitude' => 18.5980,
                'pickup_longitude' => 73.7600,
                'number_of_passenger' => '1',
            ])->json('data.id');

        $this->assertEquals(3, ReturnRide::find($rideId)->seats_available);

        $this->actingAs($passenger, 'customer')
            ->putJson("/api/customer/return-ride-bookings/{$bookingId}/cancel")
            ->assertStatus(200)
            ->assertJsonPath('data.status', ReturnRideBooking::STATUS_CANCELLED);

        $this->assertEquals(4, ReturnRide::find($rideId)->seats_available);
    }

    public function test_driver_sees_passenger_bookings_on_their_ride(): void
    {
        $driver = $this->driver();
        $rideId = $this->actingAs($driver, 'sanctum')
            ->postJson('/api/driver/return-rides', $this->publishPayload())
            ->json('data.id');

        $passenger = $this->customer();
        $this->actingAs($passenger, 'customer')
            ->postJson("/api/customer/return-rides/{$rideId}/book", [
                'pickup_latitude' => 18.5980,
                'pickup_longitude' => 73.7600,
            ])->assertStatus(201);

        $bookings = $this->actingAs($driver, 'sanctum')
            ->getJson("/api/driver/return-rides/{$rideId}/bookings");

        $bookings->assertStatus(200)->assertJsonPath('success', true);
        $this->assertGreaterThanOrEqual(1, count($bookings->json('data')));
    }
}
