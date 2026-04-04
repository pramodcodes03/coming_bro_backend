<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargePlan extends Model
{
    protected $fillable = [
        'label',
        'price',
        'original_price',
        'discount_pct',
        'is_best_value',
        'is_active',
        'sort_order',
        'terms_title',
        'terms_footer',
        'benefits',
        'terms_points',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'discount_pct' => 'integer',
            'is_best_value' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'benefits' => 'array',
            'terms_points' => 'array',
        ];
    }
}
