<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubsidiosCreditosseeder extends Seeder
{
  public function run()
  {
    DB::table('subsidios_creditos')->truncate();

    $datos = [
            // Subsidios
            [
                'tipo' => 'subsidio',
                'nombre' => 'Subsidio Confenalco',
                'url' => 'https://www.comfenalco.com.co/subsidio-de-vivienda/',
                'orden' => 1,
                'activo' => 1,
            ],
            [
                'tipo' => 'subsidio',
                'nombre' => 'Subsidio ConfaTolima',
                'url' => 'https://www.comfatolima.com.co/beneficios/subsidios/subsidio-de-vivienda/',
                'orden' => 2,
                'activo' => 1,
            ],

            // Créditos
            [
                'tipo' => 'credito',
                'nombre' => 'Bancolombia',
                'url' => 'https://www.bancolombia.com/personas/vivienda',
                'orden' => 1,
                'activo' => 1,
            ],
            [
                'tipo' => 'credito',
                'nombre' => 'Scotiabank',
                'url' => 'https://www.scotiabankcolpatria.com/personas/hipotecario/compra-de-inmuebles/compra-de-vivienda',
                'orden' => 2,
                'activo' => 1,
            ],
            [
                'tipo' => 'credito',
                'nombre' => 'Banco Caja Social',
                'url' => 'https://www.bancocajasocial.com/creditos-de-vivienda/credito-hipotecario/',
                'orden' => 3,
                'activo' => 1,
            ],
            [
                'tipo' => 'credito',
                'nombre' => 'Banco Popular',
                'url' => 'https://www.bancopopular.com.co/wps/portal/bancopopular/inicio/para-ti/financiacion-vivienda',
                'orden' => 4,
                'activo' => 1,
            ],
            [
                'tipo' => 'credito',
                'nombre' => 'Banco Avevillas',
                'url' => 'https://www.avvillas.com.co/credito-hipotecario',
                'orden' => 5,
                'activo' => 1,
            ],
            [
                'tipo' => 'credito',
                'nombre' => 'Davivienda',
                'url' => 'https://www.davivienda.com/wps/portal/personas/nuevo#cc644b6e-41c6-4827-8484-267ad854c4b2',
                'orden' => 6,
                'activo' => 1,
            ],
            [
                'tipo' => 'credito',
                'nombre' => 'Banco de Bogota',
                'url' => 'https://viviendadigital.bancodebogota.com/',
                'orden' => 7,
                'activo' => 1,
            ],
            [
                'tipo' => 'credito',
                'nombre' => 'BBVA',
                'url' => 'https://www.bbva.com.co/personas/productos/prestamos/vivienda.html',
                'orden' => 8,
                'activo' => 1,
            ],
            [
                'tipo' => 'credito',
                'nombre' => 'Fondo Nacional Del Ahorro',
                'url' => 'https://www.fna.gov.co/vivienda/Paginas/credito-hipotecario.aspx',
                'orden' => 9,
                'activo' => 1,
            ],
        ];

        DB::table('subsidios_creditos')->insert($datos);
  }
}
