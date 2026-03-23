<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalHistory extends Model
{
    use HasFactory;

    protected $table = 'withdrawal_history';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'note',
        'admin_note',
        'payment_status',
        'amount',
        'payment_date',
        'created_date',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'datetime',
            'created_date' => 'datetime',
        ];
    }
}
