<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tag orders that were spawned from an accepted Return Ride bid.
 *
 * When a customer accepts a driver's offer on a `return_rides` request, a normal
 * `orders` row is created so it runs through the standard execution flow. Until
 * now that order carried no marker, so it was indistinguishable from a regular
 * city ride. This nullable FK lets admin + the apps identify return-ride orders
 * (NULL = ordinary ride).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('return_ride_id')->nullable()->after('service_id');
            $table->index('return_ride_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['return_ride_id']);
            $table->dropColumn('return_ride_id');
        });
    }
};
