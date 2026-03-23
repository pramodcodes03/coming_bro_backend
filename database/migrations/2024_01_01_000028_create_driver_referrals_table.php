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
        Schema::create('driver_referrals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('referral_code')->nullable();
            $table->integer('referral_users_count')->default(0);
            $table->integer('bonus_rides_remaining')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_referrals');
    }
};
