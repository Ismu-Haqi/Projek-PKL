@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Laporan & Statistik')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📊 Laporan & Statistik</h1>
            <p class="text-gray-600 mt-1">Dashboard lengkap untuk monitoring dan analisis data</p>
        </div>
    </div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        8 Jenis Laporan Tersedia
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <a href="{{ route(Auth::user()->role . '.laporan.arsip') }}" 
           class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl border-2 border-blue-200 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-blue-600 font-bold text-sm">01</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Laporan Arsip Digital</h4>
            <p class="text-sm text-gray-600">Rekap arsip berdasarkan periode & kategori</p>
        </a>

        <a href="{{ route(Auth::user()->role . '.laporan.disposisi') }}" 
           class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border-2 border-purple-200 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-purple-600 font-bold text-sm">02</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Laporan Disposisi</h4>
            <p class="text-sm text-gray-600">Tracking disposisi arsip & aset</p>
        </a>

        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'pimpinan')
        <a href="{{ route(Auth::user()->role . '.laporan.user') }}" 
           class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border-2 border-green-200 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-green-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="text-green-600 font-bold text-sm">03</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Laporan Aktivitas User</h4>
            <p class="text-sm text-gray-600">Monitoring aktivitas pengguna</p>
        </a>
        @endif

        <a href="{{ route(Auth::user()->role . '.laporan.aset') }}" 
           class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl border-2 border-orange-200 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="text-orange-600 font-bold text-sm">04</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Laporan Aset</h4>
            <p class="text-sm text-gray-600">Inventaris aset per periode & kategori</p>
        </a>

        <a href="{{ route(Auth::user()->role . '.laporan.unit-kerja') }}" 
           class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border-2 border-indigo-200 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-indigo-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="text-indigo-600 font-bold text-sm">05</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Laporan Produktivitas Unit</h4>
            <p class="text-sm text-gray-600">Perbandingan kinerja antar unit</p>
        </a>

        <a href="{{ route(Auth::user()->role . '.laporan.penyusutan') }}" 
           class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-xl border-2 border-teal-200 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-teal-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
                <span class="text-teal-600 font-bold text-sm">06</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Laporan Penyusutan</h4>
            <p class="text-sm text-gray-600">Valuasi nilai aset metode garis lurus</p>
        </a>

        <a href="{{ route(Auth::user()->role . '.laporan.peminjaman') }}" 
           class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl border-2 border-yellow-200 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-yellow-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="text-yellow-600 font-bold text-sm">07</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Laporan Peminjaman</h4>
            <p class="text-sm text-gray-600">Sirkulasi riwayat peminjaman aset</p>
        </a>

        {{-- Card Surat Masuk --}}
        <a href="{{ route(Auth::user()->role . '.laporan.surat-masuk') }}"
           class="bg-gradient-to-br from-teal-50 to-cyan-50 rounded-xl border-2 border-teal-200 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-teal-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-teal-600 font-bold text-sm">08</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Laporan Surat Masuk</h4>
            <p class="text-sm text-gray-600">Rekap surat masuk & status disposisi</p>
        </a>

        <a href="{{ route(Auth::user()->role . '.laporan.maintenance') }}" class="bg-gradient-to-br from-red-50 to-rose-50 rounded-xl border-2 border-red-200 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-red-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.82 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.82 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.82-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.82-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span class="text-red-600 font-bold text-sm">09</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Laporan Pemeliharaan</h4>
            <p class="text-sm text-gray-600">Daftar aset rusak dan pemeliharaan</p>
        </a>

        <a href="{{ route(Auth::user()->role . '.laporan.pemusnahan') }}" class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-xl border-2 border-gray-300 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-gray-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <span class="text-gray-600 font-bold text-sm">10</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Laporan Pemusnahan Aset</h4>
            <p class="text-sm text-gray-600">Rekap usulan & persetujuan pemusnahan aset</p>
        </a>

        <a href="{{ route(Auth::user()->role . '.laporan.agenda-surat') }}" class="bg-gradient-to-br from-sky-50 to-cyan-50 rounded-xl border-2 border-sky-300 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-sky-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <span class="text-sky-600 font-bold text-sm">11</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Rekap Agenda Surat</h4>
            <p class="text-sm text-gray-600">Buku agenda gabungan surat masuk & keluar</p>
        </a>

        <a href="{{ route(Auth::user()->role . '.laporan.surat-keluar') }}" class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-xl border-2 border-teal-300 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-teal-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </div>
                <span class="text-teal-600 font-bold text-sm">12</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Laporan Surat Keluar</h4>
            <p class="text-sm text-gray-600">Rekap surat keluar & status TTE</p>
        </a>

        <a href="{{ route(Auth::user()->role . '.laporan.beban-kerja-pimpinan') }}" class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl border-2 border-indigo-300 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <span class="text-indigo-600 font-bold text-sm">11</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Beban Kerja Validasi Pimpinan</h4>
            <p class="text-sm text-gray-600">Jumlah &amp; waktu proses persetujuan pimpinan</p>
        </a>
        
    </div>
