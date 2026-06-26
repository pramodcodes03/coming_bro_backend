<?php

namespace Tests\Feature;

use App\Models\DriverUser;

/**
 * Driver Recharge & Ride Quota Management (PRIORITY 1) — the backend-enforced
 * rules: gating at zero quota, increment on recharge, decrement on completion,
 * auto-offline when exhausted, and the home-screen state the app reads back.
 */
class RideQuotaTest extends FlowTestCase
{
    public function test_new_driver_has_zero_quota_and_cannot_go_online(): void
    {
        $driver = $this->loginDriver();

        // Recharge status reads "inactive / 0 rides" → app shows Recharge Required.
        $this->withToken($driver['token'])
            ->getJson('/api/driver/return-rides/recharge-status')
            ->assertStatus(200)
            ->assertJsonPath('data.active', false)
            ->assertJsonPath('data.remaining_rides', 0);

        // Cannot go online with zero quota.
        $this->withToken($driver['token'])
            ->putJson('/api/driver/profile', ['is_online' => true])
            ->assertStatus(403)
            ->assertJsonPath('error_code', 'RIDE_RECHARGE_REQUIRED');
        $this->assertFalse((bool) DriverUser::find($driver['id'])->is_online);

        // Hidden from city-ride matching (no requests).
        $this->withToken($driver['token'])
            ->getJson('/api/driver/orders/nearby?latitude=18.5204&longitude=73.8567')
            ->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_zero_quota_blocks_accepting_a_ride(): void
    {
        $driver = $this->loginDriver();
        $customer = $this->loginCustomer();

        $orderId = $this->withToken($customer['token'])
            ->postJson('/api/customer/orders', $this->cityOrderPayload())
            ->json('data.id');

        $this->withToken($driver['token'])
            ->postJson("/api/driver/orders/{$orderId}/accept", ['driver_id' => (string) $driver['id']])
            ->assertStatus(403)
            ->assertJsonPath('error_code', 'RIDE_RECHARGE_REQUIRED');
    }

    public function test_recharge_activates_and_increments_quota(): void
    {
        $driver = $this->loginDriver();

        $this->rechargeDriver($driver['token'], $this->dailyPlanId, 49)->assertStatus(200);

        $this->withToken($driver['token'])
            ->getJson('/api/driver/return-rides/recharge-status')
            ->assertStatus(200)
            ->assertJsonPath('data.active', true)
            ->assertJsonPath('data.remaining_rides', 10);
    }

    public function test_completion_decrements_quota_and_home_state_reflects_it(): void
    {
        $driver = $this->loginDriver();
        $customer = $this->loginCustomer();

        $this->rechargeDriver($driver['token'], $this->dailyPlanId, 49);
        $this->withToken($driver['token'])->putJson('/api/driver/profile', ['is_online' => true]);

        $this->completeCityRide($driver, $customer);

        // Home-screen state (recharge-status) reflects the decremented count.
        $this->withToken($driver['token'])
            ->getJson('/api/driver/return-rides/recharge-status')
            ->assertStatus(200)
            ->assertJsonPath('data.remaining_rides', 9);
    }

    public function test_low_quota_count_is_exposed_for_the_two_rides_warning(): void
    {
        $driver = $this->loginDriver();
        DriverUser::find($driver['id'])->update(['remaining_rides' => 2, 'total_rides' => 10]);

        // The app shows the "Only 2 Rides Left" popup off this value.
        $this->withToken($driver['token'])
            ->getJson('/api/driver/return-rides/recharge-status')
            ->assertStatus(200)
            ->assertJsonPath('data.active', true)
            ->assertJsonPath('data.remaining_rides', 2);
    }

    public function test_driver_is_taken_offline_when_quota_hits_zero(): void
    {
        $driver = $this->loginDriver();
        $customer = $this->loginCustomer();

        // One ride left, online.
        DriverUser::find($driver['id'])->update(['remaining_rides' => 1, 'total_rides' => 10, 'is_online' => true]);

        $this->completeCityRide($driver, $customer);

        $fresh = DriverUser::find($driver['id']);
        $this->assertSame(0, (int) $fresh->remaining_rides);
        $this->assertFalse((bool) $fresh->is_online, 'driver should be auto-set offline at zero quota');

        // And can no longer go back online until recharging.
        $this->withToken($driver['token'])
            ->putJson('/api/driver/profile', ['is_online' => true])
            ->assertStatus(403);
    }
}
