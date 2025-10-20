<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Permisos
        $scan        = Permission::firstOrCreate(['name' => 'scan defects']);
        $manage      = Permission::firstOrCreate(['name' => 'manage defects']);
        $reports     = Permission::firstOrCreate(['name' => 'view reports']);
        $manageUsers = Permission::firstOrCreate(['name' => 'manage users']);

        // Roles
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $supervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $operario   = Role::firstOrCreate(['name' => 'operario']);

        // Asignar permisos a roles
        $admin->syncPermissions(Permission::all());
        $supervisor->syncPermissions([$manage, $reports]);
        $operario->syncPermissions([$scan]);

        // Usuario admin (si no existe)
        $user = User::firstOrCreate(
            ['email' => 'admin@empresa.test'],
            ['name' => 'Admin', 'password' => Hash::make('secret123')]
        );
        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
        }
    }
}
