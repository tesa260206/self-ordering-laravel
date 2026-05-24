<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('resto_name')->default('Self Order System');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->decimal('tax', 5, 2)->default(0); // Persentase Pajak, misal 11.00
            $table->decimal('service_charge', 5, 2)->default(0); // Persentase Service Charge
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};