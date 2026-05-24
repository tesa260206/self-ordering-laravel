<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained('menus')->restrictOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 12, 2); // Harga saat dipesan (mencegah bug jika harga master menu diubah nanti)
            $table->decimal('subtotal', 12, 2);
            $table->text('notes')->nullable(); // Catatan: "Jangan pedas", dll
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};