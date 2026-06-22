<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRideBooking extends Model
{
    use HasFactory;

    protected $table = 'return_ride_bookings';

    public $timestamps = false;

    public const STATUS_PENDING   = 'Pending';
    public const STATUS_CONFIRMED = 'Confirmed';
    public const STATUS_CANCELLED = 'Cancelled';
    public const STATUS_COMPLETED = 'Completed';

    protected $fillable = [
        'return_ride_id',
        'user_id',
        'driver_id',
        'pickup_location_name',
        'pickup_latitude',
        'pickup_longitude',
        'drop_location_name',
        'drop_latitude',
        'drop_longitude',
        'number_of_passenger',
        'fare',
        'final_rate',
        'payment_type',
        'payment_status',
        'otp',
        'comments',
        'status',
        'created_date',
        'update_date',
    ];

    protected function casts(): array
    {
        return [
            'payment_status' => 'boolean',
            'created_date'   => 'datetime',
            'update_date'    => 'datetime',
        ];
    }

    public function returnRide(): BelongsTo
    {
        return $this->belongsTo(ReturnRide::class, 'return_ride_id', 'id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(DriverUser::class, 'driver_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id');
    }
}
