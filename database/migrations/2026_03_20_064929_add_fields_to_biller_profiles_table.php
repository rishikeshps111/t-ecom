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
            $table->string('report_header')->nullable()->after('receipt_footer');
            $table->string('report_footer')->nullable()->after('report_header');
            $table->string('report_tc')->nullable()->after('report_footer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biller_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'report_header',
                'report_footer',
                'report_tc'
            ]);
        });
    }
};
