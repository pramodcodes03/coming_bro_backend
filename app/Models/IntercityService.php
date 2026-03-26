<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntercityService extends Model
{
    protected $table = 'intercity_services';

    protected $fillable = [
        'id', 'name', 'image', 'km_charge',
        'basic_fare_km', 'basic_fare_charges',
        'holding_charge_minute', 'holding_charges',
        'ride_time_fare_per_minute', 'ac_charges',
        'is_ac', 'enable', 'offer_rate', 'admin_commission',
    ];

    protected $casts = [
        'is_ac' => 'boolean',
        'enable' => 'boolean',
        'offer_rate' => 'boolean',
        'admin_commission' => 'array',
    ];
}