</div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Total Arsip</p>
            <p class="text-3xl font-bold text-gray-800">{{ $archiveStats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Periode saat ini</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Total Disposisi</p>
            <p class="text-3xl font-bold text-gray-800">{{ $dispositionStats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-2">
                <span class="text-green-600 font-semibold">{{ $dispositionStats['completed'] }}</span> selesai
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $dispositionStats['pending'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Menunggu proses</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Overdue</p>
            <p class="text-3xl font-bold text-red-600">{{ $dispositionStats['overdue'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Melewati deadline</p>
        </div>
    </div>

@if(auth()->user()->role === 'admin')
<div class="flex gap-2 mb-4">
    <a href="{{ route('admin.laporan.print-pdf', ['type' => 'summary']) }}" 
       target="_blank"
       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print PDF
    </a>
    
    <a href="{{ route('admin.laporan.export-pdf', ['type' => 'summary']) }}" 
       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Download PDF
    </a>
</div>
@endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                Arsip per Kategori
            </h3>
            <div class="relative" style="height: 300px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Disposisi per Status
            </h3>
            <div class="relative" style="height: 300px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
            </svg>
            Tren Arsip per Bulan
        </h3>
        <div class="relative" style="height: 350px;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Prevent auto scroll issue
document.addEventListener('DOMContentLoaded', function() {
    // Category Chart
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        new Chart(categoryCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($chartData['archives_by_category']->keys()),
                datasets: [{
                    data: @json($chartData['archives_by_category']->values()),
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(168, 85, 247)',
                        'rgb(34, 197, 94)',
                        'rgb(249, 115, 22)',
                        'rgb(239, 68, 68)',
                        'rgb(236, 72, 153)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Status Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($chartData['dispositions_by_status']->keys()),
                datasets: [{
                    label: 'Total',
                    data: @json($chartData['dispositions_by_status']->values()),
                    backgroundColor: [
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)'
                    ],
                    borderColor: [
                        'rgb(234, 179, 8)',
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($chartData['archives_per_month']->keys()),
                datasets: [{
                    label: 'Arsip',
                    data: @json($chartData['archives_per_month']->values()),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }
});
</script>
{{-- Print Styles --}}
<style media="print">
    /* Hide elements when printing */
    @media print {
        /* Hide navigation, sidebar, buttons */
        nav, .sidebar, aside, header, footer,
        button, .no-print, .print-hide,
        a[href^="http"]:not(.print-show),
        .bg-gradient-to-r.from-purple-500,
        .bg-gradient-to-r.from-orange-500,
        svg.w-6.h-6.text-gray-600,
        .flex.items-center > a[href*="laporan.index"] {
            display: none !important;
        }

        /* Reset page margins */
        @page {
            margin: 1cm;
            size: A4;
        }

        body {
            margin: 0;
            padding: 0;
            background: white !important;
        }

        /* Make tables fit on page */
        table {
            page-break-inside: auto;
            width: 100%;
            border-collapse: collapse;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        /* Add page header for print */
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #333;
        }

        .print-header h1 {
            font-size: 18px;
            margin: 0;
            color: #333;
        }

        .print-header p {
            font-size: 12px;
            margin: 5px 0;
            color: #666;
        }

        /* Remove colors for black & white printing */
        * {
            color: #000 !important;
            background: white !important;
            box-shadow: none !important;
        }

        /* Keep badges visible */
        .badge, span[class*="bg-"] {
            border: 1px solid #333 !important;
            padding: 2px 6px !important;
            color: #000 !important;
        }

        /* Chart containers */
        canvas {
            max-height: 400px !important;
        }

        /* Remove shadows and gradients */
        .shadow-sm, .shadow-lg, .shadow-xl {
            box-shadow: none !important;
        }

        /* Adjust spacing */
        .space-y-6 > * + * {
            margin-top: 20px !important;
        }

        /* Footer for print */
        .print-footer {
            display: block !important;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
    }
</style>

{{-- Print Header (hidden on screen) --}}
<div class="print-header" style="display: none;">
    <h1>LAPORAN [NAMA LAPORAN]</h1>
    <p>GANDARIA -Sistem pengelolaan arsip dan data aset terpadu, terstruktur, informatif, dan akuntabel</p>
    <p>Dinas Komunikasi dan Informatika</p>
    <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
</div>

{{-- Print Footer (hidden on screen) --}}
<div class="print-footer" style="display: none;">
    <p>GANDARIA - Sistem Arsip Digital | Halaman [Page] dari [Total Pages]</p>
</div>
@endsection