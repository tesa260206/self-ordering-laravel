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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_number')->unique(); // Format: ORD-YYMMDD-XXXX
        $table->foreignId('table_id')->constrained('tables')->restrictOnDelete();
        $table->string('customer_name')->nullable();
        $table->decimal('subtotal', 12, 2);
        $table->decimal('tax', 12, 2)->default(0); // Pajak PPN
        $table->decimal('service_charge', 12, 2)->default(0);
        $table->decimal('discount', 12, 2)->default(0);
        $table->decimal('total_amount', 12, 2);
        
        // Status Order untuk Kitchen dan Cashier
        $table->enum('status', ['pending', 'cooking', 'ready', 'completed', 'cancelled'])->default('pending');
        $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
        
        $table->text('notes')->nullable(); // Catatan umum pesanan
        $table->timestamps();
        
        $table->index('order_number');
        $table->index('status');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
