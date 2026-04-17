<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('driver_users')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sos');
    }
};
