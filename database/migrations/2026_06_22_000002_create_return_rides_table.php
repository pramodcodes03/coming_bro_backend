<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Return Ride feature — a CUSTOMER posts a scheduled ride requirement (pickup,
 * drop, passengers, date & time). Subscribed drivers then submit fare offers
 * (see `return_ride_offers`). The customer reviews offers and accepts one, at
 * which point the ride is handed off to the normal `orders` execution flow
 * (the accepted offer spawns an Order referenced via `order_id`).
 *
 * Mirrors the column conventions used by `orders` (double lat/lng pairs, JSON
 * arrays, custom created_date / update_date timestamps) so the rest of the
 * platform stays consistent.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_rides', function (Blueprint $table) {
            $table->id();

            // Owner (the requesting customer).
            $table->foreignId('user_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();

            // Pickup — where the customer wants to be picked up.
            $table->string('pickup_location_name')->nullable();
            $table->double('pickup_latitude')->nullable();
            $table->double('pickup_longitude')->nullable();

            // Drop — the customer's destination.
            $table->string('drop_location_name')->nullable();
            $table->double('drop_latitude')->nullable();
            $table->double('drop_longitude')->nullable();

            // Trip details.
            $table->unsignedTinyInteger('passengers')->default(1);
            $table->timestamp('scheduled_at')->nullable();      // requested date + time
            $table->string('payment_type')->default('cash');

            // Route geometry / distance produced by the maps integration (used to
            // show drivers the trip distance and draw the route).
            $table->longText('route_polyline')->nullable();      // encoded polyline
            $table->json('route_coordinates')->nullable();       // [{lat,lng}, ...]
            $table->string('distance')->nullable();              // numeric string (km)
            $table->string('distance_type')->default('Km');
            $table->string('duration')->nullable();              // human readable ETA

            // Zone metadata (kept consistent with the orders table).
            $table->json('zone')->nullable();
            $table->foreignId('zone_id')->nullable()->constrained('zones')->nullOnDelete();

            // Lifecycle: Scheduled | Accepted | Completed | Cancelled | Expired.
            $table->string('status')->default('Scheduled');
            $table->text('comments')->nullable();

            // Set once the customer accepts a driver's offer (handoff to orders).
            $table->foreignId('accepted_offer_id')->nullable();
            $table->foreignId('assigned_driver_id')->nullable()->constrained('driver_users')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();

            $table->timestamp('created_date')->nullable();
            $table->timestamp('update_date')->nullable();

            // Indexes for fast discovery + ownership lookups.
            $table->index(['status', 'scheduled_at']);
            $table->index(['pickup_latitude', 'pickup_longitude']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_rides');
    }
};
