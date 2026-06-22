<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\DriverUser;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Feature tests for the God's Eye View.
 *
 * The project's migrations contain MySQL-only raw DDL (ALTER TABLE ... DROP
 * FOREIGN KEY) that SQLite cannot parse, so the suite cannot use the in-memory
 * SQLite DB + RefreshDatabase. These tests instead run against the real MySQL
 * connection inside a transaction that is rolled back, so they exercise the
 * live schema without leaving any data behind.
 */
class GodsEyeViewTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Force the real (MySQL) connection even when phpunit.xml points the
        // default at sqlite / :memory:, so the live schema (incl.
        // last_online_at) is used. phpunit.xml also sets DB_DATABASE=:memory:,
        // which leaks onto the mysql connection config, so restore the real DB
        // name from .env and purge the cached PDO so it reconnects cleanly.
        // phpunit.xml sets DB_DATABASE=:memory:, so env() is unreliable here —
        // read the real database name straight from the .env file.
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

    private function admin(): Admin
    {
        $unique = uniqid();

        return Admin::create([
            'name' => 'Test Admin GE',
            'username' => 'ge-test-'.$unique,
            'email' => 'gods-eye-test-'.$unique.'@example.com',
            'password' => bcrypt('secret'),
        ]);
    }

    public function test_gods_eye_view_requires_admin_auth(): void
    {
        $this->get('/admin/gods-eye')->assertRedirect();
        $this->getJson('/admin/gods-eye/feed')->assertStatus(401);
    }

    public function test_index_renders_for_authenticated_admin(): void
    {
        $response = $this->actingAs($this->admin(), 'admin')->get('/admin/gods-eye');

        $response->assertStatus(200);
        $response->assertSee("God's Eye View", false);
        $response->assertSee('godsEye(', false); // Alpine component mounted
    }

    public function test_feed_returns_expected_structure(): void
    {
        $response = $this->actingAs($this->admin(), 'admin')->getJson('/admin/gods-eye/feed');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonStructure([
            'generated_at',
            'generated_at_label',
            'kpis' => [
                'total_drivers', 'online_drivers', 'offline_drivers', 'online_today',
                'inactive_drivers', 'active_trips', 'today_trips', 'today_completed', 'drivers_on_trip',
            ],
            'drivers',
            'active_trips',
            'recent_trips',
        ]);
    }

    public function test_feed_reflects_an_active_ride_with_driver_and_passenger(): void
    {
        $customer = Customer::create(['full_name' => 'Jane Passenger', 'phone_number' => '9000000001']);
        $driver = DriverUser::create([
            'full_name' => 'Online Driver GE',
            'phone_number' => '9111110001',
            'is_online' => true,
            'last_online_at' => now(),
            'location_latitude' => 19.07,
            'location_longitude' => 72.87,
        ]);

        Order::create([
            'source_location_name' => 'Pickup Point',
            'destination_location_name' => 'Drop Point',
            'status' => 'Ride Active',
            'driver_id' => $driver->id,
            'user_id' => $customer->id,
            'final_rate' => '250',
            'position_latitude' => 19.08,
            'position_longitude' => 72.88,
        ]);

        $json = $this->actingAs($this->admin(), 'admin')->getJson('/admin/gods-eye/feed')->json();

        // The driver appears, is online and flagged on-trip.
        $row = collect($json['drivers'])->firstWhere('id', $driver->id);
        $this->assertNotNull($row);
        $this->assertTrue($row['is_online']);
        $this->assertTrue($row['on_trip']);
        $this->assertTrue($row['has_location']);
        $this->assertNotNull($row['online_since_label']);

        // The active trip exposes granular pickup/drop/passenger/driver detail.
        $trip = collect($json['active_trips'])->firstWhere('driver.id', $driver->id);
        $this->assertNotNull($trip);
        $this->assertSame('active', $trip['status_bucket']);
        $this->assertSame('Pickup Point', $trip['pickup']);
        $this->assertSame('Drop Point', $trip['drop']);
        $this->assertSame('Jane Passenger', $trip['passenger']['name']);
        $this->assertTrue($trip['has_live_location']);
    }

    public function test_status_buckets_map_real_world_values(): void
    {
        $customer = Customer::create(['full_name' => 'P GE', 'phone_number' => '9000000002']);
        $cases = [
            'Ride Placed' => 'placed',
            'Ride Active' => 'active',
            'Ride Canceled' => 'cancelled',
            'Completed' => 'completed',
            'totally unknown state' => 'placed',
        ];

        $ids = [];
        foreach ($cases as $status => $bucket) {
            $ids[$status] = Order::create([
                'source_location_name' => 'A',
                'destination_location_name' => 'B',
                'status' => $status,
                'user_id' => $customer->id,
            ])->id;
        }

        $recent = collect(
            $this->actingAs($this->admin(), 'admin')->getJson('/admin/gods-eye/feed')->json('recent_trips')
        )->keyBy('id');

        foreach ($cases as $status => $bucket) {
            $this->assertSame(
                $bucket,
                $recent[$ids[$status]]['status_bucket'],
                "Status '{$status}' should bucket as '{$bucket}'"
            );
        }
    }

    public function test_apply_online_state_stamps_only_on_transition(): void
    {
        $driver = new DriverUser(['full_name' => 'D', 'phone_number' => '9', 'is_online' => false]);

        // offline -> online: stamps last_online_at.
        $driver->applyOnlineState(true);
        $this->assertTrue($driver->is_online);
        $this->assertNotNull($driver->last_online_at);
        $firstStamp = $driver->last_online_at->copy();

        // online -> online again: must NOT move the timestamp.
        $driver->applyOnlineState(true);
        $this->assertSame($firstStamp->toDateTimeString(), $driver->last_online_at->toDateTimeString());

        // online -> offline: keeps the history timestamp, just flips the flag.
        $driver->applyOnlineState(false);
        $this->assertFalse($driver->is_online);
        $this->assertNotNull($driver->last_online_at);
    }
}
