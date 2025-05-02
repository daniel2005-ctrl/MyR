<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     *
     * These middleware will run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,  // Middleware para manejar proxies y encabezados
        \Illuminate\Http\Middleware\HandleCors::class,  // Middleware para CORS nativo en Laravel 12
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,  // Valida el tamaño máximo de los POST
        \App\Http\Middleware\TrimStrings::class,  // Elimina espacios en blanco en los valores de las solicitudes
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,  // Convierte cadenas vacías a NULL
    ];

    /**
     * The application's route middleware groups.
     *
     * These middleware groups can be assigned to routes or controllers.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,  // Encripta cookies para las rutas web
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,  // Añade cookies a las respuestas
            \Illuminate\Session\Middleware\StartSession::class,  // Inicia sesión en las rutas web
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,  // Comparte los errores de la sesión con las vistas
            \App\Http\Middleware\VerifyCsrfToken::class,  // Verifica el token CSRF para prevenir ataques
            \Illuminate\Routing\Middleware\SubstituteBindings::class,  // Sustituye los parámetros de las rutas
        ],

        'api' => [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',  // Limita la cantidad de peticiones a la API
            \Illuminate\Routing\Middleware\SubstituteBindings::class,  // Sustituye los parámetros de las rutas en la API
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,  // Middleware para la autenticación
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,  // Middleware para autenticación básica
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,  // Redirige a los usuarios autenticados a la ruta deseada
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,  // Middleware para limitar el número de peticiones
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,  // Asegura que el usuario tenga el correo verificado
    ];
}
