<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Seeder;

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        $configuraciones = [
            [
                'id' => 1,
                'clave' => 'imagen_fondo_index',
                'valor' => 'https://res.cloudinary.com/dtwtippni/image/upload/v1750112881/proyecto_2_dzhyvu.png',
                'descripcion' => 'Fondo automático del proyecto más visitado',
            ],
            [
                'id' => 2,
                'clave' => 'logo_navbar',
                'valor' => 'https://res.cloudinary.com/dtwtippni/image/upload/v1750112030/proyectos/Logo.png',
                'descripcion' => 'URL del logo del navbar',
                'created_at' => null,
                'updated_at' => null
            ]
        ];

        foreach ($configuraciones as $config) {
            Configuracion::updateOrCreate(
                ['clave' => $config['clave']], // Find by clave
                $config // Update or create with this data
            );
        }
    }
}