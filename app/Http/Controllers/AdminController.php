<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\ZonaCatalogo;

class AdminController extends Controller
{
    public function index()
    {
        // Obtener todos los proyectos desde la base de datos
        $proyectos = Proyecto::all();

        // Pasar la variable $proyectos a la vista
        return view('admin.proyectos.index', compact('proyectos'));
    }

    public function crearProyecto()
    {
        // Obtener todas las zonas del catálogo
        $zonas = ZonaCatalogo::all(); 

        // Pasar las zonas a la vista
        return view('admin.proyectos.crear', compact('zonas'));
    }
}
