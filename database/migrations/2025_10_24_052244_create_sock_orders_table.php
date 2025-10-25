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
        Schema::create('sock_orders', function (Blueprint $table) {
            $table->id();
            $table->string('eid')->unique();
            $table->string('order_number');
            $table->string('customer_name');
            $table->date('order_date');
            $table->string('status');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['order_number', 'status']);
            $table->index('customer_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sock_orders');
    }
};
