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
        Schema::create('team_notes', function (Blueprint $table) {
            $table->id();
            $table->string('page')->unique(); // e.g., 'sock-grips', 'grips', 'thread-book-colors'
            $table->text('content')->nullable(); // The notes content
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_notes');
    }
};
