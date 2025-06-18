<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!Auth::check()) {
            // Redirigir al index principal con mensaje de advertencia
            return redirect()->route('home')->with('warning', 'Debes iniciar sesión para acceder a esta sección.');
        }

        return $next($request);
    }
}

