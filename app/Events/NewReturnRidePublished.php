<?php

namespace App\Events;

use App\Models\ReturnRide;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast when a driver publishes a NEW return ride. Sent on a shared
 * customer channel so every online passenger gets an instant "new return ride
 * available" signal and can refresh their discovery list (still filtered
 * server-side by corridor proximity / time window).
 */
class NewReturnRidePublished implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ReturnRide $returnRide) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('customers.new-return-rides')];
    }

    public function broadcastWith(): array
    {
        return ['returnRide' => $this->returnRide->toArray()];
    }
}
