<?php

namespace Database\Seeders;

use App\Models\ZonaCatalogo;
use Illuminate\Database\Seeder;
use App\Models\ZonaSocial;

class ZonasCatalogoSeeder extends Seeder
{
    public function run()
    {
        // Definir las zonas sociales predefinidas con URLs de Cloudinary actualizadas
        $zonas = [
            [
                'icono' => 'https://res.cloudinary.com/dtwtippni/image/upload/v1750112202/proyectos/otros/swimming.png', 
                'titulo' => 'Piscina para adultos y niños'
            ],
            [
                'icono' => 'https://res.cloudinary.com/dtwtippni/image/upload/v1750112194/proyectos/otros/barbeque.png', 
                'titulo' => 'Zona BBQ'
            ],
            [
                'icono' => 'https://res.cloudinary.com/dtwtippni/image/upload/v1750112201/proyectos/otros/pin.png', 
                'titulo' => 'Zona Verde'
            ],
            [
                'icono' => 'https://res.cloudinary.com/dtwtippni/image/upload/v1750112204/proyectos/otros/transport.png', 
                'titulo' => 'Parqueadero'
            ],
        ];

        // Insertar o actualizar las zonas sociales
        foreach ($zonas as $zona) {
            ZonaCatalogo::updateOrCreate(
                ['titulo' => $zona['titulo']],
                ['icono' => $zona['icono']]
            );
        }
    }
}
