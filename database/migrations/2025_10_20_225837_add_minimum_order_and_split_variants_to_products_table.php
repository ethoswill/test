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
            $table->integer('minimum_order_quantity')->nullable()->after('franchisee_price');
            $table->boolean('split_across_variants')->default(false)->after('minimum_order_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'minimum_order_quantity',
                'split_across_variants',
            ]);
        });
    }
};
