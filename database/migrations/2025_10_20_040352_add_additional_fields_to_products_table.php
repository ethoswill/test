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
            $table->string('website_url')->nullable()->after('supplier_code');
            $table->string('hs_code')->nullable()->after('website_url');
            $table->string('parent_product')->nullable()->after('hs_code');
            $table->string('status')->default('Supplier Product')->after('parent_product');
            $table->string('product_type')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['website_url', 'hs_code', 'parent_product', 'status', 'product_type']);
        });
    }
};
