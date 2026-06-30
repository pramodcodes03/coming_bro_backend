<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Batch/lot-based ride wallet with FIFO consumption and per-lot expiry.
     *
     * Each recharge (paid or free) creates one `ride_lots` row holding that
     * batch's rides and its own expiry. Rides are consumed oldest-lot-first.
     * The driver's remaining_rides / total_rides columns are kept as a CACHED
     * sum of the active (non-expired) lots, so all existing gating code that
     * reads remaining_rides keeps working unchanged.
     *
     * Plans gain `validity_days`: how long a recharge's rides stay valid.
     * NULL or 0 means the rides never expire.
     */
    public function up(): void
    {
        Schema::table('recharge_plans', function (Blueprint $table) {
            // Days the granted rides remain valid after purchase. NULL / 0 = no expiry.
            $table->unsignedInteger('validity_days')->nullable()->after('rides');
        });

        Schema::create('ride_lots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id')->index();
            $table->unsignedBigInteger('recharge_plan_id')->nullable();
            $table->unsignedBigInteger('wallet_transaction_id')->nullable();

            // Where these rides came from: recharge | return_recharge | free
            $table->string('source', 30)->default('recharge');

            $table->unsignedInteger('rides_total');       // rides granted by this lot
            $table->unsignedInteger('rides_remaining');   // rides left in this lot (FIFO)

            $table->timestamp('expires_at')->nullable();  // NULL = never expires
            $table->boolean('is_expired')->default(false);
            $table->timestamp('expired_at')->nullable();  // when the lot was retired

            $table->timestamps();

            // Hot path: pick a driver's active lots, oldest first.
            $table->index(['driver_id', 'is_expired', 'rides_remaining'], 'ride_lots_active_idx');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_lots');

        Schema::table('recharge_plans', function (Blueprint $table) {
            $table->dropColumn('validity_days');
        });
    }
};
