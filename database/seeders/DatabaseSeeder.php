<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Cargar permisos primero
        $this->call([
            TipoPermisoSeeder::class
        ]);

        // Crear el usuario admin
        Usuario::create([
            'id' => 1,
            'nombre' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
            'tipo_permiso_id' => 1
        ]);

        // 👉 Primero cargar zonas sociales
        $this->call([
            ZonasCatalogoSeeder::class
        ]);

        // 👉 Luego cargar los proyectos que usan esas zonas
        $this->call([
            ProyectoSeeder::class
        ]);
        $this->call([
    EstadoSeeder::class,
]);
    }
}
