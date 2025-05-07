<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    /**
     * Mostrar todos los proyectos.
     */
    public function index()
    {
        $proyectos = Proyecto::paginate(10);
        return view('index', compact('proyectos'));
    }

    /**
     * Mostrar un proyecto específico.
     */
    public function show($id)
    {
        $proyecto = Proyecto::where('id_pro', $id)->firstOrFail();

        // Adaptar datos para la vista
        $proyecto->titulo = $proyecto->nombre_pro;

        $proyecto->imagenesHeader = is_string($proyecto->imagenes_pro)
            ? json_decode($proyecto->imagenes_pro, true)
            : $proyecto->imagenes_pro;

        $proyecto->descripcion = explode("\n", $proyecto->descripcion);

        $proyecto->precio = [
            'min' => $proyecto->precio_min,
            'max' => $proyecto->precio_max,
        ];

        $proyecto->area = [
            'min' => $proyecto->area_min,
            'max' => $proyecto->area_max,
        ];

        $proyecto->direccion = $proyecto->ubicacion_pro;

        $proyecto->mapa = $proyecto->mapa ?: null;

        $proyecto->zonas = [];
        $proyecto->apartamentos = [];

        return view('proyectos', compact('proyecto'));
    }

    /**
     * Mostrar formulario para crear un nuevo proyecto.
     */
    public function create()
    {
        return view('crear_proyecto');
    }

    /**
     * Almacenar un nuevo proyecto en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_pro'       => 'required|string|max:255',
            'descripcion'      => 'required|string',
            'precio_min'       => 'required|numeric',
            'precio_max'       => 'nullable|numeric',
            'area_min'         => 'required|numeric',
            'area_max'         => 'nullable|numeric',
            'ubicacion_pro'    => 'required|string|max:255',
            'imagenes_pro'     => 'nullable|array',
            'imagenes_pro.*'   => 'url',
            'id_tipo_permiso'  => 'required|exists:tipo_permisos,id_tipo',
        ]);

        $direccion = $request->input('ubicacion_pro');
        $mapaEmbedUrl = 'https://www.google.com/maps?q=' . urlencode($direccion) . '&output=embed';

        $proyecto = Proyecto::create([
            'nombre_pro'      => $request->input('nombre_pro'),
            'descripcion'     => $request->input('descripcion'),
            'precio_min'      => $request->input('precio_min'),
            'precio_max'      => $request->input('precio_max'),
            'area_min'        => $request->input('area_min'),
            'area_max'        => $request->input('area_max'),
            'ubicacion_pro'   => $direccion,
            'mapa'            => $mapaEmbedUrl,
            'imagenes_pro'    => json_encode($request->input('imagenes_pro', [])),
            'id_tipo_permiso' => $request->input('id_tipo_permiso'),
        ]);

        return redirect()->route('proyecto.show', $proyecto->id_pro)
                         ->with('success', 'Proyecto creado exitosamente');
    }
}
