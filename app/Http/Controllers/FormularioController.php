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

        // Verificar si el email ya existe
        $clienteExistente = Cliente::where('email', $request->correo)->first();
        
        if ($clienteExistente) {
            return back()->with('info', 'Los datos ya han sido enviados anteriormente. Nos pondremos en contacto contigo pronto.')
                        ->withInput();
        }

        Cliente::create([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->correo,
            'id_pro' => $request->proyecto,
            'fecha_envio' => now(),
        ]);
        
        return back()->with('success', 'Formulario enviado correctamente');
    }
}
