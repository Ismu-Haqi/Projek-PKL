@extends('staff.layouts.app')

@section('title', 'Laporan Saya')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📊 Laporan Saya</h1>
            <p class="text-gray-600 mt-1">Laporan aktivitas dan kontribusi Anda di Sistem GANDARIA</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-blue-700 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            {{ Auth::user()->name }} — {{ Auth::user()->unit ?? 'Staff' }}
        </div>
    </div>

    {{-- Banner --}}
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-5 text-white">
        <div class="flex items-start gap-4">
            <div class="bg-white/20 p-3 rounded-lg flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-lg">5 Laporan Personal Staff</p>
                <p class="text-blue-100 text-sm mt-1">Semua laporan menampilkan <strong>data milik Anda sendiri</strong>. Setiap laporan dapat diekspor ke PDF untuk keperluan dokumentasi.</p>
            </div>
        </div>
    </div>

    {{-- Grid 5 Laporan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        {{-- 01. Arsip Saya --}}
        <div class="bg-white rounded-xl border-2 border-blue-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('staff.laporan.arsip') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-blue-600 font-bold text-sm bg-blue-50 px-2 py-0.5 rounded-full">01</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Laporan Arsip Saya</h4>
                <p class="text-xs text-gray-500">Rekap arsip yang Anda upload berdasarkan kategori & periode</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('staff.laporan.print-pdf', ['type' => 'arsip']) }}" target="_blank"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak PDF
                </a>
            </div>
        </div>

        {{-- 02. Disposisi Saya --}}
        <div class="bg-white rounded-xl border-2 border-purple-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('staff.laporan.disposisi') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-purple-600 font-bold text-sm bg-purple-50 px-2 py-0.5 rounded-full">02</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Laporan Disposisi Saya</h4>
                <p class="text-xs text-gray-500">Tracking disposisi yang Anda terima & progres penyelesaiannya</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('staff.laporan.print-pdf', ['type' => 'disposisi']) }}" target="_blank"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak PDF
                </a>
            </div>
        </div>

        {{-- 03. Surat Masuk Saya --}}
        <div class="bg-white rounded-xl border-2 border-teal-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('staff.laporan.surat-masuk') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-teal-600 font-bold text-sm bg-teal-50 px-2 py-0.5 rounded-full">03</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Laporan Surat Masuk</h4>
                <p class="text-xs text-gray-500">Surat masuk yang Anda input beserta status disposisinya</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('staff.laporan.print-pdf', ['type' => 'surat-masuk']) }}" target="_blank"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak PDF
                </a>
            </div>
        </div>

        {{-- 04. Peminjaman Aset Saya --}}
        <div class="bg-white rounded-xl border-2 border-orange-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('staff.laporan.peminjaman') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <span class="text-orange-600 font-bold text-sm bg-orange-50 px-2 py-0.5 rounded-full">04</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Peminjaman Aset Saya</h4>
                <p class="text-xs text-gray-500">Riwayat aset yang pernah Anda pinjam & status pengembaliannya</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('staff.laporan.print-pdf', ['type' => 'peminjaman']) }}" target="_blank"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak PDF
                </a>
            </div>
        </div>

        {{-- 05. Aktivitas Per Periode --}}
        <div class="bg-white rounded-xl border-2 border-indigo-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('staff.laporan.periode') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-indigo-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-indigo-600 font-bold text-sm bg-indigo-50 px-2 py-0.5 rounded-full">05</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Laporan Per Periode</h4>
                <p class="text-xs text-gray-500">Rekap seluruh aktivitas Anda berdasarkan rentang tanggal</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('staff.laporan.print-pdf', ['type' => 'arsip']) }}" target="_blank"
                   class="flex items-center justify-center gap-1.5 w-full bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Lihat Laporan
                </a>
            </div>
        </div>

        {{-- 06. Pemusnahan Aset --}}
        <div class="bg-white rounded-xl border-2 border-gray-300 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('staff.laporan.pemusnahan') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-gray-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <span class="text-gray-600 font-bold text-sm bg-gray-100 px-2 py-0.5 rounded-full">06</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Laporan Pemusnahan Aset</h4>
                <p class="text-xs text-gray-500">Rekap usulan pemusnahan aset yang Anda ajukan</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('staff.laporan.print-pdf', ['type' => 'pemusnahan']) }}" target="_blank"
                   class="flex items-center justify-center gap-1.5 w-full bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Lihat Laporan
                </a>
            </div>
        </div>

    </div>

    {{-- Catatan --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-blue-700">
            Semua laporan di atas hanya menampilkan <strong>data milik Anda sendiri</strong>. Untuk laporan keseluruhan instansi, hubungi Admin atau Pimpinan.
        </p>
    </div>

</div>
@endsection
