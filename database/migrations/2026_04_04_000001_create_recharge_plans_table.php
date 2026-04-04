<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recharge_plans', function (Blueprint $table) {
            $table->id();
            $table->string('label');                    // "Daily Ride (Regular)"
            $table->decimal('price', 10, 2);            // 49.00
            $table->decimal('original_price', 10, 2);   // 99.00
            $table->integer('discount_pct')->default(0); // 50
            $table->boolean('is_best_value')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('terms_title')->nullable();
            $table->text('terms_footer')->nullable();
            $table->json('benefits');                    // [{icon, title, subtitle}]
            $table->json('terms_points');                // ["point1", "point2", ...]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recharge_plans');
    }
};
