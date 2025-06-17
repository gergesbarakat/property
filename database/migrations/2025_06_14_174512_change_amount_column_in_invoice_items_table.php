<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Use a raw SQL statement to modify the column type
        // This changes the 'amount' column to a DECIMAL type that can hold
        // up to 15 total digits, with 2 digits after the decimal point.
        DB::statement("ALTER TABLE invoice_items MODIFY COLUMN amount DECIMAL(15, 2)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the column back to its original data type.
        // You should replace DECIMAL(8, 2) with whatever the original data type was.
        // Common defaults are DECIMAL(8, 2) or FLOAT.
        DB::statement("ALTER TABLE invoice_items MODIFY COLUMN amount DECIMAL(8, 2)");
    }
};
