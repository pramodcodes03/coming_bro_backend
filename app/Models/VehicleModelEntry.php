<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModelEntry extends Model
{
    use HasFactory;

    protected $table = 'vehicle_models';

    protected $fillable = [
        'company_id',
        'name',
        'enable',
    ];
}
