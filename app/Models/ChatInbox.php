<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatInbox extends Model
{
    use HasFactory;

    protected $table = 'chat_inboxes';

    protected $fillable = [
        'order_id',
        'driver_id',
        'customer_id',
        'driver_name',
        'driver_profile_image',
        'customer_name',
        'customer_profile_image',
        'last_message',
        'last_sender_id',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }
}
