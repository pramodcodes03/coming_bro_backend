<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Convert orders.id (and every order_id foreign key) from an
     * auto-increment BIGINT to a CHAR(36) UUID, so the client-generated
     * UUID can be stored as the real primary key.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Drop the foreign keys that reference orders.id.
        DB::statement('ALTER TABLE accepted_drivers DROP FOREIGN KEY accepted_drivers_order_id_foreign');
        DB::statement('ALTER TABLE chat_inboxes DROP FOREIGN KEY chat_inboxes_order_id_foreign');
        DB::statement('ALTER TABLE chat_messages DROP FOREIGN KEY chat_messages_order_id_foreign');
        DB::statement('ALTER TABLE sos DROP FOREIGN KEY sos_order_id_foreign');

        // 2. Change the primary key column (drops AUTO_INCREMENT, keeps PK).
        DB::statement('ALTER TABLE orders MODIFY id CHAR(36) NOT NULL');

        // 3. Match the referencing columns to the new type.
        DB::statement('ALTER TABLE accepted_drivers MODIFY order_id CHAR(36) NOT NULL');
        DB::statement('ALTER TABLE chat_inboxes MODIFY order_id CHAR(36) NOT NULL');
        DB::statement('ALTER TABLE chat_messages MODIFY order_id CHAR(36) NULL');
        DB::statement('ALTER TABLE sos MODIFY order_id CHAR(36) NULL');

        // 4. Re-create the foreign keys with their original ON DELETE behaviour.
        DB::statement('ALTER TABLE accepted_drivers ADD CONSTRAINT accepted_drivers_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE chat_inboxes ADD CONSTRAINT chat_inboxes_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE chat_messages ADD CONSTRAINT chat_messages_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL');
        DB::statement('ALTER TABLE sos ADD CONSTRAINT sos_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Revert to an auto-increment BIGINT primary key. Note: existing UUID
     * values cannot be converted back to integers and will be lost.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::statement('ALTER TABLE accepted_drivers DROP FOREIGN KEY accepted_drivers_order_id_foreign');
        DB::statement('ALTER TABLE chat_inboxes DROP FOREIGN KEY chat_inboxes_order_id_foreign');
        DB::statement('ALTER TABLE chat_messages DROP FOREIGN KEY chat_messages_order_id_foreign');
        DB::statement('ALTER TABLE sos DROP FOREIGN KEY sos_order_id_foreign');

        DB::statement('ALTER TABLE orders MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');

        DB::statement('ALTER TABLE accepted_drivers MODIFY order_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE chat_inboxes MODIFY order_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE chat_messages MODIFY order_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE sos MODIFY order_id BIGINT UNSIGNED NULL');

        DB::statement('ALTER TABLE accepted_drivers ADD CONSTRAINT accepted_drivers_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE chat_inboxes ADD CONSTRAINT chat_inboxes_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE chat_messages ADD CONSTRAINT chat_messages_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL');
        DB::statement('ALTER TABLE sos ADD CONSTRAINT sos_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL');

        Schema::enableForeignKeyConstraints();
    }
};
