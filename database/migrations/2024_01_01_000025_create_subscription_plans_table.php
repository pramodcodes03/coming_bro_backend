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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('amount')->default('');
            $table->text('description')->default('');
            $table->string('duration')->default('');
            $table->boolean('enable')->default(false);
            $table->string('gst')->default('');
            $table->string('name')->default('');
            $table->string('tds')->default('');
            $table->string('ride')->default('');
            $table->string('image')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
