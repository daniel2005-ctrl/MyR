<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear el usuario admin
        Usuario::create([
            'id' => 1,
            'nombre' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
            'tipo_permiso_id' => 1
        ]);
    }
}