<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB; // Import the DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // This raw SQL statement changes the column type to accept strings
        DB::statement("ALTER TABLE invoices MODIFY COLUMN invoice_id VARCHAR(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This is not a perfect rollback, but it attempts to revert to an integer type.
        // In a real project, you might need a more specific integer type.
        DB::statement("ALTER TABLE invoices MODIFY COLUMN invoice_id INT NOT NULL");
    }
};
