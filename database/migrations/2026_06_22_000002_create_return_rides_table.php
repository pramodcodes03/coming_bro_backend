<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Return Ride feature — a driver publishes the journey back from a destination
 * so passengers travelling along the same corridor can book a seat within a
 * defined pickup time window.
 *
 * Mirrors the column conventions used by `orders` / `orders_intercity`
 * (double lat/lng pairs, JSON arrays, custom created_date / update_date
 * timestamps) so the rest of the platform stays consistent.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_rides', function (Blueprint $table) {
            $table->id();

            // Owner (the publishing driver).
            $table->foreignId('driver_id')->nullable()->constrained('driver_users')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();

            // "Return From" location — where the driver starts the journey back.
            $table->string('source_location_name')->nullable();
            $table->double('source_latitude')->nullable();
            $table->double('source_longitude')->nullable();

            // "Returning Towards" destination.
            $table->string('destination_location_name')->nullable();
            $table->double('destination_latitude')->nullable();
            $table->double('destination_longitude')->nullable();

            // Route geometry produced by the maps integration.
            $table->longText('route_polyline')->nullable();         // encoded polyline
            $table->json('route_coordinates')->nullable();          // [{lat,lng}, ...]
            $table->string('distance')->nullable();                 // numeric string (km)
            $table->string('distance_type')->default('Km');
            $table->string('duration')->nullable();                 // human readable ETA

            // Scheduling + pickup window.
            $table->timestamp('departure_time')->nullable();        // arrival/start time
            $table->unsignedTinyInteger('pickup_window_hours')->default(1); // 1 or 2
            $table->timestamp('pickup_window_start')->nullable();
            $table->timestamp('pickup_window_end')->nullable();

            // Capacity + pricing (optional fare per seat).
            $table->unsignedTinyInteger('seats_total')->default(4);
            $table->unsignedTinyInteger('seats_available')->default(4);
            $table->string('fare_per_seat')->nullable();
            $table->string('offer_rate')->nullable();

            // Live driver position (for on-route discovery).
            $table->string('position_geohash')->nullable();
            $table->double('position_latitude')->nullable();
            $table->double('position_longitude')->nullable();

            // Zone metadata (kept consistent with intercity orders).
            $table->json('zone')->nullable();
            $table->foreignId('zone_id')->nullable()->constrained('zones')->nullOnDelete();

            // Lifecycle: Active | Completed | Cancelled | Expired.
            $table->string('status')->default('Active');
            $table->text('comments')->nullable();

            $table->timestamp('created_date')->nullable();
            $table->timestamp('update_date')->nullable();

            // Indexes for fast geolocation + time-window discovery.
            $table->index(['status', 'departure_time']);
            $table->index(['source_latitude', 'source_longitude']);
            $table->index('driver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_rides');
    }
};
