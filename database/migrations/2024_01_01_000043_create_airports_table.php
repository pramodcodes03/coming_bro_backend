<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->string('airport_name')->nullable();
            $table->string('airport_lat')->nullable();
            $table->string('airport_lng')->nullable();
            $table->string('city_location')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->boolean('enable')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airports');
    }
};
