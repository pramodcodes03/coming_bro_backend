<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intercity_services', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('km_charge')->nullable();
            $table->string('basic_fare_km')->nullable();
            $table->string('basic_fare_charges')->nullable();
            $table->string('holding_charge_minute')->nullable();
            $table->string('holding_charges')->nullable();
            $table->string('ride_time_fare_per_minute')->nullable();
            $table->string('ac_charges')->nullable();
            $table->boolean('is_ac')->default(false);
            $table->boolean('enable')->default(true);
            $table->boolean('offer_rate')->default(false);
            $table->json('admin_commission')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intercity_services');
    }
};
