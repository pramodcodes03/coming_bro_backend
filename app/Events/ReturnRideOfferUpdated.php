<?php

namespace App\Events;

use App\Models\ReturnRideOffer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast when a return ride offer changes status (accepted / rejected /
 * withdrawn). Delivered to BOTH parties: the driver who made the offer and the
 * customer who owns the ride.
 */
class ReturnRideOfferUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ReturnRideOffer $offer) {}

    public function broadcastOn(): array
    {
        $channels = [];
        if ($this->offer->driver_id) {
            $channels[] = new PrivateChannel('driver.' . $this->offer->driver_id . '.return-rides');
        }
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
