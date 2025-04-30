<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormularioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email',
            'mensaje' => 'required|string'
        ]);

        // Aquí puedes guardar en base de datos, enviar correo, etc.
        return back()->with('success', 'Formulario enviado correctamente');
    }
}
