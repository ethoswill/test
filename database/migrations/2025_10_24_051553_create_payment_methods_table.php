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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('card'); // card, bank_account, etc.
            $table->string('provider')->default('stripe'); // stripe, paypal, etc.
            $table->string('provider_id'); // External provider's payment method ID
            $table->string('card_brand')->nullable(); // visa, mastercard, amex, etc.
            $table->string('card_last_four', 4)->nullable();
            $table->string('card_exp_month', 2)->nullable();
            $table->string('card_exp_year', 4)->nullable();
            $table->string('card_holder_name')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Additional provider-specific data
            $table->timestamps();
            
            // Ensure only one default payment method per customer
            $table->unique(['customer_id', 'is_default'], 'unique_default_per_customer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};