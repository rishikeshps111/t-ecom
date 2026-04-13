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
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('planner_commission')->default(0)->after('detail_description');
            $table->decimal('planner_iv_percentage', 8, 2)->nullable()->after('planner_commission');
            $table->decimal('planner_c_percentage', 8, 2)->nullable()->after('planner_iv_percentage');

            // Production Commission
            $table->boolean('production_commission')->default(0)->after('planner_c_percentage');
            $table->decimal('production_iv_percentage', 8, 2)->nullable()->after('production_commission');
            $table->decimal('production_c_percentage', 8, 2)->nullable()->after('production_iv_percentage');

            $table->decimal('stt', 8, 2)->nullable()->after('production_c_percentage');
            $table->string('account_code')->nullable()->after('stt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'tss',
                'planner_commission',
                'planner_iv_percentage',
                'planner_c_percentage',
                'production_commission',
                'production_iv_percentage',
                'production_c_percentage',
                'stt',
                'account_code'
            ]);
        });
    }
};
