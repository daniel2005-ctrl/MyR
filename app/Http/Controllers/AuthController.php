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
    // En App/Http/Controllers/AuthController.php

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();
        $redirect = ($user->tipo_permiso_id == 1) ? '/admin' : '/';

        return response()->json([
            'success' => true,
            'usuario' => $user->nombre, // o username
            'redirect' => $redirect
        ]);
    }

    return redirect()->intended('/')->with('status', 'Has iniciado sesión correctamente.');


    return response()->json([
        'success' => false,
        'message' => 'Credenciales incorrectas'
    ], 401);
}




    // REGISTER via AJAX
    public function register(Request $request)
    {
        // Validación de datos
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:usuarios,email',
            'password'        => 'required|string|min:6|confirmed', // Contraseña debe ser confirmada
            'tipo_permiso_id' => 'required|in:1,2', // Admin o Usuario normal
        ]);

        // Crear usuario
        Usuario::create([
            'nombre'          => $request->nombre,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),  // Encriptación de la contraseña
            'tipo_permiso_id' => $request->tipo_permiso_id, // Asignar tipo de permiso
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado exitosamente.',
        ]);
    }


    // LOGOUT via AJAX
public function logout(Request $request)
{
    // Cerrar sesión
    Auth::logout();

    // Invalidar y regenerar el token de sesión
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Redirigir al índice principal (o a cualquier otra ruta que prefieras)
    return redirect()->route('proyectos.index'); // O la ruta principal que desees
}


    // Mostrar vista de envío de enlace de recuperación (no utilizado en AJAX)
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Enviar enlace de restablecimiento de contraseña (AJAX JSON)
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        // Respuesta JSON para AJAX
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
            'email' => $request->email,
        ]);
    }

    // Procesar restablecimiento de contraseña
    public function reset(Request $request)
    {
        // Validación de datos
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        // Realizar el restablecimiento
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->password = Hash::make($request->password); // Encriptar nueva contraseña
                $user->save();
                event(new PasswordReset($user)); // Disparar el evento de PasswordReset
            }
        );

        // Respuesta en JSON para AJAX
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
