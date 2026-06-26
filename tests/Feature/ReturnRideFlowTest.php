<?php

namespace Tests\Feature;

use App\Models\DriverUser;
use App\Models\Order;
use App\Models\ReturnRide;

/**
 * Return Ride (scheduled-ride bidding) happy path, end-to-end over HTTP:
 * recharge gate enforced → customer posts a scheduled ride → driver offers →
 * customer accepts (spawns an Order linked by return_ride_id) → ride lifecycle
 * (quota consumed) → review.
 */
class ReturnRideFlowTest extends FlowTestCase
{
    private function schedulePayload(): array
    {
        return [
            'service_id' => $this->serviceId,
            'pickup_location_name' => 'Pickup',
            'pickup_latitude' => 18.5204,
            'pickup_longitude' => 73.8567,
            'drop_location_name' => 'Drop',
            'drop_latitude' => 18.5310,
            'drop_longitude' => 73.8470,
            'passengers' => 2,
            'scheduled_at' => now()->addHours(2)->toIso8601String(),
            'payment_type' => 'cash',
            'distance' => '6',
            'duration' => '20 min',
        ];
    }

    public function test_recharge_gate_blocks_browsing_and_offering_without_quota(): void
    {
        $driver = $this->loginDriver();   // fresh → 0 rides
        $customer = $this->loginCustomer();

        $rideId = $this->withToken($customer['token'])
            ->postJson('/api/customer/return-rides', $this->schedulePayload())
            ->assertStatus(201)->json('data.id');

        $this->withToken($driver['token'])
            ->getJson('/api/driver/return-rides/available')
            ->assertStatus(403)
            ->assertJsonPath('error_code', 'RIDE_RECHARGE_REQUIRED');

        $this->withToken($driver['token'])
            ->postJson("/api/driver/return-rides/{$rideId}/offers", ['offered_fare' => 300])
            ->assertStatus(403);
    }

    public function test_full_return_ride_flow_spawns_order_and_consumes_quota(): void
    {
        $driver = $this->loginDriver();
        $customer = $this->loginCustomer();

        // Recharge → gate opens.
        $this->rechargeDriver($driver['token'], $this->returnPlanId, 99)
            ->assertStatus(200)->assertJsonPath('remaining_rides', 10);

        // Customer posts a scheduled ride.
        $rideId = $this->withToken($customer['token'])
            ->postJson('/api/customer/return-rides', $this->schedulePayload())
            ->assertStatus(201)
            ->assertJsonPath('data.status', ReturnRide::STATUS_SCHEDULED)
            ->json('data.id');

        // Recharged driver can browse + submit an offer.
        $this->withToken($driver['token'])
            ->getJson('/api/driver/return-rides/available')
            ->assertStatus(200);

        $offerId = $this->withToken($driver['token'])
            ->postJson("/api/driver/return-rides/{$rideId}/offers", [
                'offered_fare' => 320,
                'description' => 'AC sedan, 5 min away',
            ])
            ->assertStatus(201)
            ->assertJsonPath('data.status', 'Pending')
            ->json('data.id');

        // Customer accepts → ride becomes an Order linked by return_ride_id.
        $this->withToken($customer['token'])
            ->postJson("/api/customer/return-rides/{$rideId}/offers/{$offerId}/accept")
            ->assertStatus(200)
            ->assertJsonPath('data.status', ReturnRide::STATUS_ACCEPTED);

        $order = Order::where('return_ride_id', $rideId)->first();
        $this->assertNotNull($order, 'an Order should be spawned from the accepted offer');
        $this->assertEquals($driver['id'], $order->driver_id);
        $this->assertEquals('320', $order->final_rate);

        // Same lifecycle as a city ride → completing consumes one ride.
        $this->withToken($driver['token'])
            ->putJson("/api/driver/orders/{$order->id}", ['status' => 'Completed'])
            ->assertStatus(200);
        $this->assertSame(9, (int) DriverUser::find($driver['id'])->remaining_rides);

        // Review.
        $this->withToken($customer['token'])
            ->postJson('/api/customer/reviews', [
                'driver_id' => $driver['id'],
                'rating' => 4,
            ])->assertStatus(201);
        $this->assertEquals(4.0, (float) DriverUser::find($driver['id'])->reviews_sum);
    }
}
