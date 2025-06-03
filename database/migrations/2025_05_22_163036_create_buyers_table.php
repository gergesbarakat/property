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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('family_member')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->unsignedBigInteger('property')->nullable();
            $table->unsignedBigInteger('unit')->nullable();
            $table->date('lease_start_date')->nullable();
            $table->date('lease_end_date')->nullable();
            $table->enum('payment_type', ['full', 'installment'])->default('full');
            $table->enum('installment_type', ['monthly', 'yearly'])->nullable();
            $table->integer('installment_duration')->nullable(); // in months
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->decimal('installment_amount', 15, 2)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('property')->references('id')->on('properties')->onDelete('set null');
            $table->foreign('unit')->references('id')->on('property_units')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};