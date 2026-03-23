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
        Schema::create('driver_rules', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('image')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->boolean('enable')->default(true);
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_rules');
    }
};
