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
        Schema::table('quotations', callback: function (Blueprint $table) {
            $table->decimal('planner_commission', 10, 2)->nullable()->after('grant_total');
            $table->decimal('p_bill_percentage', 10, 6)->nullable()->after('planner_commission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn([
                'planner_commission',
                'p_bill_percentage'
            ]);
        });
    }
};
