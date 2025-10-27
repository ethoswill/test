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
            if (!Schema::hasColumn('products', 'tone_on_tone_lighter_hex')) {
                $table->string('tone_on_tone_lighter_hex', 7)->nullable();
            }
            if (!Schema::hasColumn('products', 'tone_on_tone_darker_hex')) {
                $table->string('tone_on_tone_darker_hex', 7)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['tone_on_tone_lighter_hex', 'tone_on_tone_darker_hex']);
        });
    }
};