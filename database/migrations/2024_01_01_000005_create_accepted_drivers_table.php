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
        Schema::create('accepted_drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('order_type')->default('city'); // 'city' or 'intercity'
            $table->foreignId('driver_id')->constrained('driver_users')->cascadeOnDelete();
            $table->string('offer_amount')->nullable();
            $table->timestamp('accepted_reject_time')->nullable();
            $table->string('suggested_time')->nullable();
            $table->string('suggested_date')->nullable();
            $table->timestamps();
            $table->index(['order_id', 'order_type']);
            $table->index(['order_id', 'driver_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accepted_drivers');
    }
};
