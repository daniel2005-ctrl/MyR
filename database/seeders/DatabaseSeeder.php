<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TipoPermisoSeeder::class
        ]);
    
        \App\Models\Usuario::create([
            'id' => 1,
            'nombre' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
            'tipo_permiso_id' => 1 // Asignar como admin
        ]);
    
        $this->call([
            ProyectoSeeder::class
        ]);
    }
    
}

   