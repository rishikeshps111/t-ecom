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
        Schema::create('planner_document_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planner_document_id')->nullable()->constrained('planner_documents')->nullOnDelete();
            $table->string('document');
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planner_document_files');
    }
};
