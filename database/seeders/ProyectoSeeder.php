<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proyecto;
use App\Models\ZonaCatalogo;

class ProyectoSeeder extends Seeder
{
    public function run()
    {
        // Obtener zonas por título
        $zonas = ZonaCatalogo::all()->keyBy('titulo');

        // Proyecto 1
        $proyecto1 = Proyecto::create([
            'nombre_pro' => 'MYR72',
            'tipo_pro' => 'vis',
            'descripcion' => ['MYR 72 es un proyecto que hace parte del desarrollo urbanístico Plan Parcial Varsovia.'],
            'terminado' => true,
            'precio' => ['min' => 100000, 'max' => 150000],
            'area' => ['min' => 54.0, 'max' => 70.0],
            'ubicacion_pro' => 'Rincon de Varsovia, Carrera 2ª A Sur, Ibagué, Tolima',
            'imagenes_pro' => [
                'imagenes/myr72/proyecto2.png',
                'imagenes/myr72/proyecto3.png'
            ],
            'videos_pro' => [],
            'apartamentos' => [
                'imagenes/myr72/apartamento1.jpg',
                'imagenes/myr72/apartamento2.jpg', 
            ],
            'id_tipo_permiso' => 1,
            'id_estado' => 2,
        ]);

        // Asociar zonas al proyecto 1
        $proyecto1->zonasCatalogo()->attach([
            $zonas['Piscina para adultos y niños']->id,
            $zonas['Parqueadero']->id,
            $zonas['Zona BBQ']->id,
            $zonas['Zona Verde']->id,
        ]);

        // Proyecto 2
        $proyecto2 = Proyecto::create([
            'nombre_pro' => 'Altos de Rincón de Varsovia',
            'tipo_pro' => 'vis',
            'descripcion' => ['Altos de Rincón de Varsovia es un proyecto que hace parte del desarrollo urbanístico Plan Parcial Varsovia.'],
            'terminado' => true,
            'precio' => ['min' => 120000, 'max' => 170000],
            'area' => ['min' => 54.0, 'max' => 72.0],
            'ubicacion_pro' => 'Rincon de Varsovia, Carrera 2ª A Sur, Ibagué, Tolima',
            'imagenes_pro' => [
                'imagenes/altos/Altos1.jpeg',
                'imagenes/cocenos.png'
            ],
            'videos_pro' => [],
            'apartamentos' => [
                'imagenes/myr72/apartamento1.jpg',
                'imagenes/myr72/apartamento2.jpg',
            ],
            'id_tipo_permiso' => 1,
            'id_estado' => 1,
        ]);

        // Asociar zonas al proyecto 2
        $proyecto2->zonasCatalogo()->attach([
            $zonas['Parqueadero']->id,
            $zonas['Zona BBQ']->id,
        ]);

        // Proyecto 3
        $proyecto3 = Proyecto::create([
            'nombre_pro' => 'Prados de Varsovia',
            'tipo_pro' => 'vis',
            'descripcion' => ['Prados de Varsovia, inicialmente vendido como un loteo con modelo de construcción y licencia. Es un barrio.'],
            'terminado' => true,
            'precio' => ['min' => 90000, 'max' => 130000],
            'area' => ['min' => 72.0, 'max' => 72.0],
            'ubicacion_pro' => 'Rincon de Varsovia, Carrera 2ª A Sur, Ibagué, Tolima',
            'imagenes_pro' => [
                'imagenes/prados/Prados1.jpg',
                'imagenes/prados/Prados2.jpg'
            ],
            'videos_pro' => [],
            'apartamentos' => [],
            'id_tipo_permiso' => 1,
            'id_estado' => 1,
        ]);

        // Asociar zonas al proyecto 3
        $proyecto3->zonasCatalogo()->attach([
            $zonas['Parqueadero']->id,
            $zonas['Zona BBQ']->id,
        ]);

        // Proyecto 4
        $proyecto4 = Proyecto::create([
            'nombre_pro' => 'Rincón de Varsovia',
            'tipo_pro' => 'vis',
            'descripcion' => ['Rincón de Varsovia, inicialmente vendido como un loteo con modelo de construcción y licencia. Es un barrio.'],
            'terminado' => true,
            'precio' => ['min' => 85000, 'max' => 120000],
            'area' => ['min' => 72.0, 'max' => 72.0],
            'ubicacion_pro' => 'Rincon de Varsovia, Carrera 2ª A Sur, Ibagué, Tolima',
            'imagenes_pro' => [
                'imagenes/rincon/Rincon1.jpeg',
                'imagenes/rincon/Rincon2.jpeg'
            ],
            'videos_pro' => [],
            'apartamentos' => [],
            'id_tipo_permiso' => 1,
            'id_estado' => 1,
        ]);

        // Asociar zonas al proyecto 4
        $proyecto4->zonasCatalogo()->attach([
            $zonas['Parqueadero']->id,
            $zonas['Zona BBQ']->id,
        ]);
    }
}