<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thread_colors', function (Blueprint $table) {
            $table->id();
            $table->string('color_name');
            $table->string('color_code')->nullable();
            $table->string('hex_code')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('thread_type')->nullable();
            $table->text('description')->nullable();
            $table->string('availability')->nullable();
            $table->text('usage_notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thread_colors');
    }
};

