<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoPermisoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipo_permisos')->insert([
            ['id' => 1, 'nombre_permiso' => 'admin'],
            ['id' => 2, 'nombre_permiso' => 'usuario']
        ]);
    }
}