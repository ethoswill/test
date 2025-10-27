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
        Schema::table('socks', function (Blueprint $table) {
            $table->string('ribbing_height')->nullable()->after('material');
            $table->string('fabric')->nullable()->after('ribbing_height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('socks', function (Blueprint $table) {
            $table->dropColumn(['ribbing_height', 'fabric']);
        });
    }
};