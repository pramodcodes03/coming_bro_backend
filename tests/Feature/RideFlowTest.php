<?php

namespace Tests\Feature;

use App\Models\DriverUser;
use App\Models\Order;

/**
 * City Ride happy path, driven entirely through the HTTP API:
 * recharge (verified) → create → nearby → accept → lifecycle → completed
 * (quota consumed exactly once) → review (rating aggregates).
 */
class RideFlowTest extends FlowTestCase
{
    public function test_full_city_ride_flow_consumes_one_ride_and_aggregates_review(): void
    {
        $driver = $this->loginDriver();
        $customer = $this->loginCustomer();

        // 1. Driver recharges → 10 rides.
        $this->rechargeDriver($driver['token'], $this->dailyPlanId, 49)
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('remaining_rides', 10);

        // 2. Driver can now go online (has quota).
        $this->withToken($driver['token'])
            ->putJson('/api/driver/profile', ['is_online' => true])
            ->assertStatus(200)
            ->assertJsonPath('data.is_online', true);

        // 3. Customer creates a ride (Ride Placed).
        $create = $this->withToken($customer['token'])
            ->postJson('/api/customer/orders', $this->cityOrderPayload());
        $create->assertStatus(201)->assertJsonPath('data.status', Order::STATUS_RIDE_PLACED);
        $orderId = $create->json('data.id');

        // 4. Driver discovers it nearby.
        $nearby = $this->withToken($driver['token'])
            ->getJson('/api/driver/orders/nearby?latitude=18.5204&longitude=73.8567&radius=10');
        $nearby->assertStatus(200);
        $this->assertContains((int) $orderId, collect($nearby->json('data'))->pluck('id')->map(fn ($i) => (int) $i)->all());

        // 5. Driver accepts.
        $this->withToken($driver['token'])
            ->postJson("/api/driver/orders/{$orderId}/accept", ['driver_id' => (string) $driver['id']])
            ->assertStatus(200);

        // 6. Ride is assigned + advances to active (no consumption yet).
        $this->withToken($driver['token'])
            ->putJson("/api/driver/orders/{$orderId}", [
                'driver_id' => $driver['id'],
                'status' => 'Ride Active',
            ])->assertStatus(200);
        $this->assertSame(10, (int) DriverUser::find($driver['id'])->remaining_rides);

        // 7. Ride completes → exactly one ride consumed.
        $this->withToken($driver['token'])
            ->putJson("/api/driver/orders/{$orderId}", ['status' => 'Completed'])
            ->assertStatus(200);
        $this->assertSame(9, (int) DriverUser::find($driver['id'])->remaining_rides);

        // Re-completing must NOT double-consume.
        $this->withToken($driver['token'])
            ->putJson("/api/driver/orders/{$orderId}", ['status' => 'Completed'])
            ->assertStatus(200);
        $this->assertSame(9, (int) DriverUser::find($driver['id'])->remaining_rides);

        // 8. Customer reviews the driver → aggregates update.
        $this->withToken($customer['token'])
            ->postJson('/api/customer/reviews', [
                'driver_id' => $driver['id'],
                'rating' => 5,
                'comment' => 'Great ride',
            ])->assertStatus(201);

        $fresh = DriverUser::find($driver['id']);
        $this->assertEquals(1.0, (float) $fresh->reviews_count);
        $this->assertEquals(5.0, (float) $fresh->reviews_sum);
    }
}
