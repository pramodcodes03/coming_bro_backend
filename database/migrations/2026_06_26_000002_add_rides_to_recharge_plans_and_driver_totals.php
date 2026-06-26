<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ride-quota recharge model.
 *
 * A recharge plan grants a fixed number of rides (`recharge_plans.rides`). On a
 * successful recharge the driver's `remaining_rides` is incremented by that many
 * and `total_rides` (lifetime granted) tracks the denominator so the app can
 * show "used / total" (e.g. 5/10, and 5/20 after a second recharge of the same
 * plan). `remaining_rides` already exists; this adds the plan quota and the
 * driver's running total.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recharge_plans', function (Blueprint $table) {
            // How many rides this plan grants on purchase (0 = wallet-only plan).
            $table->unsignedInteger('rides')->default(0)->after('price');
        });

        Schema::table('driver_users', function (Blueprint $table) {
            // Lifetime rides granted across all recharges — the "total" the app
            // shows the remaining count against (remaining / total).
            $table->unsignedInteger('total_rides')->default(0)->after('remaining_rides');
        });
    }

    public function down(): void
    {
        Schema::table('recharge_plans', function (Blueprint $table) {
            $table->dropColumn('rides');
        });

        Schema::table('driver_users', function (Blueprint $table) {
            $table->dropColumn('total_rides');
        });
    }
};
