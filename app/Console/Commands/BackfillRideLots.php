<?php

namespace App\Console\Commands;

use App\Models\DriverUser;
use App\Models\RideLot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * One-time backfill: seed the lot ledger from the existing remaining_rides /
 * total_rides columns so the new FIFO+expiry wallet starts consistent with what
 * drivers already have. Each driver with a balance and no lots gets a single
 * no-expiry "legacy" lot equal to their current remaining_rides.
 */
class BackfillRideLots extends Command
{
    protected $signature = 'rides:backfill-lots {--force : Re-run even for drivers that already have lots}';

    protected $description = 'Seed ride_lots from existing driver remaining_rides/total_rides (one-time migration)';

    public function handle(): int
    {
        $drivers = DriverUser::where(function ($q) {
            $q->where('remaining_rides', '>', 0)->orWhere('total_rides', '>', 0);
        })->get();

        $created = 0;
        $skipped = 0;

        foreach ($drivers as $driver) {
            $hasLots = RideLot::where('driver_id', $driver->id)->exists();
            if ($hasLots && ! $this->option('force')) {
                $skipped++;
                continue;
            }

            $remaining = (int) $driver->remaining_rides;
            if ($remaining <= 0) {
                $skipped++;
                continue;
            }

            DB::transaction(function () use ($driver, $remaining) {
                RideLot::create([
                    'driver_id' => $driver->id,
                    'recharge_plan_id' => null,
                    'wallet_transaction_id' => null,
                    'source' => 'legacy',
                    'rides_total' => $remaining,
                    'rides_remaining' => $remaining,
                    'expires_at' => null, // legacy balance never expires
                ]);
            });

            $created++;
        }

        $this->info("Backfill complete: {$created} legacy lot(s) created, {$skipped} driver(s) skipped.");

        return self::SUCCESS;
    }
}
