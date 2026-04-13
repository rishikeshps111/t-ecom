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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sub_category_id')->nullable()->constrained()->onDelete('cascade');
            
            $table->string('company_code')->nullable();
            $table->string('company_type')->nullable();
            $table->string('company_name')->nullable();
            $table->string('alt_company_name')->nullable();
            $table->string('industry')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'draft', 'inactive'])->default('draft');

            // Step 2
            $table->string('ssm_number')->nullable();
            $table->date('incorporation_date')->nullable();
            $table->date('commencement_date')->nullable();
            $table->decimal('paid_up_capital', 15, 2)->nullable();
            $table->decimal('authorized_capital', 15, 2)->nullable();
            $table->integer('employees')->nullable();

            // Step 5
            $table->string('primary_contact_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email_address')->nullable();
            $table->string('company_website')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
