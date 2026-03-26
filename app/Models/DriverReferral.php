<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverReferral extends Model
{
    use HasFactory;

    protected $table = 'driver_referrals';

    protected $fillable = [
        'driver_id',
        'referral_code',
    ];
}
