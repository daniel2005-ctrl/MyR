<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CotizacionController;
use App\Models\Proyecto;

// Página principal
Route::get('/', [ProyectoController::class, 'index'])->name('home');
// Otras vistas
Route::view('/nosotros', 'nosotros')->name('nosotros');
Route::view('/formulario', 'formulario')->name('formulario');

// Página de proyectos (vista con todos los proyectos)
Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');

// Página de un proyecto específico
Route::get('/proyecto/{id}', [ProyectoController::class, 'show'])->name('proyecto.show');

// Ruta de cotización, solo accesible si el usuario está autenticado
Route::get('/cotizacion', function () {
    if (!Auth::check()) {
        session()->flash('warning', 'Debes iniciar sesión para acceder a esta sección.');
        return redirect()->route('home');
    }
    return view('cotizacion');
})->name('cotizacion');

// Generación de PDF de cotización
Route::post('/generar-pdf', [CotizacionController::class, 'generarPDF'])->name('generar.pdf');

// Autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/login', fn() => redirect('/'))->name('login');

// Restablecer contraseña
Route::get('password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'reset'])->name('password.update');

// Formularios y contacto
Route::post('/enviar-formulario', [FormularioController::class, 'store'])->name('formulario.store');
Route::post('/contacto/enviar', [FormularioController::class, 'enviar'])->name('contacto.enviar');

// Rutas del perfil de usuario, solo accesibles si el usuario está autenticado
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});

// Rutas para el panel de administración
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function () {
        $proyectos = Proyecto::all();
        return view('admin.index', compact('proyectos'));
    });
});
