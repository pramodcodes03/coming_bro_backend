<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleCompany extends Model
{
    use HasFactory;

    protected $table = 'vehicle_companies';

    protected $fillable = [
        'name',
        'enable',
    ];
}
