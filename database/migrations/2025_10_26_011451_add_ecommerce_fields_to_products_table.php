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
        Schema::table('products', function (Blueprint $table) {
            // E-commerce specific fields
            $table->string('website_url')->nullable();
            $table->string('hs_code')->nullable(); // Harmonized System Code
            $table->string('parent_product')->nullable(); // Parent product for variants
            $table->string('supplier')->nullable();
            $table->string('product_type')->nullable(); // e.g., "Mens Tee", "Womens Dress"
            $table->string('fabric')->nullable(); // e.g., "100% Cotton"
            $table->text('care_instructions')->nullable();
            $table->string('lead_times')->nullable(); // e.g., "Up to 4 weeks including shipping"
            $table->string('available_sizes')->nullable(); // e.g., "XS - 3XL"
            $table->json('customization_methods')->nullable(); // e.g., ["IHP", "EMB", "PATCH"]
            $table->string('model_size')->nullable(); // e.g., "XS - 3XL"
            $table->decimal('starting_from_price', 10, 2)->nullable();
            $table->string('minimums')->nullable(); // e.g., "No minimums"
            $table->timestamp('last_inventory_sync')->nullable();
            $table->boolean('has_variants')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'website_url',
                'hs_code',
                'parent_product',
                'supplier',
                'product_type',
                'fabric',
                'care_instructions',
                'lead_times',
                'available_sizes',
                'customization_methods',
                'model_size',
                'starting_from_price',
                'minimums',
                'last_inventory_sync',
                'has_variants',
            ]);
        });
    }
};
