<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'comment',
        'rating',
        'customer_id',
        'driver_id',
        'type',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }
}
