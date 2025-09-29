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
            // Making existing integer columns nullable to prevent errors with empty form fields
            $table->integer('bedroom')->nullable()->change();
            $table->integer('kitchen')->nullable()->change();
            $table->integer('baths')->nullable()->change();
            $table->integer('unit_size')->nullable()->change();

            // Adding the new columns
            $table->string('floor')->nullable()->after('unit_size');
            $table->string('building')->nullable()->after('floor');
            $table->string('location')->nullable()->after('building');
        });
    }
};
