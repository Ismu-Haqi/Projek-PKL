<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::user()->role;
                
                // Redirect ke dashboard sesuai role user
                if ($role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($role === 'staff') {
                    return redirect()->route('staff.dashboard');
                } elseif ($role === 'pimpinan') {
                    return redirect()->route('pimpinan.dashboard');
                }
                
                // Fallback ke halaman login jika role tidak dikenali
                return redirect('/login');
            }
        }

        return $next($request);
    }
}