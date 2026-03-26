<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    protected $table = 'subscription_history';

    protected $fillable = [
        'gst_amount',
        'date',
        'end_date',
        'start_date',
        'subscription_amount',
        'subscription_role',
        'remaining_days',
        'subscription_plan',
        'user',
    ];

    protected function casts(): array
    {
        return [
            'subscription_plan' => 'array',
            'user' => 'array',
            'date' => 'datetime',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }
}
