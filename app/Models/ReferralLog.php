<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralLog extends Model
{
    use HasFactory;

    protected $table = 'referral_logs';

    protected $fillable = [
        'referrer_id',
        'referred_id',
        'referral_code',
        'amount',
        'status',
    ];
}
