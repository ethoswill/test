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
        Schema::create('email_communications', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('purchase_order'); // purchase_order, invoice, general, etc.
            $table->string('related_type')->nullable(); // App\Models\PurchaseOrder, App\Models\Invoice, etc.
            $table->unsignedBigInteger('related_id')->nullable(); // ID of the related record
            $table->string('to_email');
            $table->string('to_name')->nullable();
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->string('subject');
            $table->text('body');
            $table->string('template_name')->nullable(); // Name of the template used
            $table->json('attachments')->nullable(); // Array of attachment file paths
            $table->enum('status', ['sent', 'failed', 'pending'])->default('sent');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at');
            $table->timestamps();
            
            // Index for efficient querying
            $table->index(['related_type', 'related_id']);
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_communications');
    }
};