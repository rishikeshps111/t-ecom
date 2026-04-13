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
        Schema::create('work_plan_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_plan_id')->constrained()->cascadeOnDelete();
            $table->integer('payment_id')->nullable();
            $table->string('entity')->nullable();
            $table->string('name')->nullable();
            $table->string('file')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_plan_attachments');
    }
};
