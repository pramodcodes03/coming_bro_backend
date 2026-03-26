<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'title',
        'back_side',
        'enable',
        'expire_at',
        'front_side',
        'is_deleted',
    ];

    protected function casts(): array
    {
        return [
            'back_side' => 'boolean',
            'enable' => 'boolean',
            'expire_at' => 'boolean',
            'front_side' => 'boolean',
            'is_deleted' => 'boolean',
        ];
    }
}
