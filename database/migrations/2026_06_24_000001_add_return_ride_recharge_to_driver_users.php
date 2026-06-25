<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Dedicated expiry timestamp for the Return Ride recharge. Kept separate from
 * the shared `subscription_*` fields so Return Ride gating never interferes
 * with the existing subscription logic. A non-null value in the future means
 * the driver currently holds an active Return Ride recharge.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('driver_users', function (Blueprint $table) {
            $table->timestamp('return_ride_recharge_expires_at')->nullable()->after('subscription_expired_at');
        });
    }

    public function down(): void
    {
        Schema::table('driver_users', function (Blueprint $table) {
            $table->dropColumn('return_ride_recharge_expires_at');
        });
    }
};
