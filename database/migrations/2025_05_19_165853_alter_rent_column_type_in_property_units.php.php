<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterRentColumnTypeInPropertyUnits extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE property_units MODIFY rent BIGINT');
    }

    public function down()
    {
        DB::statement('ALTER TABLE property_units MODIFY rent FLOAT');
    }
}
