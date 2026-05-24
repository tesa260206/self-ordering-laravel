<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Comment atau hapus factory bawaan Laravel
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Panggil seeder Role dan User yang sudah kita buat
        $this->call([
            RoleAndUserSeeder::class,
        ]);

        // Berikan nilai default untuk Setting agar aplikasi tidak error saat migrate:fresh
        if (\App\Models\Setting::count() === 0) {
            \App\Models\Setting::create([
                'resto_name'            => 'Self Order System',
                'tax'                   => 11,
                'service_charge'        => 5,
                'thermal_printer_width' => '80mm',
                'receipt_footer_text'   => 'Terima Kasih atas kunjungan Anda!',
            ]);
        }
    }
}
