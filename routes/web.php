<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Http\Request;

// Página principal
Route::get('/', [ProyectoController::class, 'index'])->name('home');

// Otras vistas
Route::view('/nosotros', 'proyectos.nosotros')->name('nosotros');
Route::view('/formulario', 'proyectos.formulario')->name('formulario');

// Página de proyectos (vista con todos los proyectos)
Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
Route::get('/proyectos/{id}', [ProyectoController::class, 'show'])->name('proyectos.show');

// Ruta de cotización (requiere login)
Route::get('/cotizacion', function () {
    if (!Auth::check()) {
        session()->flash('warning', 'Debes iniciar sesión para acceder a esta sección.');
        return redirect()->route('home');
    }
    return view('proyectos.cotizacion');
})->name('cotizacion');

// Generación de PDF
Route::post('/generar-pdf', [CotizacionController::class, 'generarPDF'])->name('generar.pdf');

// 4. Autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/custom-logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'success' => true,
        'redirect' => url('/')
    ]);
});
Route::get('/login', fn() => response()->json(['message' => 'Acceso no autorizado.'], 401))->name('login');



Route::post('/custom-logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'success' => true,
        'redirect' => url('/')
    ]);
});



// Restablecer contraseña
Route::get('password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'reset'])->name('password.update');

// Formularios y contacto
Route::post('/enviar-formulario', [FormularioController::class, 'store'])->name('formulario.store');
Route::post('/contacto/enviar', [FormularioController::class, 'enviar'])->name('contacto.enviar');

// Perfil del usuario (requiere login)
Route::middleware(['auth'])->group(function () {
    // Rutas protegidas por autenticación
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});

// Rutas de administración (requiere autenticación)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    Route::get('proyectos', [ProyectoController::class, 'index'])->name('admin.proyectos.index');
    Route::post('proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::get('proyectos/crear', [ProyectoController::class, 'create'])->name('admin.proyectos.crear');
    Route::get('proyectos/{id}', [ProyectoController::class, 'show'])->name('admin.proyectos.show');
    Route::delete('/proyectos/{id}', [ProyectoController::class, 'destroy'])->name('admin.proyectos.destroy');
    Route::get('/proyectos/{id}/edit', [ProyectoController::class, 'edit'])->name('admin.proyectos.edit');
    Route::put('/admin/proyectos/{proyecto}', [ProyectoController::class, 'update'])->name('admin.proyectos.update');
    Route::patch('/proyectos/{id}/toggle', [ProyectoController::class, 'toggle'])->name('proyectos.toggle');
    
   

    

    });
    
    
        

