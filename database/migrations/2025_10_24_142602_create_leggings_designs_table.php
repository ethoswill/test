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
        Schema::create('leggings_designs', function (Blueprint $table) {
            $table->id();
            
            // Step 1: Design Details
            $table->string('designer_name');
            $table->string('design_title');
            $table->text('design_description');
            $table->string('design_category'); // Athletic, Casual, Fashion, etc.
            $table->json('design_images'); // Array of image paths
            
            // Step 2: Measurements & Fit
            $table->string('target_size_range'); // XS-XXL, etc.
            $table->string('waist_rise'); // High, Mid, Low
            $table->string('inseam_length'); // 7/8, Full, Capri, etc.
            $table->string('fit_type'); // Skinny, Straight, Relaxed, etc.
            $table->text('special_fit_notes')->nullable();
            
            // Step 3: Materials & Construction
            $table->string('fabric_type'); // Cotton, Polyester, Spandex, etc.
            $table->string('fabric_weight'); // Light, Medium, Heavy
            $table->string('stretch_percentage')->nullable();
            $table->string('waistband_type'); // Elastic, Drawstring, etc.
            $table->text('construction_details')->nullable();
            $table->json('color_options'); // Array of color choices
            
            // Step 4: Submission Details
            $table->string('submission_status')->default('draft'); // draft, submitted, under_review, approved, rejected
            $table->text('additional_notes')->nullable();
            $table->string('contact_email');
            $table->string('phone_number')->nullable();
            $table->date('target_launch_date')->nullable();
            $table->decimal('estimated_price', 8, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leggings_designs');
    }
};
