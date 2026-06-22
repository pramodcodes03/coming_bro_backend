<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnRide extends Model
{
    use HasFactory;

    protected $table = 'return_rides';

    public $timestamps = false;

    /** Lifecycle status values — single source of truth across the platform. */
    public const STATUS_ACTIVE    = 'Active';
    public const STATUS_COMPLETED = 'Completed';
    public const STATUS_CANCELLED = 'Cancelled';
    public const STATUS_EXPIRED   = 'Expired';

    protected $fillable = [
        'driver_id',
        'service_id',
        'source_location_name',
        'source_latitude',
        'source_longitude',
        'destination_location_name',
        'destination_latitude',
        'destination_longitude',
        'route_polyline',
        'route_coordinates',
        'distance',
        'distance_type',
        'duration',
        'departure_time',
        'pickup_window_hours',
        'pickup_window_start',
        'pickup_window_end',
        'seats_total',
        'seats_available',
        'fare_per_seat',
        'offer_rate',
        'position_geohash',
        'position_latitude',
        'position_longitude',
        'zone',
        'zone_id',
        'status',
        'comments',
        'created_date',
        'update_date',
    ];

    protected function casts(): array
    {
        return [
            'route_coordinates'  => 'array',
            'zone'               => 'array',
            'departure_time'     => 'datetime',
            'pickup_window_start' => 'datetime',
            'pickup_window_end'  => 'datetime',
            'pickup_window_hours' => 'integer',
            'seats_total'        => 'integer',
            'seats_available'    => 'integer',
            'created_date'       => 'datetime',
            'update_date'        => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(DriverUser::class, 'driver_id', 'id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(ReturnRideBooking::class, 'return_ride_id', 'id');
    }
}
