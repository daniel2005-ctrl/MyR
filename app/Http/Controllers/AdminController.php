<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\ZonaCatalogo;

class AdminController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::all();
        $proyectoMasVisto = Proyecto::orderBy('visitas', 'desc')->first();
        return view('admin.proyectos.index', compact('proyectos', 'proyectoMasVisto'));
    }

    public function crearProyecto()
    {
        // Obtener todas las zonas del catálogo
        $zonas = ZonaCatalogo::all(); 

        // Pasar las zonas a la vista
        return view('admin.proyectos.crear', compact('zonas'));
    }
}
