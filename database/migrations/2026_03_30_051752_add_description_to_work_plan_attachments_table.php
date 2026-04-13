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
        Schema::table('work_plan_attachments', function (Blueprint $table) {
            $table->text('description')->nullable()->after('type');
            $table->foreignId('service_type_id')->nullable()->constrained('company_types')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_plan_attachments', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['service_type_id']);

            // Then drop column
            $table->dropColumn('service_type_id');
            $table->dropColumn('description');
        });
    }
};
