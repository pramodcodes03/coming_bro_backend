<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcceptedDriver extends Model
{
    use HasFactory;

    protected $table = 'accepted_drivers';

    protected $fillable = [
        'order_id',
        'order_type',
        'driver_id',
        'offer_amount',
        'accepted_reject_time',
        'suggested_time',
        'suggested_date',
    ];

    protected function casts(): array
    {
        return [
            'accepted_reject_time' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(DriverUser::class, 'driver_id', 'id');
    }
}
