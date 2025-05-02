<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cliente;
class FormularioController extends Controller
{
    public function store(Request $request)
    {
    
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|email',
            'proyecto' => 'required'
        ]);

       

        Cliente::create([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->correo,
            'id_pro' => $request->proyecto,
            'fecha_envio' => now(),
        ]);
        
    
        // Aquí puedes guardar en base de datos, enviar correo, etc.
        return back()->with('success', 'Formulario enviado correctamente');
    }
}
