<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing CAD file paths from private to public
        DB::table('products')
            ->whereNotNull('cad_download')
            ->update([
                'cad_download' => DB::raw("REPLACE(cad_download, 'private/', 'public/')")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert CAD file paths from public to private
        DB::table('products')
            ->whereNotNull('cad_download')
            ->update([
                'cad_download' => DB::raw("REPLACE(cad_download, 'public/', 'private/')")
            ]);
    }
};