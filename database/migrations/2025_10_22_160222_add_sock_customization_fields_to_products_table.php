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
            $table->string('thread_color')->nullable();
            $table->string('thread_colors')->nullable();
            $table->string('logo_style')->nullable();
            $table->string('embroidered_logo_thread_colors')->nullable();
            $table->string('grip_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'thread_color',
                'thread_colors',
                'logo_style',
                'embroidered_logo_thread_colors',
                'grip_color',
            ]);
        });
    }
};
