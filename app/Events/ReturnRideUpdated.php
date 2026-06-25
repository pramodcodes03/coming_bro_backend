<?php

namespace App\Events;

use App\Models\ReturnRide;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast when a scheduled return ride changes (accepted, cancelled,
 * completed, expired). Delivered to the owning customer and, once assigned,
 * the chosen driver so both stay in sync in real time.
 */
class ReturnRideUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ReturnRide $returnRide) {}

    public function broadcastOn(): array
    {
        $channels = [];
        if ($this->returnRide->user_id) {
            $channels[] = new PrivateChannel('customer.' . $this->returnRide->user_id . '.return-rides');
        }
        if ($this->returnRide->assigned_driver_id) {
            $channels[] = new PrivateChannel('driver.' . $this->returnRide->assigned_driver_id . '.return-rides');
        }
        return $channels;
    }

    public function broadcastWith(): array
    {
        return ['returnRide' => $this->returnRide->toArray()];
    }
}
