<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            if (!Schema::hasColumn('banners', 'title')) {
                $table->string('title')->nullable()->after('id');
            }
            if (!Schema::hasColumn('banners', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('banners', 'redirect_url')) {
                $table->string('redirect_url')->nullable()->after('image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['title', 'description', 'redirect_url']);
        });
    }
};
