<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A driver's fare offer (bid) on a customer's scheduled Return Ride.
 */
class ReturnRideOffer extends Model
{
    use HasFactory;

    protected $table = 'return_ride_offers';

    public $timestamps = false;

    public const STATUS_PENDING   = 'Pending';
    public const STATUS_ACCEPTED  = 'Accepted';
    public const STATUS_REJECTED  = 'Rejected';
    public const STATUS_WITHDRAWN = 'Withdrawn';

    protected $fillable = [
        'return_ride_id',
        'driver_id',
        'offered_fare',
        'description',
        'status',
        'created_date',
        'update_date',
    ];

    protected function casts(): array
    {
        return [
            'created_date' => 'datetime',
            'update_date'  => 'datetime',
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
}
