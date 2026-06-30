<?php

namespace App\Console\Commands;

use App\Models\DriverUser;
use App\Models\RideLot;
use App\Services\RideWalletService;
use Illuminate\Console\Command;

class ExpireRideLots extends Command
{
    protected $signature = 'rides:expire-lots';

    protected $description = 'Retire ride lots whose validity has lapsed and remove their unused rides from drivers';

    public function handle(RideWalletService $rideWallet): int
    {
        // Drivers that have at least one lapsed-but-not-yet-retired lot.
        $driverIds = RideLot::lapsed()->distinct()->pluck('driver_id');

        if ($driverIds->isEmpty()) {
            $this->info('No lapsed ride lots to expire.');

            return self::SUCCESS;
        }

        $totalRemoved = 0;
        $affectedDrivers = 0;

        foreach ($driverIds as $driverId) {
            $driver = DriverUser::find($driverId);
            if (! $driver) {
                continue;
            }

            $removed = $rideWallet->expireLapsedLots($driver);
            if ($removed > 0) {
                $affectedDrivers++;
                $totalRemoved += $removed;

                // If the driver is now out of rides, take them offline so the
                // matching engine stops sending requests until they recharge.
                $driver->refresh();
                if ((int) $driver->remaining_rides <= 0 && $driver->is_online) {
                    $driver->is_online = false;
                    $driver->saveQuietly();
                }
            }
        }

        $this->info("Expired ride lots for {$affectedDrivers} driver(s); removed {$totalRemoved} unused ride(s).");

        return self::SUCCESS;
    }
}
