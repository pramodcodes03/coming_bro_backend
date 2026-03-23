<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $table = 'airports';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'airport_name', 'airport_lat', 'airport_lng',
        'city_location', 'state', 'country', 'enable',
    ];

    protected $casts = [
        'enable' => 'boolean',
    ];
}
