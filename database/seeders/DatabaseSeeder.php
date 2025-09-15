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
        // Ejecuta los seeders que preparaste
        $this->call([
            RolePermissionSeeder::class,
            DefectTypeSeeder::class,
            LocationSeeder::class,
            DefectSeeder::class,
        ]);

        // (opcional) Si quieres crear un usuario demo además:
        // \App\Models\User::factory()->create([
        //     'name'  => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
