<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * PreventBruteForceMiddleware
 *
 * Batasi percobaan login untuk mencegah brute force attack.
 * Maksimal 5 percobaan per 1 menit per IP.
 */
class PreventBruteForceMiddleware
{
    protected RateLimiter $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, int $maxAttempts = 5, int $decayMinutes = 1): Response
    {
        $key = 'login_attempt:' . $request->ip();

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $this->limiter->availableIn($key);
            $minutes = ceil($seconds / 60);

            return redirect()->back()
                ->withErrors([
                    'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$minutes} menit.",
                ])
                ->withInput($request->only('email'));
        }

        $response = $next($request);

        // Jika login gagal (redirect kembali dengan error), tambah counter
        if ($response->isRedirection() && session()->has('errors')) {
            $this->limiter->hit($key, $decayMinutes * 60);
        } else {
            // Login berhasil, reset counter
            $this->limiter->clear($key);
        }

        return $response;
    }
}
