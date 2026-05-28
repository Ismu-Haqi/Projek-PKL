<?php

namespace App\Helpers;

/**
 * SecurityHelper
 *
 * Helper untuk sanitasi input dan validasi keamanan tambahan di controller.
 * Gunakan method ini sebelum menyimpan data sensitif ke database.
 */
class SecurityHelper
{
    /**
     * Sanitize string — encode HTML entities
     * Gunakan untuk output data ke view (selain Blade {{ }} yang sudah auto-escape)
     */
    public static function sanitize(?string $value): string
    {
        if ($value === null) return '';
        return htmlspecialchars(trim($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Sanitize string untuk penyimpanan ke DB — strip tag berbahaya
     */
    public static function sanitizeForDb(?string $value): string
    {
        if ($value === null) return '';
        $value = strip_tags($value);
        $value = preg_replace('/javascript\s*:/i', '', $value);
        $value = preg_replace('/on\w+\s*=/i', '', $value);
        return trim($value);
    }

    /**
     * Validasi apakah string hanya berisi karakter yang aman (alphanumeric + spasi + tanda baca umum)
     */
    public static function isSafeString(?string $value): bool
    {
        if ($value === null) return true;
        return preg_match('/^[\p{L}\p{N}\s\.,\-_\(\)\/:;\'\"@#&\+\?!]+$/u', $value) === 1;
    }

    /**
     * Sanitize nama file upload — hapus karakter berbahaya
     */
    public static function sanitizeFilename(string $filename): string
    {
        // Hapus karakter berbahaya dari nama file
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        // Hapus multiple underscore berturut-turut
        $filename = preg_replace('/_+/', '_', $filename);
        // Batasi panjang filename
        if (strlen($filename) > 200) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $name = substr(pathinfo($filename, PATHINFO_FILENAME), 0, 195);
            $filename = $name . '.' . $ext;
        }
        return $filename;
    }

    /**
     * Validasi ekstensi file upload
     * Return true jika aman, false jika berbahaya
     */
    public static function isAllowedExtension(string $filename, array $allowed = []): bool
    {
        if (empty($allowed)) {
            $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'];
        }

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Daftar ekstensi berbahaya yang selalu diblokir
        $dangerous = ['php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'bat', 'sh', 'py', 'rb', 'js', 'html', 'htm', 'xml', 'svg'];

        if (in_array($ext, $dangerous)) return false;

        return in_array($ext, $allowed);
    }

    /**
     * Generate CSRF-safe token untuk form tambahan
     */
    public static function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Mask data sensitif untuk log (email, nama, dll)
     * Contoh: john@example.com → j***@example.com
     */
    public static function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) return '***';
        $name = $parts[0];
        $domain = $parts[1];
        $masked = substr($name, 0, 1) . str_repeat('*', max(strlen($name) - 1, 2));
        return $masked . '@' . $domain;
    }

    /**
     * Cek apakah request berasal dari IP yang di-whitelist (untuk admin panel)
     * Kosongkan array untuk izinkan semua IP
     */
    public static function isAllowedIp(string $ip, array $whitelist = []): bool
    {
        if (empty($whitelist)) return true;
        return in_array($ip, $whitelist);
    }
}
