<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('thermal_printer_width')->default('80mm')->after('logo'); // '58mm' atau '80mm'
            $table->text('receipt_footer_text')->nullable()->after('thermal_printer_width'); // Teks footer struk
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['thermal_printer_width', 'receipt_footer_text']);
        });
    }
};
