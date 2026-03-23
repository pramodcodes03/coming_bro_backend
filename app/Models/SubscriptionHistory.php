<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    protected $table = 'subscription_history';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'driver_id',
        'subscription_id',
        'subscription_data',
        'amount',
        'payment_type',
        'transaction_id',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'subscription_data' => 'array',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }
}
