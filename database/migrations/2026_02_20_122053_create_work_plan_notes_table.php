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
        Schema::create('work_plan_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('note_type_id')->nullable()->constrained()->nullOnDelete();
            $table->longText('description')->nullable();
            $table->enum('status', ['pending', 'active', 'closed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_plan_notes');
    }
};
