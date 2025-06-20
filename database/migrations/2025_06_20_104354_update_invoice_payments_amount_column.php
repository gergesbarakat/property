<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to modify the column - no DBAL required
        DB::statement('ALTER TABLE `invoice_payments` MODIFY COLUMN `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00');

        // Also update installments table for consistency
        DB::statement('ALTER TABLE `installments` MODIFY COLUMN `amount` DECIMAL(15,2) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original (be careful with existing data)
        DB::statement('ALTER TABLE `invoice_payments` MODIFY COLUMN `amount` DOUBLE(8,2) NOT NULL DEFAULT 0.00');

    }
};
