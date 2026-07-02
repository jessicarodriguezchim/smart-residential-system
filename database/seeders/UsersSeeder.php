<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $vigilanteRole = Role::where('name', 'vigilante')->first();
        $residenteRole = Role::where('name', 'residente')->first();

        // 1. Create Admin
        $admin = User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@fracc.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach($adminRole);

        // 2. Create Vigilante
        $vigilante = User::create([
            'name' => 'Vigilante Caseta 1',
            'email' => 'vigilante@fracc.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $vigilante->roles()->attach($vigilanteRole);

        // 3. Create Residents (User accounts)
        $res1 = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $res1->roles()->attach($residenteRole);

        $res2 = User::create([
            'name' => 'María López',
            'email' => 'maria.lopez@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $res2->roles()->attach($residenteRole);
    }
}
