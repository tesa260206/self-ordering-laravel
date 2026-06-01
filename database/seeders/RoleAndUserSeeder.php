<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
       
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleCashier = Role::create(['name' => 'cashier']);
        $roleKitchen = Role::create(['name' => 'kitchen']);

        // Create Users & Assign Roles
        $admin = User::create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $admin->assignRole($roleAdmin);

        $cashier = User::create([
            'name' => 'Cashier Utama',
            'username' => 'cashier',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $cashier->assignRole($roleCashier);

        $kitchen = User::create([
            'name' => 'Kitchen Head',
            'username' => 'kitchen',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $kitchen->assignRole($roleKitchen);
    }
}