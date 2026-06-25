<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A customer's scheduled Return Ride request. Drivers submit fare offers
 * (`ReturnRideOffer`); the customer accepts one, which spawns a normal `Order`
 * referenced by `order_id` for the standard execution flow.
 */
class ReturnRide extends Model
{
    use HasFactory;

    protected $table = 'return_rides';

    public $timestamps = false;

    /** Lifecycle status values — single source of truth across the platform. */
    public const STATUS_SCHEDULED = 'Scheduled';   // open, accepting offers
    public const STATUS_ACCEPTED  = 'Accepted';    // a driver was chosen (handed off to an Order)
    public const STATUS_COMPLETED = 'Completed';
    public const STATUS_CANCELLED = 'Cancelled';
    public const STATUS_EXPIRED   = 'Expired';

    protected $fillable = [
        'user_id',
        'service_id',
        'pickup_location_name',
        'pickup_latitude',
        'pickup_longitude',
        'drop_location_name',
        'drop_latitude',
        'drop_longitude',
        'passengers',
        'scheduled_at',
        'payment_type',
        'route_polyline',
        'route_coordinates',
        'distance',
        'distance_type',
        'duration',
        'zone',
        'zone_id',
        'status',
        'comments',
        'accepted_offer_id',
        'assigned_driver_id',
        'order_id',
        'created_date',
        'update_date',
    ];

    protected function casts(): array
    {
        return [
            'route_coordinates' => 'array',
            'zone'              => 'array',
            'passengers'        => 'integer',
            'scheduled_at'      => 'datetime',
            'created_date'      => 'datetime',
            'update_date'       => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(ReturnRideOffer::class, 'return_ride_id', 'id');
    }

    public function acceptedOffer(): BelongsTo
    {
        return $this->belongsTo(ReturnRideOffer::class, 'accepted_offer_id', 'id');
    }

    public function assignedDriver(): BelongsTo
    {
        return $this->belongsTo(DriverUser::class, 'assigned_driver_id', 'id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
