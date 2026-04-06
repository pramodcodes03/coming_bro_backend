<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';

    protected $fillable = [
        'id', 'title', 'description', 'image', 'redirect_url', 'position', 'enable', 'is_deleted',
    ];

    protected $casts = [
        'enable' => 'boolean',
        'is_deleted' => 'boolean',
    ];
}
