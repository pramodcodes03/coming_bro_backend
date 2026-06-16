<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';

    protected $fillable = [
        'order_id',
        'sender_id',
        'sender_type',
        'receiver_id',
        'message',
        'type',
        'message_type',
        'url_url',
        'url_mime',
        'url_video_thumbnail',
        'video_thumbnail',
    ];
}
