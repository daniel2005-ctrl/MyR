<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    public function run()
    {
        DB::table('estados')->insert([
            [
                'id_estado' => 1,
                'nombre_estado' => 'Terminado',
            ],
            [
                'id_estado' => 2,
                'nombre_estado' => 'En construcción',
            ],
        ]);
    }
}