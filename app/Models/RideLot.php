<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One batch of rides granted by a single recharge (paid, return-ride, or free).
 * Rides are consumed oldest-lot-first (FIFO). A lot can carry its own expiry;
 * when it lapses, its remaining rides are removed from the driver's wallet.
 */
class RideLot extends Model
{
    protected $fillable = [
        'driver_id',
        'recharge_plan_id',
        'wallet_transaction_id',
        'source',
        'rides_total',
        'rides_remaining',
        'expires_at',
        'is_expired',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'rides_total' => 'integer',
            'rides_remaining' => 'integer',
            'expires_at' => 'datetime',
            'is_expired' => 'boolean',
            'expired_at' => 'datetime',
        ];
    }

    /** Active lots: not expired, still have rides, and not past their expiry. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_expired', false)
            ->where('rides_remaining', '>', 0)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    /** Lots whose expiry has passed but haven't been retired yet. */
    public function scopeLapsed(Builder $query): Builder
    {
        return $query->where('is_expired', false)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(DriverUser::class, 'driver_id');
    }

    public function rechargePlan(): BelongsTo
    {
        return $this->belongsTo(RechargePlan::class, 'recharge_plan_id');
    }

    public function walletTransaction(): BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class, 'wallet_transaction_id');
    }
}
