<?php

namespace Database\Seeders;

use App\Models\ZonaCatalogo;
use Illuminate\Database\Seeder;
use App\Models\ZonaSocial;

class ZonasCatalogoSeeder extends Seeder
{
    public function run()
    {
        // Definir las zonas sociales predefinidas
        $zonas = [
            ['icono' => 'Imagenes/otros/swimming.png', 'titulo' => 'Piscina para adultos y niños'],
            ['icono' => 'Imagenes/otros/barbeque.png', 'titulo' => 'Zona BBQ'],
            ['icono' => 'Imagenes/otros/pin.png', 'titulo' => 'Zona Verde'],
            ['icono' => 'Imagenes/otros/transport.png', 'titulo' => 'Parqueadero'],
            
        ];

        // Insertar las zonas sociales
        foreach ($zonas as $zona) {
            ZonaCatalogo::create($zona);
        }
    }
}
