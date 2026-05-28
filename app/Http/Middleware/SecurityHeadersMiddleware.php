<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SecurityHeadersMiddleware
 *
 * Menambahkan HTTP Security Headers pada setiap response untuk melindungi
 * dari berbagai serangan: XSS, Clickjacking, MIME sniffing, dll.
 */
class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya tambahkan ke HTML responses
        $contentType = $response->headers->get('Content-Type', '');
        if (!str_contains($contentType, 'text/html') && !empty($contentType)) {
            return $response;
        }

        $isProduction = app()->environment('production');

        // ── Content Security Policy ──────────────────────────────────────────
        // Batasi sumber resource yang boleh dimuat browser
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:",
            "img-src 'self' data: blob: https://www.gstatic.com https://lh3.googleusercontent.com",
            "connect-src 'self'",
            "frame-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        // ── X-Frame-Options ──────────────────────────────────────────────────
        // Mencegah halaman di-embed di iframe (Clickjacking)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // ── X-Content-Type-Options ───────────────────────────────────────────
        // Mencegah browser menebak Content-Type (MIME sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ── X-XSS-Protection ────────────────────────────────────────────────
        // Aktifkan XSS filter bawaan browser (legacy, tapi masih berguna)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // ── Referrer-Policy ──────────────────────────────────────────────────
        // Batasi informasi referrer yang dikirim ke server lain
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ── Permissions-Policy ───────────────────────────────────────────────
        // Matikan fitur browser yang tidak dibutuhkan
        $response->headers->set('Permissions-Policy', implode(', ', [
            'camera=()',
            'microphone=()',
            'geolocation=()',
            'payment=()',
            'usb=()',
        ]));

        // ── HSTS (hanya di production dengan HTTPS) ──────────────────────────
        // Paksa browser selalu pakai HTTPS selama 1 tahun
        if ($isProduction && $request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // ── Hapus header yang membocorkan info server ─────────────────────────
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}