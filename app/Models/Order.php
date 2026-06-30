<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    /**
     * Canonical status string for a freshly placed ride. This is the single
     * source of truth shared by the customer app, driver app and backend
     * queries — do not hardcode the literal string anywhere else.
     */
    public const STATUS_RIDE_PLACED = 'Ride Placed';

    /** Canonical status when a ride is cancelled (matches the cancelled bucket). */
    public const STATUS_RIDE_CANCELED = 'Ride Canceled';

    public $timestamps = false;

    /**
     * One fare everywhere: the customer's offered fare (`offer_rate`) is the
     * single source of truth — what the customer sees & agrees to is what the
     * driver sees/earns and what payment charges. We keep `offer_rate` and
     * `final_rate` in sync on create/update via this helper. Falls back to
     * `final_rate` when no offer was sent. Returns null if neither is numeric.
     */
    public static function singleFare($offer, $final): ?float
    {
        $offer = is_numeric($offer) ? (float) $offer : null;
        $final = is_numeric($final) ? (float) $final : null;

        return $offer ?? $final;
    }

    /**
     * Expose return-ride flags in every JSON response (apps + admin) without
     * each endpoint having to compute them.
     */
    protected $appends = [
        'is_return_ride',
        'ride_type',
    ];

    protected $fillable = [
        'source_location_name',
        'destination_location_name',
        'payment_type',
        'source_latitude',
        'source_longitude',
        'destination_latitude',
        'destination_longitude',
        'service_id',
        'return_ride_id',
        'user_id',
        'offer_rate',
        'final_rate',
        'distance',
        'duration',
        'distance_type',
        'ride_hold_time',
        'holding_charge_minute',
        'total_holding_charges',
        'holding_charges',
        'status',
        'driver_id',
        'ride_time_fare_per_minute',
        'total_ride_time',
        'ac_non_ac_charges',
        'otp',
        'accepted_driver_id',
        'rejected_driver_id',
        'position_geohash',
        'position_latitude',
        'position_longitude',
        'payment_status',
        'is_ac_selected',
        'tax_list',
        'some_one_else',
        'coupon',
        'service',
        'admin_commission',
        'zone',
        'zone_id',
        'cancel_reason',
        'cancelled_by',
        'cancelled_at',
        'created_date',
        'update_date',
    ];

    protected function casts(): array
    {
        return [
            'accepted_driver_id' => 'array',
            'rejected_driver_id' => 'array',
            'tax_list' => 'array',
            'some_one_else' => 'array',
            'coupon' => 'array',
            'service' => 'array',
            'admin_commission' => 'array',
            'zone' => 'array',
            'payment_status' => 'boolean',
            'is_ac_selected' => 'boolean',
            'cancelled_at' => 'datetime',
            'created_date' => 'datetime',
            'update_date' => 'datetime',
        ];
    }

    /**
     * True when this order was spawned from an accepted Return Ride bid.
     * Convenience flag for the apps' "My Rides" list.
     */
    protected function isReturnRide(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->return_ride_id !== null,
        );
    }

    /**
     * Machine-readable ride origin: 'return_ride' for return-ride orders,
     * 'city' for ordinary rides. Handy for badges / filtering in the apps.
     */
    protected function rideType(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->return_ride_id !== null ? 'return_ride' : 'city',
        );
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(DriverUser::class, 'driver_id', 'id');
    }

    public function returnRide(): BelongsTo
    {
        return $this->belongsTo(ReturnRide::class, 'return_ride_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id');
    }

    public function acceptedDrivers(): HasMany
    {
        return $this->hasMany(AcceptedDriver::class, 'order_id', 'id');
    }
}
