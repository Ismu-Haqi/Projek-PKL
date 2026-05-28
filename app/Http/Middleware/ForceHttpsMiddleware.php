<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ForceHttpsMiddleware
 *
 * Di environment production, redirect semua request HTTP ke HTTPS.
 * Di local/development, tidak melakukan apa-apa.
 */
class ForceHttpsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya enforce HTTPS di production
        if (app()->environment('production') && !$request->isSecure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // Set trusted proxies agar HTTPS terdeteksi di balik load balancer / reverse proxy
        if (app()->environment('production')) {
            $request->server->set('HTTPS', 'on');
        }

        return $next($request);
    }
}
