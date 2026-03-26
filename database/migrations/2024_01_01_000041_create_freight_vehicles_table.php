<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freight_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('km_charge')->nullable();
            $table->string('basic_fare_km')->nullable();
            $table->string('basic_fare_charges')->nullable();
            $table->string('holding_charge_minute')->nullable();
            $table->string('holding_charges')->nullable();
            $table->string('loading_unloading_charges')->nullable();
            $table->boolean('enable')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freight_vehicles');
    }
};
