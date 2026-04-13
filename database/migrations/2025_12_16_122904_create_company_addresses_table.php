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
        Schema::create('company_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');

            // Registered Office
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->foreignId('city_id')->nullable()->constrained('locations')->onDelete('cascade');
            $table->foreignId('state_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('postcode')->nullable();
            $table->string('country')->default('Malaysia');
            $table->string('office_phone')->nullable();
            $table->string('office_email')->nullable();

            // Business Address
            $table->string('business_address1')->nullable();
            $table->string('business_address2')->nullable();
            $table->foreignId('business_city_id')->nullable()->constrained('locations')->onDelete('cascade');
            $table->foreignId('business_state_id')->nullable()->constrained('states')->onDelete('cascade')->nullable();
            $table->string('business_postcode')->nullable();
            $table->string('business_country')->default('Malaysia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_addresses');
    }
};
