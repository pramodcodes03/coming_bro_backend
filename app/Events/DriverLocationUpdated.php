<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverLocationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $driverId,
        public float $latitude,
        public float $longitude,
        public ?float $rotation = null,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('driver.' . $this->driverId . '.location')];
    }

    public function broadcastWith(): array
    {
        return [
            'driver_id' => $this->driverId,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'rotation' => $this->rotation,
        ];
    }
}
