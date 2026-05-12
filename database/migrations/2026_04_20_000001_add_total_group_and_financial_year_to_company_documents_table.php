<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_documents', function (Blueprint $table) {
            $table->foreignId('total_group_id')
                ->nullable()
                ->after('title')
                ->constrained('customers')
                ->nullOnDelete();
            $table->foreignId('financial_year_id')
                ->nullable()
                ->after('total_group_id')
                ->constrained('financial_years')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('company_documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('financial_year_id');
            $table->dropConstrainedForeignId('total_group_id');
        });
    }
};
