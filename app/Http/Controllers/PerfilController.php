<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class PerfilController extends Controller
{
    public function edit()
    {
        $usuario = Auth::user(); // esto devolverá una instancia de Usuario
        return view('perfil.edit', compact('usuario'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password_actual' => 'required',
            'password_nueva' => 'nullable|min:8|confirmed',
        ]);

        $usuario = Auth::user();

        if (!Hash::check($request->password_actual, $usuario->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta.']);
        }

        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;

        if ($request->filled('password_nueva')) {
            $usuario->password = Hash::make($request->password_nueva);
        }


        $usuario->save();

        return back()->with('mensaje', 'Perfil actualizado con éxito.');
    }


}
