<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'image',
        'enable',
        'offer_rate',
        'intercity_type',
        'is_ac_non_ac',
        'ride_time_fare_per_minute',
        'holding_charge_minute',
        'holding_charges',
        'basic_fare_charges',
        'basic_fare_km',
        'title',
        'night_fare_charge',
        'start_night_time',
        'end_night_time',
        'km_charge',
        'non_ac_km_charge',
        'admin_commission',
    ];

    protected function casts(): array
    {
        return [
            'admin_commission' => 'array',
            'enable' => 'boolean',
            'offer_rate' => 'boolean',
            'intercity_type' => 'boolean',
            'is_ac_non_ac' => 'boolean',
        ];
    }
}
