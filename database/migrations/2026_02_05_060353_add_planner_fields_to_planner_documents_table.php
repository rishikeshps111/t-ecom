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
        Schema::table('planner_documents', function (Blueprint $table) {
            $table->foreignId('company_type_id')
                ->nullable()
                ->after('company_id')
                ->constrained('company_types')
                ->nullOnDelete();
            $table->foreignId('business_user_id')
                ->nullable()
                ->after('company_type_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('total_group_id')
                ->nullable()
                ->after('business_user_id')
                ->constrained('customers')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planner_documents', function (Blueprint $table) {
            $table->dropForeign(['company_type_id']);
            $table->dropForeign(['business_user_id']);
            $table->dropForeign(['total_group_id']);
            $table->dropColumn(['company_type_id', 'business_user_id', 'total_group_id']);
        });
    }
};
