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
        Schema::create('vendor_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->string('transaction_type'); // 'invoice', 'payment', 'credit', 'debit'
            $table->string('reference_number')->nullable(); // Invoice number, payment reference, etc.
            $table->text('description');
            $table->decimal('amount', 10, 2); // Positive for money owed to us, negative for money we owe
            $table->date('transaction_date');
            $table->date('due_date')->nullable(); // For invoices
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('job_reference')->nullable(); // Reference to specific job/work
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_transactions');
    }
};
