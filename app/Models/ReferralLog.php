<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralLog extends Model
{
    protected $table = 'referral_logs';

    protected $fillable = [
        'driver_id',
        'user_id',
        'referral_code',
        'scanned_at',
    ];
}
