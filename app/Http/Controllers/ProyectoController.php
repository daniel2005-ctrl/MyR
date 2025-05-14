<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\ZonaCatalogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Estado;


class ProyectoController extends Controller
{
   public function index()
{
    // Determina si el usuario está autenticado y su tipo
    if (Auth::check()) {
        // Si es administrador, mostrar todos los proyectos
        if (Auth::user()->tipo_permiso_id == 1) {
            $proyectos = Proyecto::paginate(10);
        } else {
            // Si no es administrador, mostrar solo los proyectos terminados
            $proyectos = Proyecto::where('terminado', 1)->paginate(10);
        }
    } else {
        // Si no está autenticado, solo mostrar proyectos terminados
        $proyectos = Proyecto::where('terminado', 1)->paginate(10);
    }

    // Retorna la vista con los proyectos
    return view('proyectos.index', compact('proyectos'));
}



   public function show($id)
{
    // Busca el proyecto con las zonas sociales relacionadas
    $proyecto = Proyecto::with('zonasCatalogo')->findOrFail($id);

    // Asignación de datos del proyecto con valores predeterminados si es necesario
    $proyecto->titulo       = $proyecto->nombre_pro;
    $proyecto->descripcion  = $proyecto->descripcion ?? [];
    $proyecto->precio       = $proyecto->precio ?? ['min' => null, 'max' => null];
    $proyecto->area         = $proyecto->area ?? ['min' => null, 'max' => null];
    $proyecto->direccion    = $proyecto->ubicacion_pro;
    $proyecto->apartamentos = $proyecto->apartamentos ?? [];

    // Genera el enlace del mapa con la dirección, asegurándose de codificar la URL correctamente
    $proyecto->mapa         = 'https://www.google.com/maps?q=' . urlencode($proyecto->direccion) . '&output=embed';

    // Retorna la vista con la información del proyecto
    return view('proyectos.proyectos', compact('proyecto'));
}
public function create()
{
    // Obtener todos los estados disponibles
    $estados = Estado::all();

    // Obtener las zonas sociales
    $zonas = ZonaCatalogo::all();

    return view('admin.proyectos.crear', compact('zonas', 'estados'));
}
public function destroy($id)
{
    $proyecto = Proyecto::findOrFail($id);

    // Eliminar imágenes del proyecto
    //foreach ($proyecto->imagenes_pro ?? [] as $ruta) {
       // $rutaCompleta = public_path($ruta);
        //if (file_exists($rutaCompleta)) {
           // unlink($rutaCompleta);
      //  }
   // }
    
    // Eliminar imágenes de apartamentos
   //foreach ($proyecto->apartamentos ?? [] as $ruta) {
        //$rutaCompleta = public_path($ruta);
       // if (file_exists($rutaCompleta)) {
           // unlink($rutaCompleta);
       // }
   // }
    
    // Desasociar zonas sociales
    $proyecto->zonasCatalogo()->detach();
    
    // Eliminar el proyecto
    $proyecto->delete();
    
    // Verifica si es una petición AJAX (fetch)
    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Proyecto eliminado correctamente.'
        ]);
    }
    
    // Petición normal (no fetch)
    return redirect()->route('admin.proyectos.index')->with('success', 'Proyecto eliminado correctamente.');
    
}
   public function edit($id)
    {
        // Obtener el proyecto por ID
        $proyecto = Proyecto::findOrFail($id);

        // Pasar el proyecto a la vista de edición
        return view('admin.proyectos.edit', compact('proyecto'));
    }

    // Actualizar un proyecto
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre_pro' => 'required|string|max:255',
            'descripcion' => 'required|array', // Asegúrate de que es un array
            'terminado' => 'required|boolean',
            // Agrega otras validaciones según lo que necesites
        ]);

        // Obtener el proyecto a actualizar
        $proyecto = Proyecto::findOrFail($id);

        // Actualizar los campos del proyecto
        $proyecto->nombre_pro = $request->input('nombre_pro');
        $proyecto->descripcion = $request->input('descripcion'); // Asegúrate de que el formato sea correcto
        $proyecto->terminado = $request->input('terminado');
        
        // Aquí puedes agregar otros campos para actualizar

        // Guardar el proyecto actualizado
        $proyecto->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado correctamente.');
    }
    public function toggle($id)
{
    $proyecto = Proyecto::find($id);

    if (!$proyecto) {
        return response()->json(['success' => false, 'message' => 'Proyecto no encontrado.']);
    }

    // Cambia el valor de 1 ↔ 0
    $proyecto->terminado = $proyecto->terminado == 1 ? 0 : 1;
    $proyecto->save();

    return response()->json([
        'success' => true,
        'terminado' => $proyecto->terminado,
        'message' => $proyecto->terminado ? 'Proyecto marcado como terminado.' : 'Proyecto marcado como en construcción.'
    ]);
}



public function store(Request $request)
{
    $request->validate([
        'nombre_pro' => 'required|string|max:255',
        'estado_id' => 'required|exists:estados,id_estado',
        'imagenes_pro' => 'required|array',
        'imagenes_pro.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        'descripcion' => 'required|array',
        'descripcion.*' => 'string|max:1000',
        'zonas' => 'nullable|array',
        'precio_min' => 'required|numeric',
        'precio_max' => 'nullable|numeric',
        'area_min' => 'required|numeric',
        'area_max' => 'nullable|numeric',
        'ubicacion_pro' => 'required|string|max:255',
    ]);

      $imagenesPro = [];
    if ($request->hasFile('imagenes_pro')) {
        foreach ($request->file('imagenes_pro') as $imagen) {
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('imagenes'), $nombreArchivo);
            $imagenesPro[] = 'imagenes/' . $nombreArchivo;
        }
    }

    // Guardar apartamentos en public/imagenes/apartamentos (si los hay)
    $apartamentos = [];
    if ($request->hasFile('apartamentos')) {
        foreach ($request->file('apartamentos') as $apto) {
            $nombreApto = time() . '_' . $apto->getClientOriginalName();
            $apto->move(public_path('imagenes/apartamentos'), $nombreApto);
            $apartamentos[] = 'imagenes/apartamentos/' . $nombreApto;
        }
    }
    // Crear proyecto
    $proyecto = Proyecto::create([
        'nombre_pro'       => $request->input('nombre_pro'),
        'tipo_pro'         => $request->input('tipo_pro'),
        'descripcion'      => $request->input('descripcion'),
        'precio'           => [
            'min' => $request->input('precio_min'),
            'max' => $request->input('precio_max'),
        ],
        'area'             => [
            'min' => $request->input('area_min'),
            'max' => $request->input('area_max'),
        ],
        'ubicacion_pro'    => $request->input('ubicacion_pro'),
        'imagenes_pro'     => $imagenesPro,
        'videos_pro'       => [], // aún no se usa
        'apartamentos'     => $apartamentos,
        'id_tipo_permiso'  => Auth::user()->tipo_permiso_id,
        'terminado'        => 1,
        'id_estado'        => $request->input('estado_id'),
    ]);

    // Asociar zonas sociales si las hay
    if ($request->filled('zonas')) {
        $proyecto->zonasCatalogo()->attach($request->input('zonas'));
    }

   return redirect()->route('proyectos.show', $proyecto->id_pro);

                    
}
}