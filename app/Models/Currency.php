<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';

    protected $fillable = [
        'symbol',
        'code',
        'enable',
        'symbol_at_right',
        'name',
        'decimal_digits',
    ];
}
