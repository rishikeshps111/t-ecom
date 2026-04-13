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
        Schema::table('companies', function (Blueprint $table) {
            $table->foreignId('planner_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('planner_code')
                ->nullable()
                ->after('planner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['planner_id']);
            $table->dropColumn(['planner_id', 'planner_code']);
        });
    }
};
