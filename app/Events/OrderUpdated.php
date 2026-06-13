<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order) {}

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('customer.' . $this->order->user_id . '.orders'),
        ];
        if ($this->order->driver_id) {
            $channels[] = new PrivateChannel('driver.' . $this->order->driver_id . '.orders');
        }
        return $channels;
    }

    public function broadcastWith(): array
    {
        return ['order' => $this->order->toArray()];
    }
}
