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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_address')->nullable();
            $table->string('client_phone')->nullable();
            $table->text('description')->nullable();
            $table->json('line_items'); // For detailed line items
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->date('po_date');
            $table->date('delivery_date')->nullable();
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected', 'completed', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('contact_person')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
