<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Corrective migration.
 *
 * The Return Ride feature was first shipped as a "driver publishes a route"
 * model (commit 8b3ef8f) and then re-oriented to the "customer posts a request,
 * drivers bid" model (commit 16e04e2). The re-orientation rewrote the original
 * create_return_rides migration in place, but that migration was already
 * recorded as Ran, so the rewritten schema never reached the database — the
 * live `return_rides` table kept the old columns (driver_id, source_*,
 * destination_*, seats_*, fare_per_seat, …). Inserts from ReturnRide therefore
 * failed with "Unknown column 'pickup_location_name'".
 *
 * This migration rebuilds `return_rides` to match the current ReturnRide model
 * and the canonical create_return_rides migration. The old table only ever held
 * throwaway test rows under the incompatible publish schema, so it is dropped
 * and recreated rather than column-by-column transformed.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Other tables (return_ride_offers, and the orphaned return_ride_bookings
        // left over from the old publish schema) carry FK constraints onto
        // return_rides. Disable FK checks for the drop/recreate; the referencing
        // tables hold no live data tied to the old schema.
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('return_rides');

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

            // Route geometry / distance produced by the maps integration.
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

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Irreversible re-shape; just drop the rebuilt table.
        Schema::dropIfExists('return_rides');
    }
};
