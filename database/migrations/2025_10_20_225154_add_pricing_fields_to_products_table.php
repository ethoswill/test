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
            $table->decimal('ethos_cost_price', 10, 2)->nullable()->after('model_size');
            $table->decimal('customer_b2b_price', 10, 2)->nullable()->after('ethos_cost_price');
            $table->decimal('customer_dtc_price', 10, 2)->nullable()->after('customer_b2b_price');
            $table->decimal('franchisee_price', 10, 2)->nullable()->after('customer_dtc_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'ethos_cost_price',
                'customer_b2b_price',
                'customer_dtc_price',
                'franchisee_price',
            ]);
        });
    }
};
