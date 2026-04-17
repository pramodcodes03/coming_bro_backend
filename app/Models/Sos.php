<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sos extends Model
{
    protected $table = 'sos';

    protected $fillable = [
        'user_id',
        'driver_id',
        'order_id',
        'latitude',
        'longitude',
    ];
}
