<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionHistoryRecord extends Model
{
    use HasFactory;

    protected $table = 'subscription_history';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'driver_id',
        'subscription_plan',
        'user',
        'payment_method',
        'transaction_id',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'subscription_plan' => 'array',
            'user' => 'array',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }
}
