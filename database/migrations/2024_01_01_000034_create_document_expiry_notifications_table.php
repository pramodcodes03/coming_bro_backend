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
        Schema::create('document_expiry_notifications', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('driver_id')->nullable();
            $table->string('document_id')->nullable();
            $table->string('message')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_expiry_notifications');
    }
};
