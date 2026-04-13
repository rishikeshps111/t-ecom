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
            $table->string('quotation_tc')->nullable()->after('work_plan_footer');
            $table->string('invoice_payment_terms')->nullable()->after('quotation_tc');
            $table->string('receipt_tc')->nullable()->after('invoice_payment_terms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biller_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'quotation_tc',
                'invoice_payment_terms',
                'receipt_tc'
            ]);
        });
    }
};
