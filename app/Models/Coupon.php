<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'title', 'code', 'amount', 'type',
        'enable', 'is_deleted', 'is_public', 'validity',
    ];

    protected $casts = [
        'enable' => 'boolean',
        'is_deleted' => 'boolean',
        'is_public' => 'boolean',
        'validity' => 'datetime',
    ];
}
