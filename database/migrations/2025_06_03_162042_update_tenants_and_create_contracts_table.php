<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the tenants table
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('national_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('nationality')->nullable();
            // $table->string('address')->nullable(); // Already exists
            $table->string('gender')->nullable();
            $table->string('purchase_type')->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('payment_amount', 12, 2)->nullable();
            $table->string('payment_currency')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('iban_number')->nullable();
            $table->string('property_type')->nullable();
            $table->string('building_name')->nullable();
            $table->string('floor_number')->nullable();
            $table->string('unit_number')->nullable();
            $table->string('profile_image')->nullable();
        });

        // Create the contracts table
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable(); // No foreign key constraint
            $table->string('contract_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the tenants table updates
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'national_id',
                'phone',
                'email',
                'nationality',
                'gender',
                'purchase_type',
                'payment_method',
                'payment_amount',
                'payment_currency',
                'bank_name',
                'iban_number',
                'property_type',
                'building_name',
                'floor_number',
                'unit_number',
                'profile_image',
            ]);
        });

        // Drop the contracts table
        Schema::dropIfExists('contracts');
    }
};
