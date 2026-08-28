<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * ✅ TAMBAHAN BARU (Poin 5 revisi)
 * Layanan pengiriman notifikasi WhatsApp via Fonnte (WhatsApp Gateway
 * non-official). Daftar & dapatkan token gratis untuk development di
 * https://fonnte.com — cocok dipakai untuk kebutuhan skripsi.
 *
 * Cara pakai:
 *   1. Daftar di fonnte.com, scan QR untuk hubungkan nomor WA.
 *   2. Salin Token dari dashboard Fonnte.
 *   3. Isi di .env:  FONNTE_TOKEN=xxxxxxxxxxxx
 */
class WhatsAppService
{
    protected string $endpoint = 'https://api.fonnte.com/send';

    /**
     * Kirim pesan WhatsApp ke satu nomor.
     *
     * @param string|null $phone  Nomor tujuan (format bebas: 08xx, +62xx, 62xx)
     * @param string      $message
     * @return bool  true jika berhasil terkirim (atau berhasil dikirim ke antrean Fonnte)
     */
    public function send(?string $phone, string $message): bool
    {
        if (!config('services.fonnte.enabled', true)) {
            return false;
        }

        $token = config('services.fonnte.token');

        if (empty($token)) {
            Log::warning('[WhatsAppService] FONNTE_TOKEN belum diisi di .env, notifikasi WA dilewati.');
            return false;
        }

        $normalized = $this->normalizePhone($phone);

        if (!$normalized) {
            Log::warning('[WhatsAppService] Nomor WhatsApp tidak valid/kosong, notifikasi dilewati.', ['phone' => $phone]);
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post($this->endpoint, [
                'target'  => $normalized,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful() && ($response->json('status') ?? true)) {
                return true;
            }

            Log::warning('[WhatsAppService] Gagal kirim WA', [
                'phone'    => $normalized,
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('[WhatsAppService] Error kirim WA: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim pesan ke banyak nomor sekaligus. Mengembalikan jumlah yang
     * berhasil terkirim.
     */
    public function sendToMany(array $phones, string $message): int
    {
        $sukses = 0;

        foreach ($phones as $phone) {
            if ($this->send($phone, $message)) {
                $sukses++;
            }
        }

        return $sukses;
    }

    /**
     * Rapikan nomor telepon ke format 62xxxxxxxxxx yang dibutuhkan Fonnte.
     */
    private function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Buang semua karakter selain digit
        $digits = preg_replace('/\D/', '', $phone);

        if (empty($digits)) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (!str_starts_with($digits, '62')) {
            $digits = '62' . $digits;
        }

        return $digits;
    }
}
