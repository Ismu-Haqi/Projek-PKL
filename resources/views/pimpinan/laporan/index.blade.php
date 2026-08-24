@extends('pimpinan.layouts.app')

@section('title', 'Laporan Eksekutif')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📊 Laporan Eksekutif</h1>
            <p class="text-gray-600 mt-1">Monitoring menyeluruh seluruh aktivitas sistem GANDARIA</p>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-lg px-4 py-2 text-purple-700 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Akses Pimpinan — Semua Laporan + TTE
        </div>
    </div>

    {{-- Banner --}}
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-xl p-5 text-white">
        <div class="flex items-start gap-4">
            <div class="bg-white/20 p-3 rounded-lg flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-lg">9 Laporan Eksekutif dengan Tanda Tangan Elektronik</p>
                <p class="text-purple-100 text-sm mt-1">Setiap laporan dilengkapi tombol <strong>PDF + TTE</strong> — dokumen yang diekspor memiliki QR Code yang dapat diverifikasi keasliannya.</p>
            </div>
        </div>
    </div>

    {{-- Grid 9 Laporan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        {{-- 01. Arsip Digital --}}
        <div class="bg-white rounded-xl border-2 border-blue-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('pimpinan.laporan.arsip') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-blue-600 font-bold text-sm bg-blue-50 px-2 py-0.5 rounded-full">01</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Laporan Arsip Digital</h4>
                <p class="text-xs text-gray-500">Rekap seluruh arsip semua unit & bidang</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('pimpinan.laporan.export-pdf', ['type' => 'arsip']) }}"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l-3-3m0 0l3-3m-3 3h12M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    PDF + TTE
                </a>
            </div>
        </div>

        {{-- 02. Disposisi --}}
        <div class="bg-white rounded-xl border-2 border-purple-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('pimpinan.laporan.disposisi') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-purple-600 font-bold text-sm bg-purple-50 px-2 py-0.5 rounded-full">02</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Laporan Disposisi</h4>
                <p class="text-xs text-gray-500">Progress disposisi seluruh unit & efektivitas</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('pimpinan.laporan.export-pdf', ['type' => 'disposisi']) }}"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l-3-3m0 0l3-3m-3 3h12M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    PDF + TTE
                </a>
            </div>
        </div>

        {{-- 03. Surat Masuk --}}
        <div class="bg-white rounded-xl border-2 border-teal-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('pimpinan.laporan.surat-masuk') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-teal-600 font-bold text-sm bg-teal-50 px-2 py-0.5 rounded-full">03</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Laporan Surat Masuk</h4>
                <p class="text-xs text-gray-500">Rekap surat masuk seluruh instansi & status</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('pimpinan.laporan.export-pdf', ['type' => 'surat-masuk']) }}"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l-3-3m0 0l3-3m-3 3h12M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    PDF + TTE
                </a>
            </div>
        </div>

        {{-- 04. Aset --}}
        <div class="bg-white rounded-xl border-2 border-green-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('pimpinan.laporan.aset') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-green-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="text-green-600 font-bold text-sm bg-green-50 px-2 py-0.5 rounded-full">04</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Laporan Aset</h4>
                <p class="text-xs text-gray-500">Inventaris aset seluruh bidang & nilainya</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('pimpinan.laporan.export-pdf', ['type' => 'aset']) }}"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l-3-3m0 0l3-3m-3 3h12M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    PDF + TTE
                </a>
            </div>
        </div>

        {{-- 05. Penyusutan --}}
        <div class="bg-white rounded-xl border-2 border-orange-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('pimpinan.laporan.penyusutan') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                    </div>
                    <span class="text-orange-600 font-bold text-sm bg-orange-50 px-2 py-0.5 rounded-full">05</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Penyusutan Aset</h4>
                <p class="text-xs text-gray-500">Nilai penyusutan & estimasi umur aset</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('pimpinan.laporan.export-pdf', ['type' => 'penyusutan']) }}"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l-3-3m0 0l3-3m-3 3h12M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    PDF + TTE
                </a>
            </div>
        </div>

        {{-- 06. Peminjaman --}}
        <div class="bg-white rounded-xl border-2 border-yellow-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('pimpinan.laporan.peminjaman') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-yellow-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>
                    <span class="text-yellow-600 font-bold text-sm bg-yellow-50 px-2 py-0.5 rounded-full">06</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Peminjaman Aset</h4>
                <p class="text-xs text-gray-500">Rekap peminjaman aset seluruh staff & unit</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('pimpinan.laporan.export-pdf', ['type' => 'peminjaman']) }}"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l-3-3m0 0l3-3m-3 3h12M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    PDF + TTE
                </a>
            </div>
        </div>

        {{-- 07. Kinerja Per Unit --}}
        <div class="bg-white rounded-xl border-2 border-indigo-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('pimpinan.laporan.unit-kerja') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-indigo-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-indigo-600 font-bold text-sm bg-indigo-50 px-2 py-0.5 rounded-full">07</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Kinerja Per Unit</h4>
                <p class="text-xs text-gray-500">Produktivitas arsip & disposisi tiap bidang</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('pimpinan.laporan.export-pdf', ['type' => 'unit']) }}"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l-3-3m0 0l3-3m-3 3h12M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    PDF + TTE
                </a>
            </div>
        </div>

        {{-- 08. Pengguna --}}
        <div class="bg-white rounded-xl border-2 border-pink-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('pimpinan.laporan.user') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-pink-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span class="text-pink-600 font-bold text-sm bg-pink-50 px-2 py-0.5 rounded-full">08</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Laporan Pengguna</h4>
                <p class="text-xs text-gray-500">Monitoring aktivitas seluruh pengguna sistem</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('pimpinan.laporan.export-pdf', ['type' => 'user']) }}"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l-3-3m0 0l3-3m-3 3h12M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    PDF + TTE
                </a>
            </div>
        </div>

        {{-- 09. Pemeliharaan Aset --}}
        <div class="bg-white rounded-xl border-2 border-red-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('pimpinan.laporan.maintenance') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-red-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-red-600 font-bold text-sm bg-red-50 px-2 py-0.5 rounded-full">09</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Pemeliharaan Aset</h4>
                <p class="text-xs text-gray-500">Status aset rusak & dalam pemeliharaan</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('pimpinan.laporan.export-pdf', ['type' => 'maintenance']) }}"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l-3-3m0 0l3-3m-3 3h12M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    PDF + TTE
                </a>
            </div>
        </div>

        {{-- 10. Pemusnahan Aset --}}
        <div class="bg-white rounded-xl border-2 border-gray-300 overflow-hidden hover:shadow-lg transition-all duration-300">
            <a href="{{ route('pimpinan.laporan.pemusnahan') }}" class="block p-5 group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-gray-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <span class="text-gray-600 font-bold text-sm bg-gray-100 px-2 py-0.5 rounded-full">10</span>
                </div>
                <h4 class="font-bold text-gray-800 mb-1">Pemusnahan Aset</h4>
                <p class="text-xs text-gray-500">Rekap usulan & persetujuan pemusnahan aset</p>
            </a>
            <div class="px-5 pb-4">
                <a href="{{ route('pimpinan.laporan.export-pdf', ['type' => 'pemusnahan']) }}"
                   class="flex items-center justify-center gap-1.5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l-3-3m0 0l3-3m-3 3h12M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    PDF + TTE
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
