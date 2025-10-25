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
            $table->string('minimums')->nullable()->after('product_type');
            $table->string('starting_from_price')->nullable()->after('minimums');
            $table->string('fabric')->nullable()->after('starting_from_price');
            $table->string('how_it_fits')->nullable()->after('fabric');
            $table->text('care_instructions')->nullable()->after('how_it_fits');
            $table->string('lead_times')->nullable()->after('care_instructions');
            $table->string('available_sizes')->nullable()->after('lead_times');
            $table->string('customization_methods')->nullable()->after('available_sizes');
            $table->string('model_size')->nullable()->after('customization_methods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'minimums',
                'starting_from_price',
                'fabric',
                'how_it_fits',
                'care_instructions',
                'lead_times',
                'available_sizes',
                'customization_methods',
                'model_size',
            ]);
        });
    }
};
