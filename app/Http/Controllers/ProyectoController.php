<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    private $proyectos = [
        '1' => [
            'titulo' => 'MYR72',
            'imagenesHeader' => [
                'imagenes/myr72/proyecto 2.png',
                'imagenes/myr72/proyecto 3.png'
            ],
            'descripcion' => [
                'MYR 72 es un proyecto que hace parte del desarrollo urbanístico Plan Parcial Varsovia.',
                'Es un conjunto cerrado de 1 torre, 40 apartamentos de 2 y 3 alcobas.'
            ],
            'precio' => ['min' => '115 SMLV', 'max' => '135 SMLV'],
            'area' => ['min' => '54 m²', 'max' => '70 m²'],
            'zonas' => [
                ['icono' => 'imagenes/otros/swimming.png', 'titulo' => 'Piscinas para adultos y niños'],
                ['icono' => 'imagenes/otros/transport.png', 'titulo' => 'Parqueadero comunal'],
                ['icono' => 'imagenes/otros/barbeque.png', 'titulo' => 'Zona BBQ'],
                ['icono' => 'imagenes/otros/pin.png', 'titulo' => 'Zona verde']
            ],
            'apartamentos' => [
                'imagenes/myr72/apartamento1.jpg',
                'imagenes/myr72/apartamento2.jpg'
            ],
            'direccion' => 'Carrera 2ª A Sur, Ibagué, Tolima',
            'mapa' => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d565.803754602264!2d-75.19187708973818!3d4.433238114786227!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2sco!4v1732904635802!5m2!1ses!2sco'
        ],

        '2' => [
            'titulo' => 'Altos de Rincón de Varsovia',
            'imagenesHeader' => [
                'imagenes/altos/Altos2.jpeg',
                'imagenes/altos/cocenos.png'
            ],
            'descripcion' => [
                'Altos de Rincón de Varsovia es un proyecto que hace parte del desarrollo urbanístico Plan Parcial Varsovia.',
                'Es un conjunto cerrado de 1 torre, 40 apartamentos de 2 y 3 alcobas.'
            ],
            'precio' => ['min' => '115 SMLV', 'max' => '135 SMLV'],
            'area' => ['min' => '54 m²', 'max' => '72 m²'],
            'zonas' => [
                ['icono' => 'imagenes/otros/transport.png', 'titulo' => 'Parqueadero comunal'],
                ['icono' => 'imagenes/otros/barbeque.png', 'titulo' => 'Zona BBQ']
            ],
            'apartamentos' => [
                'imagenes/myr72/apartamento1.jpg',
                'imagenes/myr72/apartamento2.jpg'
            ],
            'direccion' => 'Carrera 2ª A Sur, Ibagué, Tolima',
            'mapa' => 'https://www.google.com/maps/embed?...'
        ],

        '3' => [
            'titulo' => 'Prados de Varsovia',
            'imagenesHeader' => [
                'imagenes/prados/5. Medianera tipo A.jpg',
                'imagenes/prados/Fondo 4.jpg'
            ],
            'descripcion' => [
                'Prados de Varsovia, inicialmente vendido como un loteo con modelo de construcción y licencia.',
                'Es un barrio.'
            ],
            'precio' => ['min' => '80 Millones', 'max' => null],
            'area' => ['min' => '6m x 12m', 'max' => null],
            'zonas' => [],
            'apartamentos' => [],
            'direccion' => 'Cra. 2 #74, Ibagué, Tolima',
            'mapa' => 'https://www.google.com/maps/embed?...'
        ],

        '4' => [
            'titulo' => 'Rincón de Varsovia',
            'imagenesHeader' => [
                'imagenes/rincon/Rincon2.jpeg',
                'imagenes/rincon/Rincon3.jpeg'
            ],
            'descripcion' => [
                'Rincón de Varsovia, inicialmente vendido como un loteo con modelo de construcción y licencia.',
                'Es un barrio.'
            ],
            'precio' => ['min' => '80 Millones', 'max' => null],
            'area' => ['min' => '6m x 12m', 'max' => null],
            'zonas' => [],
            'apartamentos' => [],
            'direccion' => 'Carrera 2ª A Sur, Ibagué, Tolima',
            'mapa' => 'https://www.google.com/maps/embed?...'
        ],
    ];

    // Método para mostrar un proyecto específico
    public function show($id)
    {
        if (!array_key_exists($id, $this->proyectos)) {
            abort(404, 'Proyecto no encontrado');
        }

        $proyecto = $this->proyectos[$id];
        return view('proyecto', compact('proyecto'));
    }
}
