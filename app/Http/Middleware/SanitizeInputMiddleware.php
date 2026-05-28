<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SanitizeInputMiddleware
 *
 * Membersihkan semua input dari potensi XSS sebelum masuk ke controller.
 * Bekerja rekursif pada array/nested input.
 */
class SanitizeInputMiddleware
{
    /**
     * Field yang TIDAK boleh di-sanitize (password, konten HTML yang valid, dll)
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'current_password',
        '_token',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Hanya sanitize method yang membawa data
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            $input = $request->all();
            $sanitized = $this->sanitize($input);
            $request->merge($sanitized);
        }

        return $next($request);
    }

    /**
     * Sanitize array input secara rekursif
     */
    protected function sanitize(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            // Skip field yang dikecualikan
            if (in_array($key, $this->except)) {
                $result[$key] = $value;
                continue;
            }

            if (is_array($value)) {
                $result[$key] = $this->sanitize($value);
            } elseif (is_string($value)) {
                $result[$key] = $this->cleanString($value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Bersihkan string dari karakter berbahaya XSS
     */
    protected function cleanString(string $value): string
    {
        // Strip tag HTML berbahaya
        $value = strip_tags($value, '<p><br><b><i><u><strong><em><ul><ol><li><h1><h2><h3><h4><table><tr><td><th>');

        // Encode karakter HTML khusus yang tersisa
        // htmlspecialchars mengubah <, >, ", ', & menjadi entity HTML
        // ENT_QUOTES memastikan single dan double quote juga di-encode
        // Tapi kita hanya encode yang bukan tag yang diizinkan
        $value = $this->encodeScriptTags($value);

        // Hapus javascript: dan vbscript: dari attribute
        $value = preg_replace('/javascript\s*:/i', '', $value);
        $value = preg_replace('/vbscript\s*:/i', '', $value);
        $value = preg_replace('/on\w+\s*=/i', '', $value); // onload=, onclick=, dll

        // Trim whitespace berlebih
        $value = trim($value);

        return $value;
    }

    /**
     * Encode tag script yang berbahaya
     */
    protected function encodeScriptTags(string $value): string
    {
        // Encode <script> tags
        $value = preg_replace('/<script\b[^>]*>/i', '&lt;script&gt;', $value);
        $value = preg_replace('/<\/script>/i', '&lt;/script&gt;', $value);

        // Encode <iframe> tags
        $value = preg_replace('/<iframe\b[^>]*>/i', '&lt;iframe&gt;', $value);
        $value = preg_replace('/<\/iframe>/i', '&lt;/iframe&gt;', $value);

        // Encode <object> dan <embed>
        $value = preg_replace('/<(object|embed|link|meta|style)\b[^>]*>/i', '', $value);

        return $value;
    }
}
