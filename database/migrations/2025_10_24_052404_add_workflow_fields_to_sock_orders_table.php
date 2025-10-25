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
        Schema::table('sock_orders', function (Blueprint $table) {
            // Sock Details
            $table->string('thread_color')->nullable();
            $table->string('thread_colors')->nullable();
            $table->string('grip_design_file')->nullable();
            $table->string('packaging_design_file')->nullable();
            $table->string('logo_style')->nullable();
            $table->string('embroidered_logo_thread_colors')->nullable();
            $table->string('grip_color')->nullable();
            
            // Sample workflow
            $table->string('sample_image_1')->nullable();
            $table->string('sample_image_2')->nullable();
            $table->boolean('sample_approved')->nullable();
            $table->text('revision_notes')->nullable();
            
            // Production workflow
            $table->date('ship_date_eta')->nullable();
            $table->boolean('is_shipped')->default(false);
            $table->string('tracking_number')->nullable();
            $table->json('sock_images')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sock_orders', function (Blueprint $table) {
            $table->dropColumn([
                'thread_color',
                'thread_colors',
                'grip_design_file',
                'packaging_design_file',
                'logo_style',
                'embroidered_logo_thread_colors',
                'grip_color',
                'sample_image_1',
                'sample_image_2',
                'sample_approved',
                'revision_notes',
                'ship_date_eta',
                'is_shipped',
                'tracking_number',
                'sock_images',
            ]);
        });
    }
};
