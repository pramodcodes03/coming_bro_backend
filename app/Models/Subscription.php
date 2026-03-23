<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'description',
        'amount',
        'duration_days',
        'rides_count',
        'enable',
        'is_deleted',
        'features',
    ];

    protected function casts(): array
    {
        return [
            'enable' => 'boolean',
            'is_deleted' => 'boolean',
            'features' => 'array',
        ];
    }
}
