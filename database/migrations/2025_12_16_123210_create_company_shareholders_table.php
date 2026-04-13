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
        Schema::create('company_shareholders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');

            $table->enum('type', ['individual', 'corporate'])->nullable();
            $table->string('name')->nullable();
            $table->string('identification')->nullable();
            $table->string('nationality')->nullable();
            $table->integer('shares')->nullable();
            $table->string('ownership')->nullable();
            $table->string('share_class')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_shareholders');
    }
};
