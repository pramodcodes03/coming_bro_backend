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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('amount')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('driver_users')->nullOnDelete();
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('note')->nullable();
            $table->string('order_type')->nullable();
            $table->string('user_type')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
