<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'source_location_name',
        'destination_location_name',
        'payment_type',
        'source_latitude',
        'source_longitude',
        'destination_latitude',
        'destination_longitude',
        'service_id',
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
            'created_date' => 'datetime',
            'update_date' => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(DriverUser::class, 'driver_id', 'id');
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
