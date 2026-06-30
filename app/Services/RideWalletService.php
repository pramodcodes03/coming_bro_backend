<?php

namespace App\Services;

use App\Models\DriverUser;
use App\Models\RechargePlan;
use App\Models\RideLot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Batch/lot-based ride wallet.
 *
 * Every recharge (paid, return-ride, or admin free grant) creates one ride_lots
 * row holding that batch's rides and its own expiry. Rides are consumed
 * oldest-lot-first (FIFO). A lot can expire after N days; on expiry its unused
 * rides are removed from the wallet.
 *
 * driver_users.remaining_rides / total_rides are kept as a CACHED sum of the
 * active (non-expired) lots, so every existing caller that reads remaining_rides
 * (hasRideBalance, nearby, accept, dashboard, return-ride gating) keeps working
 * without changes.
 */
class RideWalletService
{
    /**
     * Credit a batch of rides to a driver and create its lot.
     *
     * @param  int|null  $validityDays  Days the rides stay valid. null/0 = no expiry.
     * @return RideLot|null  The created lot, or null if $rides <= 0.
     */
    public function credit(
        DriverUser $driver,
        int $rides,
        string $source = 'recharge',
        ?int $rechargePlanId = null,
        ?int $walletTransactionId = null,
        ?int $validityDays = null,
    ): ?RideLot {
        if ($rides <= 0) {
            return null;
        }

        $expiresAt = ($validityDays && $validityDays > 0)
            ? now()->addDays($validityDays)
            : null;

        return DB::transaction(function () use ($driver, $rides, $source, $rechargePlanId, $walletTransactionId, $expiresAt) {
            $lot = RideLot::create([
                'driver_id' => $driver->id,
                'recharge_plan_id' => $rechargePlanId,
                'wallet_transaction_id' => $walletTransactionId,
                'source' => $source,
                'rides_total' => $rides,
                'rides_remaining' => $rides,
                'expires_at' => $expiresAt,
            ]);

            $this->syncDriverCache($driver);

            Log::info('[RIDE_LOT] credited', [
                'driver_id' => $driver->id,
                'lot_id' => $lot->id,
                'rides' => $rides,
                'source' => $source,
                'expires_at' => optional($expiresAt)->toDateTimeString(),
                'remaining_rides' => (int) $driver->remaining_rides,
            ]);

            return $lot;
        });
    }

    /**
     * Convenience: credit rides from a recharge plan, taking the plan's rides
     * count and validity. Pass an explicit $rides to override the plan count
     * (e.g. an admin free grant that references no plan).
     */
    public function creditFromPlan(
        DriverUser $driver,
        ?RechargePlan $plan,
        string $source = 'recharge',
        ?int $walletTransactionId = null,
        ?int $ridesOverride = null,
        ?int $validityDaysOverride = null,
    ): ?RideLot {
        $rides = $ridesOverride ?? (int) ($plan?->rides ?? 0);
        $validity = $validityDaysOverride ?? ($plan?->validity_days !== null ? (int) $plan->validity_days : null);

        return $this->credit(
            driver: $driver,
            rides: $rides,
            source: $source,
            rechargePlanId: $plan?->id,
            walletTransactionId: $walletTransactionId,
            validityDays: $validity,
        );
    }

    /**
     * Consume $count rides from the driver's active lots, oldest first (FIFO).
     * Retires lapsed lots first so an expired lot is never consumed from.
     * Never goes below zero; returns the number actually consumed.
     */
    public function consume(DriverUser $driver, int $count = 1): int
    {
        if ($count <= 0) {
            return 0;
        }

        return DB::transaction(function () use ($driver, $count) {
            // Drop any lapsed lots before consuming so we only spend valid rides.
            $this->expireLapsedLots($driver);

            $remaining = $count;

            $lots = RideLot::active()
                ->where('driver_id', $driver->id)
                ->orderBy('created_at')   // FIFO: oldest purchased first
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            foreach ($lots as $lot) {
                if ($remaining <= 0) {
                    break;
                }
                $take = min($lot->rides_remaining, $remaining);
                $lot->rides_remaining -= $take;
                $lot->save();
                $remaining -= $take;
            }

            $consumed = $count - $remaining;

            $this->syncDriverCache($driver);

            Log::info('[RIDE_LOT] consumed', [
                'driver_id' => $driver->id,
                'requested' => $count,
                'consumed' => $consumed,
                'remaining_rides' => (int) $driver->remaining_rides,
            ]);

            return $consumed;
        });
    }

    /**
     * Retire lapsed lots for one driver: mark expired and zero their unused
     * rides. Returns the number of rides removed. Safe to call lazily on reads.
     */
    public function expireLapsedLots(DriverUser $driver): int
    {
        $lapsed = RideLot::lapsed()->where('driver_id', $driver->id)->get();
        if ($lapsed->isEmpty()) {
            return 0;
        }

        $removed = 0;
        foreach ($lapsed as $lot) {
            $removed += (int) $lot->rides_remaining;
            $lot->rides_remaining = 0;
            $lot->is_expired = true;
            $lot->expired_at = now();
            $lot->save();
        }

        $this->syncDriverCache($driver);

        if ($removed > 0) {
            Log::info('[RIDE_LOT] expired lots removed', [
                'driver_id' => $driver->id,
                'lots' => $lapsed->pluck('id')->all(),
                'rides_removed' => $removed,
                'remaining_rides' => (int) $driver->remaining_rides,
            ]);
        }

        return $removed;
    }

    /**
     * Active (non-expired, ride-bearing) lots for a driver, oldest first.
     * Lazily retires lapsed lots so the result is always current.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, RideLot>
     */
    public function activeLots(DriverUser $driver)
    {
        $this->expireLapsedLots($driver);

        return RideLot::active()
            ->where('driver_id', $driver->id)
            ->with('rechargePlan:id,label,price,original_price,gst_percent')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();
    }

    /**
     * Recompute the driver's cached ride counters from the lot ledger.
     *  - remaining_rides = sum of active lots' rides_remaining
     *  - total_rides     = lifetime rides ever granted across all lots
     * Persisted with saveQuietly so model events / observers don't refire.
     */
    public function syncDriverCache(DriverUser $driver): void
    {
        $remaining = (int) RideLot::active()
            ->where('driver_id', $driver->id)
            ->sum('rides_remaining');

        $total = (int) RideLot::where('driver_id', $driver->id)->sum('rides_total');

        $driver->remaining_rides = $remaining;
        $driver->total_rides = $total;
        $driver->saveQuietly();
    }
}
