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
        Schema::create('customers', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('login_type')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('reviews_count')->default('0.0');
            $table->string('reviews_sum')->default('0.0');
            $table->string('wallet_amount')->default('0');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
