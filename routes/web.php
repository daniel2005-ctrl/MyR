<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CotizacionController;

// Página principal
Route::get('/', function () {
    return view('index');
})->name('home');

// Otras vistas
Route::view('/nosotros', 'nosotros')->name('nosotros');
Route::view('/proyecto1', 'proyecto1')->name('proyecto1');
Route::view('/formulario', 'formulario')->name('formulario');

Route::get('/cotizacion', function () {
    if (!Auth::check()) {
        // Usuario no está autenticado
        session()->flash('warning', 'debes iniciar sesión para acceder a esta sección.');
        return redirect()->route('home');
    }
    
    return view('cotizacion');
})->name('cotizacion');


Route::post('/generar-pdf', [CotizacionController::class, 'generarPDF'])->name('generar.pdf');

// Autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/login', fn() => redirect('/'))->name('login');

// Restablecer contraseña
Route::get('password/reset',   [AuthController::class, 'showLinkRequestForm'])
     ->name('password.request');
Route::post('password/email',  [AuthController::class, 'sendResetLinkEmail'])
     ->name('password.email');
Route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])
     ->name('password.reset');
Route::post('password/reset',  [AuthController::class, 'reset'])
     ->name('password.update');

// Formularios y proyectos
Route::post('/enviar-formulario', [FormularioController::class, 'store'])->name('formulario.store');
Route::get('/proyecto/{id}',      [ProyectoController::class, 'show'])->name('proyecto.show');



Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});


Route::get('/admin/index', function () {
     return view('admin.index'); // Crea esta vista en resources/views/admin/index.blade.php
 })->middleware('auth')->name('admin.index');
 

