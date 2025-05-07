<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proyecto;

class ProyectoSeeder extends Seeder
{
    public function run()
    {
        Proyecto::create([
            'nombre_pro' => 'MYR72',
            'tipo_pro' => 'vis', // Todos son 'vis'
            'descripcion' => 'MYR 72 es un proyecto que hace parte del desarrollo urbanístico Plan Parcial Varsovia. Es un conjunto cerrado de 1 torre, 40 apartamentos de 2 y 3 alcobas.',
            'terminado' => true,
            'imagenes_pro' => json_encode([
                'imagenes/myr72/proyecto 2.png',
                'imagenes/myr72/proyecto 3.png'
            ]),
            'videos_pro' => json_encode([]),
            'ubicacion_pro' => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d565.803754602264!2d-75.19187708973818!3d4.433238114786227!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2sco!4v1732904635802!5m2!1ses!2sco',
            'area_min' => 54.0, // Tamaño mínimo en m²
            'area_max' => 70.0, // Tamaño máximo en m²
            'id_tipo_permiso' => 1, // Asegúrate de tener un tipo de permiso con ID 1
        ]);

        Proyecto::create([
            'nombre_pro' => 'Altos de Rincón de Varsovia',
            'tipo_pro' => 'vis', // Todos son 'vis'
            'descripcion' => 'Altos de Rincón de Varsovia es un proyecto que hace parte del desarrollo urbanístico Plan Parcial Varsovia. Es un conjunto cerrado de 1 torre, 40 apartamentos de 2 y 3 alcobas.',
            'terminado' => true,
            'imagenes_pro' => json_encode([
                'imagenes/altos/Altos2.jpeg',
                'imagenes/altos/cocenos.png'
            ]),
            'videos_pro' => json_encode([]),
            'ubicacion_pro' => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d565.803754602264!2d-75.19187708973818!3d4.433238114786227!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2sco!4v1732904635802!5m2!1ses!2sco',
            'area_min' => 54.0, // Tamaño mínimo en m²
            'area_max' => 72.0, // Tamaño máximo en m²
            'id_tipo_permiso' => 1,
        ]);

        Proyecto::create([
            'nombre_pro' => 'Prados de Varsovia',
            'tipo_pro' => 'vis', // Todos son 'vis'
            'descripcion' => 'Prados de Varsovia, inicialmente vendido como un loteo con modelo de construcción y licencia. Es un barrio.',
            'terminado' => true,
            'imagenes_pro' => json_encode([
                'imagenes/prados/5. Medianera tipo A.jpg',
                'imagenes/prados/Fondo 4.jpg'
            ]),
            'videos_pro' => json_encode([]),
            'ubicacion_pro' => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d565.803754602264!2d-75.19187708973818!3d4.433238114786227!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2sco!4v1732904635802!5m2!1ses!2sco',
            'area_min' => 72.0, // Tamaño mínimo en m²
            'area_max' => 72.0, // Tamaño máximo en m²
            'id_tipo_permiso' => 1,
        ]);

        Proyecto::create([
            'nombre_pro' => 'Rincón de Varsovia',
            'tipo_pro' => 'vis', // Todos son 'vis'
            'descripcion' => 'Rincón de Varsovia, inicialmente vendido como un loteo con modelo de construcción y licencia. Es un barrio.',
            'terminado' => true,
            'imagenes_pro' => json_encode([
                'imagenes/rincon/Rincon2.jpeg',
                'imagenes/rincon/Rincon3.jpeg'
            ]),
            'videos_pro' => json_encode([]),
            'ubicacion_pro' => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d565.803754602264!2d-75.19187708973818!3d4.433238114786227!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2sco!4v1732904635802!5m2!1ses!2sco',
            'area_min' => 72.0, // Tamaño mínimo en m²
            'area_max' => 72.0, // Tamaño máximo en m²
            'id_tipo_permiso' => 1,
        ]);
    }
}
