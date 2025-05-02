<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * Los nombres de las cookies que no deberían ser encriptadas.
     *
     * @var array
     */
    protected $except = [];
}
