<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CotizacionController extends Controller
{
    public function generarPDF(Request $request)
{
    // Filtrar solo los datos necesarios
    $data = $request->only([
        'valor_vivienda', 'cuota_inicial', 'separacion',
        'valor_parqueadero', 'cuota_inicial_parq', 'separacion_parq',
        'plazo_general', 'cuota_mensual_general'
    ]);

    // Retornar PDF con la vista Blade
    $pdf = Pdf::loadView('pdf.cotizacion', $data);
    return $pdf->download('cotizacion.pdf');
}
}

