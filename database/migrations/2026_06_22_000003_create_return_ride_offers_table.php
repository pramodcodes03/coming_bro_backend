<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A driver's fare offer (bid) on a customer's scheduled return ride. Drivers
 * with an active Return Ride recharge submit a price + optional description.
 * The customer reviews competing offers and accepts one; the others are
 * automatically rejected.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_ride_offers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('return_ride_id')->constrained('return_rides')->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('driver_users')->cascadeOnDelete();

            // The driver's bid.
            $table->string('offered_fare');
            $table->text('description')->nullable();

            // Lifecycle: Pending | Accepted | Rejected | Withdrawn.
            $table->string('status')->default('Pending');

            $table->timestamp('created_date')->nullable();
            $table->timestamp('update_date')->nullable();

            $table->index(['return_ride_id', 'status']);
            $table->index('driver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_ride_offers');
    }
};
