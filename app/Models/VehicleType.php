<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;

    protected $table = 'vehicle_types';

    protected $fillable = [
        'enable',
        'name',
        'front_image',
        'back_image',
        'service_id',
    ];
}
