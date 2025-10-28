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
            $table->string('color_name'); // e.g., "672"
            $table->string('color_code'); // Same as color_name - the thread number
            $table->string('image_url')->nullable(); // Image from Google Sheets
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thread_colors');
    }
};

