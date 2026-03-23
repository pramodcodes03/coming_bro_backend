<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders_intercity', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('source_city')->nullable();
            $table->string('source_location_name')->nullable();
            $table->string('destination_city')->nullable();
            $table->string('destination_location_name')->nullable();
            $table->string('payment_type')->nullable();
            $table->double('source_latitude')->nullable();
            $table->double('source_longitude')->nullable();
            $table->double('destination_latitude')->nullable();
            $table->double('destination_longitude')->nullable();
            $table->string('intercity_service_id')->nullable();
            $table->string('loading_unloading_charges')->nullable();
            $table->string('user_id')->nullable();
            $table->string('offer_rate')->nullable();
            $table->string('final_rate')->nullable();
            $table->string('ride_hold_time')->nullable();
            $table->string('holding_charges')->nullable();
            $table->string('holding_charge_minute')->nullable();
            $table->string('total_holding_charges')->nullable();
            $table->string('distance')->nullable();
            $table->string('distance_type')->nullable();
            $table->string('status')->nullable();
            $table->boolean('loading')->default(false);
            $table->boolean('unloading')->default(false);
            $table->string('driver_id')->nullable();
            $table->string('parcel_dimension')->nullable();
            $table->string('parcel_weight')->nullable();
            $table->string('freight_weight')->nullable();
            $table->json('parcel_image')->nullable();
            $table->json('accepted_driver_id')->nullable();
            $table->json('rejected_driver_id')->nullable();
            $table->string('position_geohash')->nullable();
            $table->double('position_latitude')->nullable();
            $table->double('position_longitude')->nullable();
            $table->boolean('payment_status')->default(false);
            $table->json('tax_list')->nullable();
            $table->json('coupon')->nullable();
            $table->json('freight_vehicle')->nullable();
            $table->json('intercity_service')->nullable();
            $table->string('when_dates')->nullable();
            $table->string('when_time')->nullable();
            $table->string('number_of_passenger')->nullable();
            $table->text('comments')->nullable();
            $table->string('otp')->nullable();
            $table->json('some_one_else')->nullable();
            $table->json('admin_commission')->nullable();
            $table->json('zone')->nullable();
            $table->string('zone_id')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->timestamp('update_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_intercity');
    }
};
