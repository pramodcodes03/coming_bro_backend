<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateLocation extends Model
{
    use HasFactory;

    protected $table = 'states';

    protected $fillable = [
        'name',
        'is_active',
    ];
}
