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
        Schema::create('work_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();
            $table->foreignId('company_type_id')
                ->nullable()
                ->constrained('company_types')
                ->nullOnDelete();
            $table->foreignId('total_group_id')
                ->nullable()
                ->constrained('customers')
                ->nullOnDelete();
            $table->foreignId('planner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('workplan_number')->nullable();
            $table->date('date')->nullable();
            $table->longText('description')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['approved', 'pending', 'cancelled', 'closed', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_plans');
    }
};
