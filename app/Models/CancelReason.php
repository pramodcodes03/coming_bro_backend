<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancelReason extends Model
{
    protected $fillable = [
        'reason',
        'applies_to',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /** Active reasons for a given audience ('customer' | 'driver'), ordered. */
    public function scopeForAudience($query, string $audience)
    {
        return $query->where('is_active', true)
            ->whereIn('applies_to', [$audience, 'both'])
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
