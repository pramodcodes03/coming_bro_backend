<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A passenger's booking against a published return ride. Decrements the
 * parent ride's `seats_available` on creation and restores it on cancellation.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_ride_bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('return_ride_id')->constrained('return_rides')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('driver_users')->nullOnDelete();

            // Passenger pickup point (must fall within the driver's corridor).
            $table->string('pickup_location_name')->nullable();
            $table->double('pickup_latitude')->nullable();
            $table->double('pickup_longitude')->nullable();

            // Passenger drop point (towards the driver's destination).
            $table->string('drop_location_name')->nullable();
            $table->double('drop_latitude')->nullable();
            $table->double('drop_longitude')->nullable();

            $table->string('number_of_passenger')->default('1');
            $table->string('fare')->nullable();
            $table->string('final_rate')->nullable();
            $table->string('payment_type')->nullable();
            $table->boolean('payment_status')->default(false);
            $table->string('otp')->nullable();
            $table->text('comments')->nullable();

            // Lifecycle: Pending | Confirmed | Cancelled | Completed.
            $table->string('status')->default('Confirmed');

            $table->timestamp('created_date')->nullable();
            $table->timestamp('update_date')->nullable();

            $table->index(['return_ride_id', 'status']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_ride_bookings');
    }
};
