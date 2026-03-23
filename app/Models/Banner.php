<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'image', 'position', 'enable', 'is_deleted',
    ];

    protected $casts = [
        'enable' => 'boolean',
        'is_deleted' => 'boolean',
    ];
}
