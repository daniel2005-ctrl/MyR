<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

    class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies = '*';  // Aquí puedes poner las IPs específicas de tus proxies, o '*' para confiar en todos

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PROTO | Request::HEADER_X_FORWARDED_PORT;

}
