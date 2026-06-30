<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Free rides granted by an admin reuse the same ride wallet (remaining_rides
     * / total_rides) and are recorded as wallet_transactions with
     * order_type='free_ride'. We add:
     *  - driver_users.free_rides_total: a running tally of free rides ever
     *    granted to the driver (display/audit only; the usable balance still
     *    lives in remaining_rides).
     *  - wallet_transactions.granted_by_admin_id: which admin granted a free
     *    ride, so the grant can be attributed in history.
     */
    public function up(): void
    {
        Schema::table('driver_users', function (Blueprint $table) {
            $table->integer('free_rides_total')->default(0)->after('total_rides');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('granted_by_admin_id')->nullable()->after('user_type');
        });
    }

    public function down(): void
    {
        Schema::table('driver_users', function (Blueprint $table) {
            $table->dropColumn('free_rides_total');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn('granted_by_admin_id');
        });
    }
};
