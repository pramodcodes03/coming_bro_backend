<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('title')->nullable();
            $table->string('code')->nullable();
            $table->string('amount')->nullable();
            $table->string('type')->nullable(); // 'fix' or 'percentage'
            $table->boolean('enable')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_public')->default(true);
            $table->timestamp('validity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
