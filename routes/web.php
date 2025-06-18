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
use App\Http\Controllers\Admin\NosotrosController;
use App\Http\Controllers\Admin\AdminPanelController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\Api\SecurityCodeController;



// Página principal
Route::get('/', [ProyectoController::class, 'index'])->name('home');

// Otras vistas
Route::view('/nosotros', 'proyectos.nosotros')->name('nosotros');
Route::get('nosotros', [NosotrosController::class, 'showPublic'])->name('proyectos.nosotros');
//ruta de terminos y condiciones
Route::view('/terminos-condiciones', 'proyectos.terminos')->name('terminos');

// Mostrar el formulario
Route::get('/formulario', function () {
    $proyectos = \App\Models\Proyecto::where('terminado', 1)->get();
    return view('proyectos.formulario', compact('proyectos'));
})->name('formulario');

Route::post('/formulario', [FormularioController::class, 'store'])->name('formulario.store');


// Página de proyectos (vista con todos los proyectos)
Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
Route::get('/proyectos/{proyecto:slug}', [ProyectoController::class, 'show'])->name('proyectos.show');

// Rutas de cotización (requiere login)
Route::middleware('auth')->group(function () {
    // Mostrar formulario de creación
    // CORRECCIÓN: Cambiar el nombre de la ruta para que coincida
    Route::get('/cotizacion', [CotizacionController::class, 'create'])->name('cotizaciones.create');
    Route::resource('cotizaciones', CotizacionController::class)->except(['create']);
    Route::post('/generar-pdf', [CotizacionController::class, 'generarPDF'])->name('generar.pdf');
});


//Autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

//logout de el login 
Route::post('/custom-logout', function (Request $request) {
    Auth::logout();
    
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'success' => true,
        'redirect' => url('/')
    ]);
});
Route::get('/login', function() {
    return redirect()->route('home')->with('warning', 'Debes iniciar sesión para acceder a esta sección.');
})->name('login');

// Restablecer contraseña
Route::get('password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'reset'])->name('password.update');

// Formularios y contacto
Route::post('/formulario', [FormularioController::class, 'store'])->name('formulario.store');
Route::post('/contacto/enviar', [FormularioController::class, 'enviar'])->name('contacto.enviar');

// Perfil del usuario (requiere login)
Route::middleware(['auth'])->group(function () {
    // Rutas protegidas por autenticación
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});

// Rutas de administración (requiere autenticación)
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    // Proyectos
    Route::get('proyectos', [ProyectoController::class, 'adminIndex'])->name('admin.proyectos.index');
    Route::post('proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::get('proyectos/crear', [ProyectoController::class, 'create'])->name('admin.proyectos.crear');
    Route::get('proyectos/{proyecto:slug}', [ProyectoController::class, 'show'])->name('admin.proyectos.show');
    Route::delete('proyectos/{proyecto:slug}', [ProyectoController::class, 'destroy'])->name('admin.proyectos.destroy');
    Route::get('proyectos/{proyecto:slug}/edit', [ProyectoController::class, 'edit'])->name('admin.proyectos.edit');
    Route::put('proyectos/{proyecto:slug}', [ProyectoController::class, 'update'])->name('admin.proyectos.update'); // <--- ESTA ES LA CLAVE
    Route::post('proyectos/{proyecto:slug}/toggle', [ProyectoController::class, 'toggle'])->name('admin.proyectos.toggle');
    Route::post('proyectos/{proyecto:slug}/subir-imagen-destacada', [ProyectoController::class, 'subirImagenDestacada'])->name('admin.proyectos.subirImagenDestacada');

    // Nosotros
    Route::get('nosotros', [NosotrosController::class, 'edit'])->name('admin.nosotros');
    Route::put('nosotros/update', [NosotrosController::class, 'update'])->name('admin.nosotros.update');

    // Panel administrador
    Route::get('/panel_administrador/panel2', [AdminPanelController::class, 'index'])->name('admin.panel_administrador.panel2');

    // Footer
    Route::get('footer', [FooterController::class, 'index'])->name('footer.index');
    Route::get('footer/editar', [FooterController::class, 'edit'])->name('footer.editar');
    Route::put('footer', [FooterController::class, 'update'])->name('footer.update');

    // Subsidios y créditos
    Route::resource('subsidios_creditos', App\Http\Controllers\Admin\SubsidioCreditoController::class);
});
// Agregar estas rutas en la sección de rutas autenticadas
Route::middleware(['auth'])->group(function () {
    // Rutas para cambiar fondo (solo admins)
    Route::post('admin/usar-proyecto-mas-visitado', [ProyectoController::class, 'usarProyectoMasVisitado'])->name('admin.usarProyectoMasVisitado');
    Route::post('admin/cambiar-fondo-personalizado', [ProyectoController::class, 'cambiarFondoPersonalizado'])->name('admin.cambiarFondoPersonalizado');
    Route::post('admin/cambiar-fondo-proyecto', [ProyectoController::class, 'cambiarFondoProyecto'])->name('admin.cambiarFondoProyecto');
    Route::post('admin/subir-imagen-personalizada', [ProyectoController::class, 'cambiarFondoPersonalizado'])->name('admin.subir-imagen-personalizada');
});
// Rutas para mostrar opciones de imagen destacada (controlador)
Route::middleware(['auth'])->group(function () {
    // Rutas existentes
    Route::post('admin/usar-proyecto-mas-visitado', [ProyectoController::class, 'usarProyectoMasVisitado'])->name('admin.usar-proyecto-mas-visitado');
    Route::post('admin/cambiar-fondo-personalizado', [ProyectoController::class, 'cambiarFondoPersonalizado'])->name('admin.cambiar-fondo-personalizado');
    Route::post('admin/cambiar-fondo-proyecto', [ProyectoController::class, 'cambiarFondoProyecto'])->name('admin.cambiar-fondo-proyecto');
    
    // Nuevas rutas para mostrar opciones
    Route::get('admin/mostrar-mas-visto', [ProyectoController::class, 'mostrarMasVisto'])->name('admin.mostrar-mas-visto');
    Route::get('admin/mostrar-selector-proyecto', [ProyectoController::class, 'mostrarSelectorProyecto'])->name('admin.mostrar-selector-proyecto');
    Route::get('admin/mostrar-subir-imagen', [ProyectoController::class, 'mostrarSubirImagen'])->name('admin.mostrar-subir-imagen');
});

// Rutas de API para códigos de seguridad
Route::prefix('api')->group(function () {
    Route::prefix('security-code')->group(function () {
        Route::get('/current', [SecurityCodeController::class, 'current']);
        Route::post('/generate', [SecurityCodeController::class, 'generate']);
        Route::post('/validate', [SecurityCodeController::class, 'validateCode']);
        Route::get('/info', [SecurityCodeController::class, 'info']);
    });
});
