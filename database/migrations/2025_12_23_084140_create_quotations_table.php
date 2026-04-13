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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('contact_person')->nullable();
            $table->date('quotation_date')->nullable();
            $table->date('validity_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->integer('validity_in_days')->nullable();
            $table->decimal('sub_total', 10, 2)->nullable();
            $table->decimal('tax_total', 10, 2)->nullable();
            $table->decimal('discount_total', 10, 2)->nullable();
            $table->decimal('grant_total', 10, 2)->nullable();
            $table->longText('payment_terms')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('terms')->nullable();
            $table->unsignedTinyInteger('current_level')->default(1);
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'accepted', 'pending', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
