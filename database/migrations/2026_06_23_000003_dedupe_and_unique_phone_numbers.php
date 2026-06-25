<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Stop duplicate accounts sharing one phone number. A NULL country_code in
     * the old "where phone AND country_code" login lookup never matched, so a
     * new row was created on every login. We merge existing duplicates into the
     * oldest record (reassigning their child rows) and then add a unique index
     * on phone_number as a hard guarantee for both customers and drivers.
     */
    public function up(): void
    {
        $this->mergeDuplicates('driver_users', $this->driverChildren());
        $this->mergeDuplicates('customers', $this->customerChildren());

        if (! $this->hasIndex('driver_users', 'driver_users_phone_number_unique')) {
            Schema::table('driver_users', function (Blueprint $table) {
                $table->unique('phone_number', 'driver_users_phone_number_unique');
            });
        }

        if (! $this->hasIndex('customers', 'customers_phone_number_unique')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->unique('phone_number', 'customers_phone_number_unique');
            });
        }
    }

    public function down(): void
    {
        if ($this->hasIndex('driver_users', 'driver_users_phone_number_unique')) {
            Schema::table('driver_users', function (Blueprint $table) {
                $table->dropUnique('driver_users_phone_number_unique');
            });
        }

        if ($this->hasIndex('customers', 'customers_phone_number_unique')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropUnique('customers_phone_number_unique');
            });
        }
    }

    /**
     * For every phone number owned by more than one row, keep the oldest row,
     * repoint that owner's child records at it, then delete the extra rows.
     * NULL phone numbers are left untouched (MySQL allows multiple NULLs in a
     * unique index, so social-only accounts won't collide).
     *
     * @param  array<int, array{0:string,1:string,2:?array{0:string,1:mixed}}>  $children
     */
    private function mergeDuplicates(string $table, array $children): void
    {
        $groups = DB::table($table)
            ->select('phone_number', DB::raw('MIN(id) as keep_id'), DB::raw('COUNT(*) as cnt'))
            ->whereNotNull('phone_number')
            ->where('phone_number', '<>', '')
            ->groupBy('phone_number')
            ->having('cnt', '>', 1)
            ->get();

        foreach ($groups as $group) {
            $dupeIds = DB::table($table)
                ->where('phone_number', $group->phone_number)
                ->where('id', '<>', $group->keep_id)
                ->pluck('id')
                ->all();

            if (empty($dupeIds)) {
                continue;
            }

            foreach ($children as [$childTable, $childColumn, $extra]) {
                if (! Schema::hasTable($childTable) || ! Schema::hasColumn($childTable, $childColumn)) {
                    continue;
                }

                try {
                    $query = DB::table($childTable)->whereIn($childColumn, $dupeIds);
                    if ($extra) {
                        $query->where($extra[0], $extra[1]);
                    }
                    $query->update([$childColumn => $group->keep_id]);
                } catch (\Throwable $e) {
                    // A unique "one-per-owner" child (e.g. bank_details) may
                    // already exist on the survivor; leave it and let the
                    // cascade/null-on-delete handle the dupe's copy on delete.
                }
            }

            DB::table($table)->whereIn('id', $dupeIds)->delete();
        }
    }

    /** Child tables/columns that point at a driver_users.id. */
    private function driverChildren(): array
    {
        return [
            ['orders', 'driver_id', null],
            ['orders_intercity', 'driver_id', null],
            ['bank_details', 'user_id', null],
            ['driver_documents', 'driver_id', null],
            ['driver_referrals', 'driver_id', null],
            ['reviews', 'driver_id', null],
            ['withdrawal_history', 'user_id', null],
            ['accepted_drivers', 'driver_id', null],
            ['chat_inboxes', 'driver_id', null],
            ['document_expiry_notifications', 'driver_id', null],
            ['referral_logs', 'driver_id', null],
            ['sos', 'driver_id', null],
            ['return_rides', 'driver_id', null],
            ['return_ride_bookings', 'driver_id', null],
            ['wallet_transactions', 'user_id', ['user_type', 'driver']],
        ];
    }

    /** Child tables/columns that point at a customers.id. */
    private function customerChildren(): array
    {
        return [
            ['orders', 'user_id', null],
            ['orders_intercity', 'user_id', null],
            ['chat_inboxes', 'customer_id', null],
            ['reviews', 'customer_id', null],
            ['referral_logs', 'user_id', null],
            ['sos', 'user_id', null],
            ['return_ride_bookings', 'user_id', null],
            ['wallet_transactions', 'user_id', ['user_type', 'customer']],
        ];
    }

    private function hasIndex(string $table, string $index): bool
    {
        return collect(DB::select("SHOW INDEX FROM `{$table}`"))
            ->pluck('Key_name')
            ->contains($index);
    }
};
