<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    // LOGIN via AJAX
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (! Auth::attempt($credentials)) {
        return response()->json(['success' => false, 'message' => 'Credenciales incorrectas.'], 401);
    }

    $user = Auth::user();
    return response()->json([
        'success'  => true,
        'usuario'  => $user->nombre,
        'es_admin' => $user->tipo_permiso_id === 1,  // ← devuelve si es admin
    ]);
        
        
    }

    // REGISTER via AJAX
    public function register(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:usuarios,email',
            'password'  => 'required|string|min:6|confirmed',
            'tipo_permiso_id'  => 'required|in:1,2',
            // no hace falta validar es_admin, lo manejamos por lógica
        ]);
    
        Usuario::create([
            'nombre'          => $request->nombre,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'tipo_permiso_id' => $request->input('tipo_permiso_id', 2), // ← asigna admin o usuario normal
        ]);
    
        return response()->json(['success' => true, 'message' => 'Usuario registrado exitosamente.']);
    }

    

    // LOGOUT via AJAX
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente.'
        ]);
    }

    // Mostrar vista de envío de enlace (no usado en AJAX)
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Enviar enlace de restablecimiento (soporta AJAX JSON)
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($request->wantsJson()) {
            return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => __($status)], 200)
                : response()->json(['message' => __($status)], 422);
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // Mostrar formulario de restablecer contraseña
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // Procesar restablecimiento de contraseña
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->password = Hash::make($request->password);
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($request->wantsJson()) {
            return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => 'Contraseña cambiada con éxito.'], 200)
                : response()->json(['message' => __($status)], 422);
        }

        return $status === Password::PASSWORD_RESET
    ? redirect()->route('home')->with('status', 'Contraseña cambiada con éxito.')
    : back()->withErrors(['email' => [__($status)]]);
    }
}
