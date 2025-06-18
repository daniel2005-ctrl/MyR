<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Footer;

class FooterSeeder extends Seeder
{
    public function run(): void
    {
        Footer::firstOrCreate([], [
            'direccion' => 'Carrera 20 A Sur Edificio Altos de Rincón De Varsovia',
            'gmail_url' => 'https://mail.google.com/mail/?view=cm&fs=1&to=myrproyectosyconstrucciones@gmail.com',
            'whatsapp_url' => 'https://wa.link/m5a8kb',
            'facebook_url' => 'https://www.facebook.com/share/1Dhgky7Wrw/'
        ]);
    }
}