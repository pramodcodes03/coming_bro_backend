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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('sender_id')->default('');
            $table->string('receiver_id')->default('');
            $table->string('order_id')->default('');
            $table->text('message')->default('');
            $table->string('message_type')->default('');
            $table->string('video_thumbnail')->default('');
            $table->string('url_mime')->default('');
            $table->string('url_url')->default('');
            $table->string('url_video_thumbnail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
