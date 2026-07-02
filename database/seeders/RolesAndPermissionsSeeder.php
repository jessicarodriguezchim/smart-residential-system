<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrador',
            'description' => 'Administrador del sistema con acceso total.',
        ]);

        $vigilanteRole = Role::create([
            'name' => 'vigilante',
            'display_name' => 'Vigilante',
            'description' => 'Personal de caseta de control de acceso.',
        ]);

        $residenteRole = Role::create([
            'name' => 'residente',
            'display_name' => 'Residente',
            'description' => 'Propietario o arrendatario de lote.',
        ]);

        // 2. Create Permissions
        $permissions = [
            'users.manage' => ['display' => 'Gestionar usuarios', 'desc' => 'Crear, editar y eliminar usuarios de la plataforma.'],
            'lots.manage' => ['display' => 'Gestionar lotes', 'desc' => 'Crear, editar y actualizar lotes e inventario.'],
            'owners.manage' => ['display' => 'Gestionar propietarios', 'desc' => 'Administrar expedientes y perfiles de propietarios.'],
            'finance.view' => ['display' => 'Ver finanzas', 'desc' => 'Consultar ingresos, estados de cuenta globales.'],
            'finance.manage' => ['display' => 'Administrar finanzas', 'desc' => 'Generar cuotas, recargos y aprobar pagos.'],
            'visits.register' => ['display' => 'Registrar visitas', 'desc' => 'Controlar entradas y salidas en caseta.'],
            'visits.view' => ['display' => 'Consultar visitas', 'desc' => 'Ver bitácora de visitas.'],
            'profile.view' => ['display' => 'Ver perfil propio', 'desc' => 'Acceso a la información personal.'],
            'payments.make' => ['display' => 'Realizar pagos', 'desc' => 'Pagar cuotas de mantenimiento en línea.'],
        ];

        $permissionModels = [];
        foreach ($permissions as $name => $details) {
            $permissionModels[$name] = Permission::create([
                'name' => $name,
                'display_name' => $details['display'],
                'description' => $details['desc'],
            ]);
        }

        // 3. Assign Permissions to Roles
        // Admin: all
        $adminRole->permissions()->sync(array_column($permissionModels, 'id'));

        // Vigilante
        $vigilanteRole->permissions()->sync([
            $permissionModels['visits.register']->id,
            $permissionModels['visits.view']->id,
            $permissionModels['profile.view']->id,
        ]);

        // Residente
        $residenteRole->permissions()->sync([
            $permissionModels['profile.view']->id,
            $permissionModels['payments.make']->id,
        ]);
    }
}
