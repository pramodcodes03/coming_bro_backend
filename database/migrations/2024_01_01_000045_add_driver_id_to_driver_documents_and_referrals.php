<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The driver_documents and driver_referrals tables used `id` as both PK
     * and the implicit driver foreign key. But the Eloquent relationships
     * expect explicit `driver_id` columns. Add them and backfill from `id`.
     */
    public function up(): void
    {
        // driver_documents: add driver_id column
        if (!Schema::hasColumn('driver_documents', 'driver_id')) {
            Schema::table('driver_documents', function (Blueprint $table) {
                $table->string('driver_id')->nullable()->after('id');
                $table->index('driver_id');
            });

            // Backfill: existing rows use id as the driver's user id
            DB::table('driver_documents')->whereNull('driver_id')->update([
                'driver_id' => DB::raw('`id`'),
            ]);
        }

        // driver_referrals: add driver_id column
        if (!Schema::hasColumn('driver_referrals', 'driver_id')) {
            Schema::table('driver_referrals', function (Blueprint $table) {
                $table->string('driver_id')->nullable()->after('id');
                $table->index('driver_id');
            });

            // Backfill
            DB::table('driver_referrals')->whereNull('driver_id')->update([
                'driver_id' => DB::raw('`id`'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('driver_documents', function (Blueprint $table) {
            $table->dropIndex(['driver_id']);
            $table->dropColumn('driver_id');
        });

        Schema::table('driver_referrals', function (Blueprint $table) {
            $table->dropIndex(['driver_id']);
            $table->dropColumn('driver_id');
        });
    }
};
