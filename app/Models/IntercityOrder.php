<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntercityOrder extends Model
{
    use HasFactory;

    protected $table = 'orders_intercity';

    public $timestamps = false;

    protected $fillable = [
        'source_city',
        'source_location_name',
        'destination_city',
        'destination_location_name',
        'payment_type',
        'source_latitude',
        'source_longitude',
        'destination_latitude',
        'destination_longitude',
        'intercity_service_id',
        'loading_unloading_charges',
        'user_id',
        'offer_rate',
        'final_rate',
        'ride_hold_time',
        'holding_charges',
        'holding_charge_minute',
        'total_holding_charges',
        'distance',
        'distance_type',
        'status',
        'loading',
        'unloading',
        'driver_id',
        'parcel_dimension',
        'parcel_weight',
        'freight_weight',
        'parcel_image',
        'accepted_driver_id',
        'rejected_driver_id',
        'position_geohash',
        'position_latitude',
        'position_longitude',
        'payment_status',
        'tax_list',
        'coupon',
        'freight_vehicle',
        'intercity_service',
        'when_dates',
        'when_time',
        'number_of_passenger',
        'comments',
        'otp',
        'some_one_else',
        'admin_commission',
        'zone',
        'zone_id',
        'created_date',
        'update_date',
    ];

    protected function casts(): array
    {
        return [
            'parcel_image' => 'array',
            'accepted_driver_id' => 'array',
            'rejected_driver_id' => 'array',
            'tax_list' => 'array',
            'coupon' => 'array',
            'freight_vehicle' => 'array',
            'intercity_service' => 'array',
            'some_one_else' => 'array',
            'admin_commission' => 'array',
            'zone' => 'array',
            'loading' => 'boolean',
            'unloading' => 'boolean',
            'payment_status' => 'boolean',
            'created_date' => 'datetime',
            'update_date' => 'datetime',
        ];
    }
}
