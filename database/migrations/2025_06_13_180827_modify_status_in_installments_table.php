<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB; // ✅ IMPORTANT: Import the DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // This raw SQL statement modifies the column directly in MySQL.
        // It sets the column to an ENUM that accepts 'pending' and sets it as the default.
        DB::statement("ALTER TABLE installments MODIFY COLUMN status ENUM('pending', 'paid', 'overdue') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This reverts the column to a state that does not include 'pending'.
        DB::statement("ALTER TABLE installments MODIFY COLUMN status ENUM('paid', 'overdue') NOT NULL");
    }
};
