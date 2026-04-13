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
        Schema::table('biller_profiles', function (Blueprint $table) {
            $table->string('work_plan_header')->nullable()->after('receipt_footer');
            $table->string('work_plan_footer')->nullable()->after('work_plan_header');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biller_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'work_plan_header',
                'work_plan_footer'
            ]);
        });
    }
};
