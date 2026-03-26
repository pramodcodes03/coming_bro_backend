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
        Schema::create('withdrawal_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('driver_users')->nullOnDelete();
            $table->string('note')->nullable();
            $table->string('admin_note')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('amount')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_history');
    }
};
