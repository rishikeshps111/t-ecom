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
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('sum_amount', 10, 2)->nullable()->after('unit_price');
            $table->boolean('is_selected')->default(false)->after('sum_amount');
            $table->decimal('planner_iv', 10, 2)->nullable()->after('is_selected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn([
                'sum_amount',
                'is_selected',
                'planner_iv'
            ]);
        });
    }
};
