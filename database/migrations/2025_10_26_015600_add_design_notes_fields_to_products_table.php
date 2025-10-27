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
            $table->string('cad_download')->nullable();
            $table->string('tone_on_tone_lighter', 7)->nullable();
            $table->string('tone_on_tone_darker', 7)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['cad_download', 'tone_on_tone_lighter', 'tone_on_tone_darker']);
        });
    }
};