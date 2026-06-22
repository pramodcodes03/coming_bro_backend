<?php

namespace App\Events;

use App\Models\ReturnRideBooking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast when a return ride booking is created or changes status. Delivered
 * to BOTH parties: the passenger who booked (ride confirmation) and the driver
 * who published the ride (new booking alert).
 */
class ReturnRideBookingUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ReturnRideBooking $booking) {}

    public function broadcastOn(): array
    {
        $channels = [];
        if ($this->booking->user_id) {
            $channels[] = new PrivateChannel('customer.' . $this->booking->user_id . '.return-rides');
        }
        if ($this->booking->driver_id) {
            $channels[] = new PrivateChannel('driver.' . $this->booking->driver_id . '.return-rides');
        }
        return $channels;
    }

    public function broadcastWith(): array
    {
        return ['booking' => $this->booking->toArray()];
    }
}
