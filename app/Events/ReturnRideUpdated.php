<?php

namespace App\Events;

use App\Models\ReturnRide;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast when a published return ride changes (seats taken, cancelled,
 * completed). Delivered to the owning driver and re-broadcast on the shared
 * customer channel so discovery lists stay in sync.
 */
class ReturnRideUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ReturnRide $returnRide) {}

    public function broadcastOn(): array
    {
        $channels = [new PrivateChannel('customers.new-return-rides')];
        if ($this->returnRide->driver_id) {
            $channels[] = new PrivateChannel('driver.' . $this->returnRide->driver_id . '.return-rides');
        }
        return $channels;
    }

    public function broadcastWith(): array
    {
        return ['returnRide' => $this->returnRide->toArray()];
    }
}
