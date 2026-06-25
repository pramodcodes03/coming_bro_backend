<?php

namespace App\Events;

use App\Models\ReturnRideOffer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast when a driver submits a NEW fare offer on a customer's scheduled
 * return ride. Delivered to the owning customer's private channel so their app
 * can play an alert sound and surface the offer in real time.
 */
class NewReturnRideOffer implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ReturnRideOffer $offer) {}

    public function broadcastOn(): array
    {
        $channels = [];
        $userId = $this->offer->returnRide?->user_id;
        if ($userId) {
            $channels[] = new PrivateChannel('customer.' . $userId . '.return-rides');
        }
        return $channels;
    }

    public function broadcastWith(): array
    {
        return ['offer' => $this->offer->toArray()];
    }
}
