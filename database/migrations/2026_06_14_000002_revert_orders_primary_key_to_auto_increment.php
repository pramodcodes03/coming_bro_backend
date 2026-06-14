<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Revert orders.id (and the order_id foreign keys) from CHAR(36) UUID back
     * to an auto-increment BIGINT primary key.
     *
     * Existing UUID ids cannot be cast to integers, so the existing orders and
     * the rows referencing them are cleared first.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // Drop the foreign keys that reference orders.id.
        DB::statement('ALTER TABLE accepted_drivers DROP FOREIGN KEY accepted_drivers_order_id_foreign');
        DB::statement('ALTER TABLE chat_inboxes DROP FOREIGN KEY chat_inboxes_order_id_foreign');
        DB::statement('ALTER TABLE chat_messages DROP FOREIGN KEY chat_messages_order_id_foreign');
        DB::statement('ALTER TABLE sos DROP FOREIGN KEY sos_order_id_foreign');

        // UUID values cannot become integers — clear the affected rows.
        DB::table('accepted_drivers')->delete();
        DB::table('chat_messages')->delete();
        DB::table('chat_inboxes')->delete();
        DB::table('sos')->whereNotNull('order_id')->update(['order_id' => null]);
        DB::table('orders')->delete();

        // Change the primary key back to auto-increment BIGINT.
        DB::statement('ALTER TABLE orders MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');

        // Match the referencing columns back to BIGINT.
        DB::statement('ALTER TABLE accepted_drivers MODIFY order_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE chat_inboxes MODIFY order_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE chat_messages MODIFY order_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE sos MODIFY order_id BIGINT UNSIGNED NULL');

        // Re-create the foreign keys with their original ON DELETE behaviour.
        DB::statement('ALTER TABLE accepted_drivers ADD CONSTRAINT accepted_drivers_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE chat_inboxes ADD CONSTRAINT chat_inboxes_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE chat_messages ADD CONSTRAINT chat_messages_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL');
        DB::statement('ALTER TABLE sos ADD CONSTRAINT sos_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Re-apply the UUID primary key (mirrors the previous migration).
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::statement('ALTER TABLE accepted_drivers DROP FOREIGN KEY accepted_drivers_order_id_foreign');
        DB::statement('ALTER TABLE chat_inboxes DROP FOREIGN KEY chat_inboxes_order_id_foreign');
        DB::statement('ALTER TABLE chat_messages DROP FOREIGN KEY chat_messages_order_id_foreign');
        DB::statement('ALTER TABLE sos DROP FOREIGN KEY sos_order_id_foreign');

        DB::statement('ALTER TABLE orders MODIFY id CHAR(36) NOT NULL');

        DB::statement('ALTER TABLE accepted_drivers MODIFY order_id CHAR(36) NOT NULL');
        DB::statement('ALTER TABLE chat_inboxes MODIFY order_id CHAR(36) NOT NULL');
        DB::statement('ALTER TABLE chat_messages MODIFY order_id CHAR(36) NULL');
        DB::statement('ALTER TABLE sos MODIFY order_id CHAR(36) NULL');

        DB::statement('ALTER TABLE accepted_drivers ADD CONSTRAINT accepted_drivers_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE chat_inboxes ADD CONSTRAINT chat_inboxes_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE chat_messages ADD CONSTRAINT chat_messages_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL');
        DB::statement('ALTER TABLE sos ADD CONSTRAINT sos_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL');

        Schema::enableForeignKeyConstraints();
    }
};
