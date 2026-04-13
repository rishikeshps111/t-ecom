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
        Schema::table('work_plans', function (Blueprint $table) {
            $table->foreignId('production_staff_id')->nullable()->constrained('users')->nullOnDelete()->after('planner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_plans', function (Blueprint $table) {
            $table->dropForeign(['production_staff_id']);
            $table->dropColumn('production_staff_id');
        });
    }
};
