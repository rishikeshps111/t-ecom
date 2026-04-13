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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('item_name');
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->cascadeOnDelete();
            $table->string('item_type')->nullable();
            $table->boolean('status')->default(1);

            // Pricing
            $table->decimal('selling_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->decimal('commission_factor', 8, 2)->nullable();
            $table->string('tax_group')->nullable();

            // Inventory
            $table->string('uom')->nullable();
            $table->integer('opening_stock')->nullable();
            $table->integer('reorder_level')->nullable();
            $table->integer('safety_stock')->nullable();

            // Supplier
            $table->string('default_supplier')->nullable();
            $table->string('supplier_item')->nullable();
            $table->decimal('purchase_price', 12, 2)->nullable();

            // Logistics
            $table->string('warehouse')->nullable();
            $table->string('bin_location')->nullable();
            $table->decimal('weight', 8, 2)->nullable();

            // Description
            $table->text('short_description')->nullable();
            $table->longText('detail_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
