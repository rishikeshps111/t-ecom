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
        Schema::table('users', function (Blueprint $table) {
            $table->string('planner_code')->nullable()->after('user_type');
            $table->decimal('iv', 10, 1)->nullable()->after('planner_code');
            $table->integer('sequence_number')->nullable()->after('iv');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['planner_code', 'iv', 'sequence_number']);
        });
    }
};
