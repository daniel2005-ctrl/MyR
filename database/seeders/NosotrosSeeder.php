<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nosotros;

class NosotrosSeeder extends Seeder
{
    public function run(): void
    {
        Nosotros::updateOrCreate(
            ['id' => 1],
            [
                'titulo' => 'Nosotros',
                'introduccion' => 'https://res.cloudinary.com/dtwtippni/image/upload/v1750113028/cotizaciones_r1ityx.png',
                'vision' => 'https://res.cloudinary.com/dtwtippni/image/upload/v1750112042/proyectos/Nosotros/vision.png',
                'mision' => 'https://res.cloudinary.com/dtwtippni/image/upload/v1750112033/proyectos/Nosotros/mision.png',
                'valores' => json_encode([
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112031/proyectos/Nosotros/colabora.jpg',
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112037/proyectos/Nosotros/soli.jpg',
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112036/proyectos/Nosotros/respeto.jpg',
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112034/proyectos/Nosotros/renspo.jpg',
                    'https://res.cloudinary.com/dtwtippni/image/upload/v1750112039/proyectos/Nosotros/trans.jpg'
                ])
            ]
        );
    }
}