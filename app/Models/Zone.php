<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $table = 'zones';

    protected $fillable = [
        'name',
        'area',
        'enable',
    ];

    protected function casts(): array
    {
        return [
            'area' => 'array',
        ];
    }
}
