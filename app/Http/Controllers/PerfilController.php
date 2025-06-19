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
        // Obtener el usuario directamente desde la base de datos
        $usuario = Usuario::find(Auth::id());
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $usuario->id,
            'password_actual' => 'required',
            'password_nueva' => 'nullable|min:8|confirmed',
        ], [
            'email.unique' => 'Este correo electrónico ya está en uso por otro usuario.',
        ]);

        if (!Hash::check($request->password_actual, $usuario->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta.']);
        }

        // Actualizar usando el método update() en lugar de save()
        $updateData = [
            'nombre' => $request->nombre,
            'email' => $request->email,
        ];

        if ($request->filled('password_nueva')) {
            $updateData['password'] = Hash::make($request->password_nueva);
        }

        $usuario->update($updateData);

        return back()->with('mensaje', 'Perfil actualizado con éxito.');
    }
}
