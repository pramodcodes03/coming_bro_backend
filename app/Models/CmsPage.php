<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    protected $table = 'cms_pages';

    protected $fillable = [
        'id', 'name', 'slug', 'description', 'publish',
    ];

    protected $casts = [
        'publish' => 'boolean',
    ];
}
