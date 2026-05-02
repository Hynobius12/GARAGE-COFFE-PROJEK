<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $ownerRole = Role::create(['name' => 'owner']);
        $cashierRole = Role::create(['name' => 'cashier']);
        $baristaRole = Role::create(['name' => 'barista']);

        // Create Users and assign roles
        $owner = User::firstOrCreate([
            'email' => 'owner@garagecoffee.com',
        ], [
            'name' => 'Owner Garage',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'is_active' => true,
        ]);
        $owner->assignRole($ownerRole);

        $cashier = User::firstOrCreate([
            'email' => 'kasir@garagecoffee.com',
        ], [
            'name' => 'Kasir 1',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'is_active' => true,
        ]);
        $cashier->assignRole($cashierRole);

        $barista = User::firstOrCreate([
            'email' => 'barista@garagecoffee.com',
        ], [
            'name' => 'Barista 1',
            'password' => Hash::make('password'),
            'role' => 'barista',
            'is_active' => true,
        ]);
        $barista->assignRole($baristaRole);
    }
}
