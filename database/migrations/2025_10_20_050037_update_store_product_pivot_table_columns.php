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
        Schema::table('store_product', function (Blueprint $table) {
            $table->renameColumn('collection_id', 'store_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_product', function (Blueprint $table) {
            $table->renameColumn('store_id', 'collection_id');
        });
    }
};
