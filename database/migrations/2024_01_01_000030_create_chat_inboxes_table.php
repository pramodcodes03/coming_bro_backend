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
        Schema::create('chat_inboxes', function (Blueprint $table) {
            $table->string('order_id')->primary();
            $table->string('customer_id')->default('');
            $table->string('customer_name')->default('');
            $table->string('customer_profile_image')->default('');
            $table->text('last_message')->nullable();
            $table->string('driver_id')->default('');
            $table->string('driver_name')->default('');
            $table->string('driver_profile_image')->default('');
            $table->string('last_sender_id')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_inboxes');
    }
};
