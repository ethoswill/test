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
        Schema::rename('collections', 'stores');
        Schema::rename('collection_product', 'store_product');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('stores', 'collections');
        Schema::rename('store_product', 'collection_product');
    }
};
