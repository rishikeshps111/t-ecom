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
        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_category_id')->nullable()->constrained('chat_categories')->nullOnDelete();
            $table->string('title');
            $table->text('content')->nullable();
            $table->json('keywords')->nullable();
            $table->enum('status', ['draft', 'published', 'unpublished'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_bases');
    }
};
