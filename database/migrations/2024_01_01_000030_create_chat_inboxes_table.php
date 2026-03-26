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
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('customer_name')->default('');
            $table->string('customer_profile_image')->default('');
            $table->text('last_message')->nullable();
            $table->foreignId('driver_id')->nullable()->constrained('driver_users')->nullOnDelete();
            $table->string('driver_name')->default('');
            $table->string('driver_profile_image')->default('');
            $table->unsignedBigInteger('last_sender_id')->nullable();
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
