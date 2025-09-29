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
        // ✅ FIX: Use a raw SQL statement to modify the columns, avoiding the need for doctrine/dbal.
        DB::statement("ALTER TABLE property_units
            ADD COLUMN floor VARCHAR(255) NULL AFTER unit_size,
            ADD COLUMN building VARCHAR(255) NULL AFTER floor,
            ADD COLUMN location VARCHAR(255) NULL AFTER building,
            MODIFY bedroom INT NULL,
            MODIFY kitchen INT NULL,
            MODIFY baths INT NULL,
            MODIFY unit_size INT NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_units', function (Blueprint $table) {
            $table->dropColumn(['floor', 'building', 'location']);
            // Revert integer columns back to NOT NULL if needed, assuming that was their original state
            $table->integer('bedroom')->nullable(false)->change();
            $table->integer('kitchen')->nullable(false)->change();
            $table->integer('baths')->nullable(false)->change();
            $table->integer('unit_size')->nullable()->change(); // Keep this nullable as it was added as such
        });
    }
};
