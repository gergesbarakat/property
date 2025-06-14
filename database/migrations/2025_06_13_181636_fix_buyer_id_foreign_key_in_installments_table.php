<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('installments', function (Blueprint $table) {
            // 1. Drop the old, incorrect foreign key constraint.
            // The name 'installments_buyer_id_foreign' comes directly from your error message.
            $table->dropForeign('installments_buyer_id_foreign');

            // 2. Add the new, correct foreign key referencing the 'tenants' table.
            $table->foreign('buyer_id')
                  ->references('id')
                  ->on('tenants')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('installments', function (Blueprint $table) {
            // This allows the migration to be reversible.
            $table->dropForeign(['buyer_id']);

            $table->foreign('buyer_id')
                  ->references('id')
                  ->on('buyers')
                  ->onDelete('cascade');
        });
    }
};
