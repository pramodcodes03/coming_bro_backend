<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverRule extends Model
{
    use HasFactory;

    protected $table = 'driver_rules';

    protected $fillable = [
        'name',
        'enable',
    ];
}
