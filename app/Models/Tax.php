<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'taxes';

    protected $fillable = [
        'id', 'title', 'type', 'tax', 'country', 'enable',
    ];

    protected $casts = [
        'enable' => 'boolean',
    ];
}
