<?php

namespace Database\Seeders;

use App\Models\Lot;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Database\Seeder;

class LotsAndOwnersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Resident users to link profiles
        $user1 = User::where('email', 'juan.perez@example.com')->first();
        $user2 = User::where('email', 'maria.lopez@example.com')->first();

        // 1. Create Owners
        $owner1 = Owner::create([
            'user_id' => $user1->id,
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'phone' => '5551234567',
            'email' => 'juan.perez@example.com',
            'status' => 'activo',
        ]);

        $owner2 = Owner::create([
            'user_id' => $user2->id,
            'first_name' => 'María',
            'last_name' => 'López',
            'phone' => '5557654321',
            'email' => 'maria.lopez@example.com',
            'status' => 'activo',
        ]);

        // An owner with no user account yet
        $owner3 = Owner::create([
            'user_id' => null,
            'first_name' => 'Roberto',
            'last_name' => 'Gómez',
            'phone' => '5559876543',
            'email' => 'roberto.gomez@example.com',
            'status' => 'activo',
        ]);

        // 2. Create Lots and Link to Owners
        Lot::create([
            'owner_id' => $owner1->id,
            'number' => 'LOT-01',
            'street' => 'Av. Tulipanes',
            'surface_area' => 250.00,
            'status' => 'vendido',
            'notes' => 'Propiedad principal del residente Juan Pérez.',
        ]);

        Lot::create([
            'owner_id' => $owner2->id,
            'number' => 'LOT-02',
            'street' => 'Av. Tulipanes',
            'surface_area' => 250.00,
            'status' => 'vendido',
            'notes' => 'Propiedad residencial de María López.',
        ]);

        Lot::create([
            'owner_id' => $owner2->id,
            'number' => 'LOT-03',
            'street' => 'Av. Tulipanes',
            'surface_area' => 300.00,
            'status' => 'apartado',
            'notes' => 'Segundo lote adquirido por María López (Apartado).',
        ]);

        Lot::create([
            'owner_id' => $owner3->id,
            'number' => 'LOT-04',
            'street' => 'Calle Rosas',
            'surface_area' => 200.00,
            'status' => 'vendido',
            'notes' => 'Propiedad de Roberto Gómez sin cuenta de portal.',
        ]);

        Lot::create([
            'owner_id' => null,
            'number' => 'LOT-05',
            'street' => 'Calle Rosas',
            'surface_area' => 200.00,
            'status' => 'disponible',
            'notes' => 'Lote baldío disponible para venta.',
        ]);

        Lot::create([
            'owner_id' => null,
            'number' => 'LOT-06',
            'street' => 'Calle Rosas',
            'surface_area' => 220.00,
            'status' => 'disponible',
            'notes' => 'Lote baldío disponible para venta.',
        ]);
    }
}
