<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Dokumen — GANDARIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full">

        @if($signature)
        {{-- Dokumen SAH --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-8 py-6 text-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">Dokumen Sah & Valid</h1>
                <p class="text-green-100 mt-1 text-sm">Tanda tangan elektronik terverifikasi</p>
            </div>

            <div class="px-8 py-6 space-y-4">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                    <p class="text-sm text-green-800 font-semibold">
                        ✅ Dokumen ini sah dicetak dari Sistem GANDARIA
                    </p>
                    <p class="text-sm text-green-700 mt-1">
                        pada tanggal <strong>{{ $signature->signed_at->translatedFormat('d F Y \p\u\k\u\l H:i') }} WITA</strong>
                    </p>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500 font-medium">Jenis Dokumen</span>
                        <span class="text-gray-800 font-semibold capitalize">Laporan {{ ucfirst($signature->document_type) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500 font-medium">Judul</span>
                        <span class="text-gray-800 font-semibold text-right max-w-xs">{{ $signature->document_title }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500 font-medium">Ditandatangani oleh</span>
                        <span class="text-gray-800 font-semibold">{{ $signature->signed_by }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500 font-medium">Jabatan</span>
                        <span class="text-gray-800">{{ $signature->signed_by_title ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500 font-medium">Instansi</span>
                        <span class="text-gray-800 text-right max-w-xs">{{ $signature->instansi }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500 font-medium">Token</span>
                        <span class="text-gray-400 font-mono text-xs">{{ substr($signature->token, 0, 16) }}...</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-8 py-4 text-center">
                <p class="text-xs text-gray-400">
                    GANDARIA — Sistem Arsip Digital<br>
                    Dinas Komunikasi dan Informatika Kab. Barito Kuala
                </p>
            </div>
        </div>

        @else
        {{-- Dokumen TIDAK SAH / Token tidak ditemukan --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-rose-600 px-8 py-6 text-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">Dokumen Tidak Valid</h1>
                <p class="text-red-100 mt-1 text-sm">Token tidak ditemukan dalam sistem</p>
            </div>
            <div class="px-8 py-6 text-center">
                <p class="text-gray-600 text-sm">
                    Dokumen ini tidak dapat diverifikasi. Kemungkinan dokumen ini tidak dicetak dari Sistem GANDARIA atau token sudah tidak berlaku.
                </p>
                <p class="text-gray-400 text-xs mt-4">
                    Hubungi administrator jika Anda merasa ini adalah kesalahan.
                </p>
            </div>
        </div>
        @endif

    </div>
</body>
</html>
