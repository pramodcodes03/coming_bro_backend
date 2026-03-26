<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateSetting extends Model
{
    use HasFactory;

    protected $table = 'rate_settings';

    protected $fillable = [
        'name',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }
}
