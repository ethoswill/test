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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Original file name
            $table->string('file_name'); // Stored file name
            $table->string('path'); // Storage path
            $table->string('disk')->default('public'); // Storage disk
            $table->unsignedBigInteger('size')->nullable(); // File size in bytes
            $table->string('mime_type')->nullable(); // MIME type
            $table->text('url')->nullable(); // Full URL to the file
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();
            
            // Indexes for better search performance
            $table->index('name');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
