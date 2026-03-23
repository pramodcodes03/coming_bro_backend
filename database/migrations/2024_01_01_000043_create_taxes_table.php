<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('title')->nullable();
            $table->string('type')->nullable(); // 'percentage'
            $table->string('tax')->nullable();
            $table->string('country')->nullable();
            $table->boolean('enable')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
