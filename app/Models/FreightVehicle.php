<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreightVehicle extends Model
{
    protected $table = 'freight_vehicles';

    protected $fillable = [
        'id', 'name', 'image', 'description',
        'length', 'width', 'height', 'weight',
        'km_charge', 'basic_fare_km', 'basic_fare_charges',
        'holding_charge_minute', 'holding_charges',
        'loading_unloading_charges', 'enable',
    ];

    protected $casts = [
        'enable' => 'boolean',
    ];
}
