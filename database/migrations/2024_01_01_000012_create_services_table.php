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
        Schema::create('services', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('image')->nullable();
            $table->boolean('enable')->default(true);
            $table->boolean('offer_rate')->default(false);
            $table->boolean('intercity_type')->default(false);
            $table->boolean('is_ac_non_ac')->default(false);
            $table->string('ride_time_fare_per_minute')->nullable();
            $table->string('holding_charge_minute')->nullable();
            $table->string('holding_charges')->nullable();
            $table->string('basic_fare_charges')->nullable();
            $table->string('basic_fare_km')->nullable();
            $table->string('title')->nullable();
            $table->string('night_fare_charge')->nullable();
            $table->string('start_night_time')->nullable();
            $table->string('end_night_time')->nullable();
            $table->string('km_charge')->nullable();
            $table->string('non_ac_km_charge')->nullable();
            $table->json('admin_commission')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
