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
        Schema::create('subscription_history', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('gst_amount')->nullable();
            $table->timestamp('date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->string('subscription_amount')->nullable();
            $table->string('subscription_role')->nullable();
            $table->string('remaining_days')->nullable();
            $table->json('subscription_plan')->nullable();
            $table->json('user')->nullable(); // DriverUserModelData subset
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_history');
    }
};
