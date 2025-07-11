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
        Schema::table('property_units', function (Blueprint $table) {
            // Adds a new integer column named 'unit_size', placed after the 'baths' column.
            // It is nullable, meaning it's not required to have a value.
            $table->integer('unit_size')->nullable()->after('baths');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_units', function (Blueprint $table) {
            // This makes the migration reversible by dropping the column if you ever need to rollback.
            $table->dropColumn('unit_size');
        });
    }
};
