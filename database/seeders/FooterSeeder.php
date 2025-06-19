<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Footer;

class FooterSeeder extends Seeder
{
    public function run(): void
    {
        Footer::updateOrCreate(
            ['id' => 2], // Match by ID
            [
                'direccion' => 'Carrera 20 A Sur Edificio Altos de Rincón De Varsovia',
                'gmail_url' => 'https://mail.google.com/mail/?view=cm&fs=1&to=myrproyectosyconstrucciones@gmail.com&su=Consulta%20desde%20la%20web&body=Hola%2C%20quisiera%20más%20información%20sobre...',
                'whatsapp_url' => 'https://wa.link/m5a8kb',
                'facebook_url' => 'https://www.facebook.com/share/1Dhgky7Wrw/',
                'gmail_icon' => 'bi-envelope',
                'whatsapp_icon' => 'bi-whatsapp',
                'facebook_icon' => 'bi-facebook',
                'additional_socials' => null
            ]
        );
    }
}