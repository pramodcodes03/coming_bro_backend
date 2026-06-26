<?php

namespace Tests\Feature;

use App\Models\ReturnRide;
use App\Models\ReturnRideOffer;

/**
 * Return Ride bidding — focused offer-management cases (scheduling, reject,
 * withdraw, cancel) that complement the end-to-end ReturnRideFlowTest. Runs on
 * the same RefreshDatabase harness.
 */
class ReturnRideTest extends FlowTestCase
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
        ];
    }

    private function scheduleRide(array $customer): string
    {
        return $this->withToken($customer['token'])
            ->postJson('/api/customer/return-rides', $this->schedulePayload())
            ->assertStatus(201)
            ->assertJsonPath('data.status', ReturnRide::STATUS_SCHEDULED)
            ->json('data.id');
    }

    private function submitOffer(array $driver, string $rideId, int $fare = 300): string
    {
        return $this->withToken($driver['token'])
            ->postJson("/api/driver/return-rides/{$rideId}/offers", ['offered_fare' => $fare])
            ->assertStatus(201)
            ->json('data.id');
    }

    public function test_customer_can_schedule_and_list_return_rides(): void
    {
        $customer = $this->loginCustomer();
        $rideId = $this->scheduleRide($customer);

        $this->withToken($customer['token'])
            ->getJson('/api/customer/return-rides')
            ->assertStatus(200)
            ->assertJsonPath('data.0.id', (int) $rideId);
    }

    public function test_customer_can_reject_a_single_offer(): void
    {
        $customer = $this->loginCustomer();
        $driver = $this->loginDriver();
        $this->rechargeDriver($driver['token'], $this->returnPlanId, 99);

        $rideId = $this->scheduleRide($customer);
        $offerId = $this->submitOffer($driver, $rideId);

        $this->withToken($customer['token'])
            ->postJson("/api/customer/return-rides/{$rideId}/offers/{$offerId}/reject")
            ->assertStatus(200)
            ->assertJsonPath('data.status', ReturnRideOffer::STATUS_REJECTED);
    }

    public function test_driver_can_withdraw_a_pending_offer(): void
    {
        $customer = $this->loginCustomer();
        $driver = $this->loginDriver();
        $this->rechargeDriver($driver['token'], $this->returnPlanId, 99);

        $rideId = $this->scheduleRide($customer);
        $offerId = $this->submitOffer($driver, $rideId);

        $this->withToken($driver['token'])
            ->putJson("/api/driver/return-ride-offers/{$offerId}")
            ->assertStatus(200)
            ->assertJsonPath('data.status', ReturnRideOffer::STATUS_WITHDRAWN);
    }

    public function test_customer_can_cancel_a_scheduled_ride(): void
    {
        $customer = $this->loginCustomer();
        $rideId = $this->scheduleRide($customer);

        $this->withToken($customer['token'])
            ->putJson("/api/customer/return-rides/{$rideId}/cancel")
            ->assertStatus(200)
            ->assertJsonPath('data.status', ReturnRide::STATUS_CANCELLED);
    }
}
