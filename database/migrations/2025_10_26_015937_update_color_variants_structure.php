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
        // No database changes needed - color_variants is already a JSON field
        // The structure will be handled by the application layer
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No database changes needed
    }
};