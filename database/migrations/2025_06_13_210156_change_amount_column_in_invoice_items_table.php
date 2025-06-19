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
     * @return void
     */
    public function up()
    {
        // This raw SQL statement modifies the column type to accept larger numbers.
        DB::statement("ALTER TABLE invoice_items MODIFY COLUMN amount DECIMAL(10, 2) NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This attempts to revert the change, assuming it was DECIMAL(8, 2) before.
        DB::statement("ALTER TABLE invoice_items MODIFY COLUMN amount DECIMAL(8, 2) NULL");
    }
};
