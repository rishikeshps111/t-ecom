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
        Schema::table('companies', function (Blueprint $table) {
            $table->foreignId('company_type_id')
                ->nullable()
                ->after('company_type')
                ->constrained('company_types')
                ->onDelete('set null');

            $table->foreignId('business_user_id')
                ->nullable()
                ->after('company_type_id')
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignId('total_group_id')
                ->nullable()
                ->after('business_user_id')
                ->constrained('customers')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['company_type_id']);
            $table->dropForeign(['business_user_id']);
            $table->dropForeign(['total_group_id']);
            $table->dropColumn(['company_type_id', 'business_user_id', 'total_group_id']);
        });
    }
};
