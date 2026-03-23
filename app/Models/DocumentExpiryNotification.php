<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentExpiryNotification extends Model
{
    use HasFactory;

    protected $table = 'document_expiry_notifications';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'driver_id',
        'document_id',
        'document_name',
        'expiry_date',
        'notification_sent_at',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'datetime',
            'notification_sent_at' => 'datetime',
            'is_read' => 'boolean',
        ];
    }
}
