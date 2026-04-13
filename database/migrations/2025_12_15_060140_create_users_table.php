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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->foreignId('city_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('state_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->unique()->nullable();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone', 200)->nullable();
            $table->string('profile_image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('show_password')->nullable();
            $table->string('role', 200)->nullable();
            $table->rememberToken();
            $table->tinyInteger('status')->default(0)->nullable();
            $table->string('contact_person')->nullable();
            $table->text('address')->nullable();
            $table->string('assigned_item', 200)->nullable();
            $table->string('pricing_level', 200)->nullable();
            $table->string('commission_factor', 200)->nullable();
            $table->string('tax_group', 200)->nullable();
            $table->string('quotation_access', 200)->nullable();
            $table->string('invoice_view', 200)->nullable();
            $table->string('department', 200)->nullable();
            $table->string('designation', 200)->nullable();
            $table->string('task_access', 200)->nullable();
            $table->string('document_upload', 200)->nullable();
            $table->string('document_edit', 200)->nullable();
            $table->string('task_scope', 200)->nullable();
            $table->string('approval_authority', 200)->nullable();
            $table->string('verson_control', 200)->nullable();
            $table->string('document_delete', 200)->nullable();
            $table->string('folder_access', 200)->nullable();
            $table->string('document_category', 200)->nullable();
            $table->string('alternate_phone')->nullable();
            $table->longText('billing_address')->nullable();
            $table->string('country')->nullable();
            $table->string('gst')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('whats_app')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
