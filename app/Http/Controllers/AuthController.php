<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Services\PasswordResetService;
use App\Notifications\ResetPasswordNotification;

class AuthController extends Controller
{
    protected $passwordResetService;
    
    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    // LOGIN via AJAX
    // En App/Http/Controllers/AuthController.php

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        // Verificar si el usuario existe en la base de datos
        $usuario = Usuario::where('email', $credentials['email'])->first();
        
        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no está registrado. Por favor, regístrate primero.',
                'type' => 'not_registered'
            ], 404);
        }
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
    
            $user = Auth::user();
            $redirect = ($user->tipo_permiso_id == 1) ? '/admin' : '/';
    
            return response()->json([
                'success' => true,
                'usuario' => $user->nombre,
                'redirect' => $redirect
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Contraseña incorrecta',
            'type' => 'wrong_password'
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
        
        // Verificar si el usuario existe
        $user = Usuario::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No encontramos un usuario con ese correo electrónico.'
            ], 422);
        }
        
        // Crear token personalizado
        $token = $this->passwordResetService->createToken($request->email);
        
        // Enviar notificación
        $user->notify(new ResetPasswordNotification($token));
        
        return response()->json([
            'success' => true,
            'message' => 'Hemos enviado un enlace de recuperación a tu correo electrónico.'
        ], 200);
    }

    // Mostrar formulario de restablecer contraseña
    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->email;
        
        // Verificar si el token es válido antes de mostrar el formulario
        if (!$this->passwordResetService->validateToken($email, $token)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Este enlace de restablecimiento es inválido o ya ha sido utilizado.']);
        }
        
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $email,
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
        
        // Verificar token personalizado
        if (!$this->passwordResetService->validateToken($request->email, $request->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Este enlace de restablecimiento es inválido o ya ha sido utilizado.'
            ], 422);
        }
        
        // Buscar usuario
        $user = Usuario::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el usuario.'
            ], 422);
        }
        
        // Actualizar contraseña
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Marcar token como usado y luego eliminarlo
        $this->passwordResetService->markTokenAsUsed($request->email);
        $this->passwordResetService->deleteToken($request->email);
        
        // Respuesta exitosa
        return response()->json([
            'success' => true,
            'message' => 'Contraseña cambiada con éxito.'
        ], 200);
    }
}
