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
        Schema::table('invoices', function (Blueprint $table) {
            // Add the new column after the 'tenant_id' column for organization.
            // It is nullable because not all invoices may be generated from an installment.
            // onDelete('set null') means if an installment is deleted, the invoice will remain
            // but its link to the installment will be removed.
            $table->foreignId('installment_id')
                ->nullable()
                ->after('tenant_id')
                ->constrained('installments')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop the foreign key constraint first, then the column.
            $table->dropForeign(['installment_id']);
            $table->dropColumn('installment_id');
        });
    }
};
