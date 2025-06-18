<?php

namespace Database\Seeders;

use App\Models\FooterSociales;
use Illuminate\Database\Seeder;

class FooterSocialSeeder extends Seeder
{
    public function run()
    {
        $redes = [
            ['nombre' => 'Facebook', 'url' => 'https://facebook.com', 'icono' => 'bi-facebook.svg'],
            ['nombre' => 'Instagram', 'url' => 'https://instagram.com', 'icono' => 'bi-instagram.svg'],
            ['nombre' => 'WhatsApp', 'url' => 'https://wa.me/123456789', 'icono' => 'bi-whatsapp.svg'],
            ['nombre' => 'YouTube', 'url' => 'https://youtube.com', 'icono' => 'bi-youtube.svg'],
        ];

        foreach ($redes as $red) {
            FooterSociales::updateOrCreate(
                ['nombre' => $red['nombre']],  // condición para evitar duplicados
                ['url' => $red['url'], 'icono' => $red['icono']]
            );
        }
    }
}
