<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'wallet_transactions';

    protected $fillable = [
        'amount',
        'user_id',
        'recharge_plan_id',
        'base_amount',
        'gst_percent',
        'gst_amount',
        'total_amount',
        'transaction_id',
        'payment_type',
        'note',
        'order_type',
        'user_type',
        'created_date',
    ];

    protected function casts(): array
    {
        return [
            'base_amount' => 'decimal:2',
            'gst_percent' => 'decimal:2',
            'gst_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'created_date' => 'datetime',
        ];
    }

    public function rechargePlan()
    {
        return $this->belongsTo(RechargePlan::class, 'recharge_plan_id');
    }
}
