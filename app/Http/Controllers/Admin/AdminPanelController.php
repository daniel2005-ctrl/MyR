<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Cliente;

class AdminPanelController extends Controller
{
   public function index()
   {
       $proyectos = Proyecto::orderBy('visitas', 'desc')->take(10)->get();
       $proyectoMasVisto = $proyectos->first();
       
       // Obtener los clientes más recientes con información del proyecto
       $clientes = Cliente::with('proyecto')
                         ->orderBy('created_at', 'desc')
                         ->take(10)
                         ->get();
       
       // Contar total de clientes
       $totalClientes = Cliente::count();
       
       return view('admin.panel_administrador.panel2', compact('proyectos', 'proyectoMasVisto', 'clientes', 'totalClientes'));
   }
}





