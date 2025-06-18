<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\SubsidioCredito;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class CotizacionController extends Controller
{
    public function create()
    {
        // Filtrar proyectos según el tipo de usuario
        if (Auth::check() && Auth::user()->tipo_permiso_id == 1) {
            // Si es administrador, mostrar todos los proyectos
            $proyectos = Proyecto::all();
        } else {
            // Si no es administrador o no está autenticado, solo mostrar proyectos terminados
            $proyectos = Proyecto::where('terminado', 1)->get();
        }
        
        $subsidios = SubsidioCredito::where('tipo', 'subsidio')->activos()->ordenados()->get();
        $creditos = SubsidioCredito::where('tipo', 'credito')->activos()->ordenados()->get();
        
        // Get current minimum wage
        $salarioMinimo = Cache::get('salario_minimo', 1300000);
        
        return view('cotizaciones.index', compact('proyectos', 'subsidios', 'creditos', 'salarioMinimo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id_pro',
            'tipo_apto' => 'required|in:min,max',
            'incluye_parqueadero' => 'required|boolean',
        ]);
        $proyecto = Proyecto::findOrFail($request->proyecto_id);
        $salarioMinimo = Cache::get('salario_minimo', 1300000);
        $precio = $proyecto->precio;
        
        if (!isset($precio[$request->tipo_apto])) {
            return back()->withErrors(['tipo_apto' => 'Tipo de apartamento inválido o precio no disponible.'])->withInput();
        }
        
        $valorApto = $precio[$request->tipo_apto] * $salarioMinimo;
        
        // CORRECCIÓN: Acceso correcto a datos de parqueadero
        // En el método store, reemplaza la sección de parqueadero:
        $parqueaderoData = $proyecto->parqueadero;
        $valorParqueaderoBase = is_array($parqueaderoData) ? 
            ($parqueaderoData['min'] ?? $parqueaderoData['max'] ?? $parqueaderoData ?? 0) : 
            ($parqueaderoData ?? 0);
        $valorParqueadero = $request->incluye_parqueadero ? $valorParqueaderoBase * $salarioMinimo : 0;
        
        $cuotaInicial = $valorApto * 0.3;
        $separacion = $cuotaInicial * 0.1;
        
        Cotizacion::create([
            'proyecto_id' => $proyecto->id_pro,
            'nombre_pro' => $proyecto->nombre_pro,
            'tipo_apto' => $request->tipo_apto,
            'valor_apartamento' => $valorApto,
            'incluye_parqueadero' => $request->incluye_parqueadero,
            'valor_parqueadero' => $valorParqueadero,
            'cuota_inicial' => $cuotaInicial,
            'separacion' => $separacion,
        ]);
        
        return redirect()->route('cotizaciones.create')->with('success', 'Cotización creada con éxito.');
    }

    public function generarPDF(Request $request)
    {
        // Validar los datos recibidos del formulario
        $request->validate([
            'nombre_proyecto' => 'required|string',
            'valor_vivienda' => 'required|string',
            'cuota_inicial' => 'required|string',
            'separacion' => 'required|string',
            'valor_parqueadero' => 'nullable|string',
            'cuota_inicial_parq' => 'nullable|string',
            'separacion_parq' => 'nullable|string',
            'plazo_general' => 'required|string',
            'cuota_mensual_general' => 'required|string',
        ]);
    
        // Preparar los datos para la vista PDF
        $data = [
            'nombreProyecto' => $request->nombre_proyecto,
            'valorVivienda' => $request->valor_vivienda,
            'cuotaInicial' => $request->cuota_inicial,
            'separacion' => $request->separacion,
            'valorParqueadero' => $request->valor_parqueadero ?? '$ 0',
            'cuotaInicialParqueadero' => $request->cuota_inicial_parq ?? '$ 0',
            'separacionParqueadero' => $request->separacion_parq ?? '$ 0',
            'plazoGeneral' => $request->plazo_general,
            'cuotaMensualGeneral' => $request->cuota_mensual_general,
            'totalAhorros' => $request->total_ahorros ?? null 
        ];
    
        // Generar el PDF usando dompdf
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cotizaciones.cotizacion-pdf', $data);
        return $pdf->download('cotizacion_proyecto.pdf');
    }
}
