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
        Schema::create('languages', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('image')->default('');
            $table->string('code')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->boolean('enable')->default(true);
            $table->string('name')->nullable();
            $table->boolean('is_rtl')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
