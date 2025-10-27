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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('cost', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_level')->default(0);
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->string('status')->default('active'); // active, inactive, discontinued
            $table->json('images')->nullable(); // Store image URLs/paths
            $table->json('specifications')->nullable(); // Store product specifications
            $table->decimal('weight', 8, 2)->nullable(); // Weight in kg
            $table->string('dimensions')->nullable(); // e.g., "10x20x30 cm"
            $table->string('barcode')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['status', 'is_featured']);
            $table->index(['category', 'brand']);
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
