<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverDocument extends Model
{
    use HasFactory;

    protected $table = 'driver_documents';

    protected $fillable = [
        'driver_id',
        'documents',
    ];

    protected function casts(): array
    {
        return [
            'documents' => 'array',
        ];
    }
}
