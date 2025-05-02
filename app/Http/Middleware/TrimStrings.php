<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrimStrings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Recorrer todos los inputs y recortar los espacios al principio y al final
        $request->merge(array_map('trim', $request->all()));

        return $next($request);
    }
}
