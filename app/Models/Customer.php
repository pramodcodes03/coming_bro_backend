<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'customers';

    protected $fillable = [
        'full_name',
        'email',
        'login_type',
        'profile_pic',
        'fcm_token',
        'country_code',
        'phone_number',
        'reviews_count',
        'reviews_sum',
        'wallet_amount',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
