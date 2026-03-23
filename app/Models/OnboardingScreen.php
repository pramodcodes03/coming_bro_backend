<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingScreen extends Model
{
    protected $table = 'onboarding_screens';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'description',
        'image',
        'type',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
