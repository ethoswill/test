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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('medusa_id')->unique(); // Medusa customer ID
            $table->string('email')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->json('metadata')->nullable(); // Store additional Medusa metadata
            $table->boolean('has_account')->default(false);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('synced_at')->nullable(); // When we last synced from Medusa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
