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
            'created_date' => 'datetime',
        ];
    }
}
