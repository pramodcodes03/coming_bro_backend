<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds `last_online_at` so the admin "God's Eye View" can show the exact
     * moment a driver came online. It is stamped only on the offline -> online
     * transition (not on every location ping or profile edit), so it stays a
     * truthful "online since" timestamp. Existing online rows are backfilled
     * from `updated_at` as a best-effort approximation.
     */
    public function up(): void
    {
        Schema::table('driver_users', function (Blueprint $table) {
            $table->timestamp('last_online_at')->nullable()->after('is_online');
        });

        // Backfill currently-online drivers so the view isn't blank on day one.
        DB::table('driver_users')
            ->where('is_online', true)
            ->whereNull('last_online_at')
            ->update(['last_online_at' => DB::raw('updated_at')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('driver_users', function (Blueprint $table) {
            $table->dropColumn('last_online_at');
        });
    }
};
