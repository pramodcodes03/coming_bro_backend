<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast when a customer places a NEW city order that is not yet assigned
 * to any driver. Sent on a shared driver channel so every online driver gets
 * an instant "new ride available" signal and can refresh their nearby list
 * (which is still filtered server-side by radius / service / zone).
 */
class NewOrderPlaced implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('drivers.new-orders')];
    }

    public function broadcastWith(): array
    {
        return ['order' => $this->order->toArray()];
    }
}
