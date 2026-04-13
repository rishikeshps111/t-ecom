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
        Schema::table('quotations', function (Blueprint $table) {
            $table->foreignId('company_type_id')->nullable()->constrained('company_types')->onDelete('set null');
            $table->foreignId('business_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('planner_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->onDelete('set null');

            $table->longText('remarks')->nullable();
            $table->longText('invoice_address')->nullable();
            $table->longText('delivery_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['company_type_id']);
            $table->dropForeign(['business_user_id']);
            $table->dropForeign(['planner_user_id']);
            $table->dropForeign(['currency_id']);
            $table->dropColumn(['company_type_id', 'business_user_id', 'planner_user_id', 'remarks', 'invoice_address', 'delivery_address', 'currency_id']);
        });
    }
};
