<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class DriverUser extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'driver_users';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'phone_number',
        'login_type',
        'country_code',
        'profile_pic',
        'document_verification',
        'full_name',
        'is_online',
        'service_id',
        'fcm_token',
        'email',
        'city',
        'state',
        'group_name',
        'complimentary_rides',
        'pin_code',
        'reference_number',
        'reference_name',
        'gender',
        'address',
        'cob_number',
        'remaining_rides',
        'service_type',
        'aadhar_card_number',
        'aadhar_card_photo',
        'pan_card_photo',
        'pan_card_number',
        'is_subscription_enable',
        'reviews_count',
        'reviews_sum',
        'wallet_amount',
        'location_latitude',
        'location_longitude',
        'rotation',
        'position_geohash',
        'position_latitude',
        'position_longitude',
        'subscription_expired_at',
        'zone_ids',
        'vehicle_type',
        'vehicle_type_id',
        'registration_date',
        'vehicle_registration_date',
        'vehicle_color',
        'vehicle_number',
        'company_name',
        'vehicle_model',
        'rc_number',
        'engine_number',
        'registration_type',
        'vehicle_fuel',
        'permit_photo',
        'permit_number',
        'rc_image',
        'chassis_number',
        'district',
        'district_id',
        'ac_perkm_rate',
        'non_ac_perkm_rate',
        'selfie_photo',
        'vehicle_manufacture_date',
        'seats',
        'carrier',
        'driver_rules',
        'agent_code',
        'agent_name',
        'insurance_company',
        'insurance_type',
        'insurance_amount',
        'insurance_expiry_date',
        'insurance_number',
        'insurance_premium',
        'nominee_age',
        'nominee_name',
        'nominee_number',
        'nominee_relation',
        'paid_receipt_number',
        'payment_date',
        'payment_type',
        'subscription_gst_amount',
        'subscription_id',
        'subscription_date',
        'subscription_end_date',
        'subscription_start_date',
        'subscription_amount',
        'subscription_role',
        'subscription_remaining_days',
        'subscription_plan_data',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'zone_ids' => 'array',
            'driver_rules' => 'array',
            'subscription_plan_data' => 'array',
            'document_verification' => 'boolean',
            'is_online' => 'boolean',
            'is_subscription_enable' => 'boolean',
            'carrier' => 'boolean',
            'subscription_expired_at' => 'datetime',
            'registration_date' => 'datetime',
            'subscription_date' => 'datetime',
            'subscription_end_date' => 'datetime',
            'subscription_start_date' => 'datetime',
        ];
    }

    public function bankDetail(): HasOne
    {
        return $this->hasOne(BankDetail::class, 'user_id', 'id');
    }

    public function driverDocument(): HasOne
    {
        return $this->hasOne(DriverDocument::class, 'driver_id', 'id');
    }

    public function driverReferral(): HasOne
    {
        return $this->hasOne(DriverReferral::class, 'driver_id', 'id');
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'user_id', 'id');
    }

    public function withdrawalHistory(): HasMany
    {
        return $this->hasMany(WithdrawalHistory::class, 'user_id', 'id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'driver_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'driver_id', 'id');
    }

    public function intercityOrders(): HasMany
    {
        return $this->hasMany(IntercityOrder::class, 'driver_id', 'id');
    }
}
