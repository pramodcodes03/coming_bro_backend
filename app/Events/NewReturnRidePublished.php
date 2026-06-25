<?php

namespace App\Events;

use App\Models\ReturnRide;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast when a customer posts a NEW scheduled return ride. Sent on a shared
 * driver channel so every online (recharged) driver gets an instant "new
 * scheduled ride available" signal and can refresh their list.
 */
class NewReturnRidePublished implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ReturnRide $returnRide) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('drivers.new-return-rides')];
    }

    public function broadcastWith(): array
    {
        return ['returnRide' => $this->returnRide->toArray()];
    }
}
