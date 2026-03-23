<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverReferral extends Model
{
    use HasFactory;

    protected $table = 'driver_referrals';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'driver_id',
        'referral_code',
    ];
}
