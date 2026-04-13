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
            $table->decimal('production_c_percentage', 8, 2)->nullable()->after('status');
            $table->decimal('planner_c_percentage', 8, 2)->nullable()->after('production_c_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'planner_c_percentage',
                'production_c_percentage',
            ]);
        });
    }
};
