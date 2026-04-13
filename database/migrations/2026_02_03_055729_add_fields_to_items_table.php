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
            $table->foreignId('company_type_id')
                ->nullable()
                ->after('id')
                ->constrained('company_types')
                ->onDelete('cascade');

            $table->foreignId('total_group_id')
                ->nullable()
                ->after('company_type_id')
                ->constrained('customers')
                ->onDelete('cascade');

            $table->decimal('suggested_price', 10, 2)
                ->nullable()
                ->after('total_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['company_type_id']);
            $table->dropForeign(['total_group_id']);

            $table->dropColumn([
                'company_type_id',
                'total_group_id',
                'suggested_price'
            ]);
        });
    }
};
