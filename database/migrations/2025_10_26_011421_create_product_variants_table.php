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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('variant_name'); // e.g., "Small", "Medium", "Large"
            $table->string('sku')->unique(); // e.g., "5001-WHITE-G-S"
            $table->string('color')->nullable(); // e.g., "White", "Black"
            $table->string('size')->nullable(); // e.g., "S", "M", "L"
            $table->decimal('weight', 8, 2)->nullable(); // Weight in oz/kg
            $table->integer('inventory_quantity')->default(0);
            $table->decimal('price', 10, 2)->nullable(); // Variant-specific price
            $table->decimal('cost', 10, 2)->nullable(); // Variant-specific cost
            $table->string('barcode')->nullable();
            $table->json('attributes')->nullable(); // Additional variant attributes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'is_active']);
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
