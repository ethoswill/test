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
        Schema::create('product_sales_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('product_name');
            $table->string('supplier_name');
            $table->string('vendor_name');
            $table->string('store_name');
            $table->decimal('cost_price', 10, 2);
            $table->decimal('retail_price', 10, 2);
            $table->decimal('profit_margin', 10, 2);
            $table->decimal('profit_percentage', 5, 2);
            $table->integer('quantity_sold');
            $table->decimal('total_revenue', 12, 2);
            $table->decimal('total_cost', 12, 2);
            $table->decimal('total_profit', 12, 2);
            $table->date('report_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sales_reports');
    }
};
