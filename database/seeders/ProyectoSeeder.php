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

        // Proyecto 1 - MYR72
        $proyecto1 = Proyecto::updateOrCreate(
            ['nombre_pro' => 'MYR72'],
            [
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
            ]
        );

        // Asociar zonas al proyecto 1
        $proyecto1->zonasCatalogo()->sync([
            $zonas['Piscina para adultos y niños']->id,
            $zonas['Parqueadero']->id,
            $zonas['Zona BBQ']->id,
            $zonas['Zona Verde']->id,
        ]);

        // Proyecto 2 - Altos de Rincón de Varsovia
        $proyecto2 = Proyecto::updateOrCreate(
            ['nombre_pro' => 'Altos de Rincón de Varsovia'],
            [
                'slug' => 'altos-de-rincon-de-varsovia',
                'tipo_pro' => 'VIS',
                'descripcion' => ['Altos de Rincón de Varsovia es un proyecto que hace parte del desarrollo urbanístico Plan Parcial Varsovia.'],
                'terminado' => false,
                'precio' => ['min' => '120000', 'max' => '170000'],
                'area' => ['min' => '54', 'max' => '72'],
                'parqueadero' => null,
                'ubicacion_pro' => 'Rincon de Varsovia, Carrera 2ª A Sur, Ibagué, Tolima',
                'imagenes_pro' => [
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112052/proyectos/altos/cocenos.png',
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112045/proyectos/altos/Altos2.jpg'
                ],
                'videos_pro' => [],
                'imagenes_header' => 'https://res.cloudinary.com/dtwtippni/image/upload/v1750112044/proyectos/altos/Altos1.jpg',
                'apartamentos' => [
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112047/proyectos/altos/WhatsApp_Image_2024-11-23_at_8.14.26_PM_2.jpg'
                ],
                'id_tipo_permiso' => 1,
                'id_estado' => 1,
                'visitas' => 2,
            ]
        );

        // Asociar zonas al proyecto 2
        $proyecto2->zonasCatalogo()->sync([
            $zonas['Parqueadero']->id,
            $zonas['Zona BBQ']->id,
        ]);

        // Proyecto 3 - Prados de Varsovia (ACTUALIZADO)
        $proyecto3 = Proyecto::updateOrCreate(
            ['nombre_pro' => 'Prados de Varsovia'],
            [
                'slug' => 'prados-de-varsovia',
                'tipo_pro' => 'VIS',
                'descripcion' => ['Prados de Varsovia, inicialmente vendido como un loteo con modelo de construcción y licencia. Es un barrio.'],
                'terminado' => true,
                'precio' => ['min' => '90000', 'max' => '130000'],
                'area' => ['min' => '72', 'max' => '72'],
                'parqueadero' => null,
                'ubicacion_pro' => 'Rincon de Varsovia, Carrera 2ª A Sur, Ibagué, Tolima',
                'imagenes_pro' => [
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112258/proyectos/prados/Prados2.jpg',
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112237/proyectos/prados/Fondo_4.jpg'
                ],
                'videos_pro' => [],
                'imagenes_header' => 'https://res.cloudinary.com/dtwtippni/image/upload/v1750112225/proyectos/prados/5._Medianera_tipo_A.jpg',
                'apartamentos' => [],
                'id_tipo_permiso' => 1,
                'id_estado' => 1,
                'visitas' => 5,
            ]
        );

        // Asociar zonas al proyecto 3
        $proyecto3->zonasCatalogo()->sync([
            $zonas['Parqueadero']->id,
            $zonas['Zona BBQ']->id,
        ]);

        // Proyecto 4 - Rincón de Varsovia (ACTUALIZADO)
        $proyecto4 = Proyecto::updateOrCreate(
            ['nombre_pro' => 'Rincón de Varsovia'],
            [
                'slug' => 'rincon-de-varsovia',
                'tipo_pro' => 'VIS',
                'descripcion' => ['Rincón de Varsovia, inicialmente vendido como un loteo con modelo de construcción y licencia. Es un barrio.'],
                'terminado' => true,
                'precio' => ['min' => '85000', 'max' => '120000'],
                'area' => ['min' => '72', 'max' => '72'],
                'parqueadero' => null,
                'ubicacion_pro' => 'Rincon de Varsovia, Carrera 2ª A Sur, Ibagué, Tolima',
                'imagenes_pro' => [
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112263/proyectos/rincon/Rincon2.jpg',
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112261/proyectos/rincon/Rincon1.jpg'
                ],
                'videos_pro' => [],
                'imagenes_header' => 'https://res.cloudinary.com/dtwtippni/image/upload/v1750112265/proyectos/rincon/Rincon3.jpg',
                'apartamentos' => [
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112272/proyectos/rincon/WhatsApp_Image_2024-11-23_at_8.21.05_PM.jpg',
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112269/proyectos/rincon/WhatsApp_Image_2024-11-23_at_8.21.04_PM.jpg'
                ],
                'id_tipo_permiso' => 1,
                'id_estado' => 1,
                'visitas' => 3,
            ]
        );

        // Asociar zonas al proyecto 4
        $proyecto4->zonasCatalogo()->sync([
            $zonas['Parqueadero']->id,
            $zonas['Zona BBQ']->id,
        ]);
    }
}