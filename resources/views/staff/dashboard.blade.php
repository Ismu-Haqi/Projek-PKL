@extends('staff.layouts.app')

@section('title', 'Dashboard Staff')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Welcome Header with Time-based Greeting --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-500 via-blue-600 to-purple-600 rounded-2xl shadow-2xl mb-8 p-8">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMC41IiBvcGFjaXR5PSIwLjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-20"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-lg mb-2">
                        @php
                            $hour = date('H');
                            if ($hour < 12) echo 'Selamat Pagi';
                            elseif ($hour < 15) echo 'Selamat Siang';
                            elseif ($hour < 18) echo 'Selamat Sore';
                            else echo 'Selamat Malam';
                        @endphp
                    </p>
                    <h1 class="text-4xl font-bold text-white mb-2">{{ Auth::user()->name }} 👋</h1>
                    <p class="text-blue-50 text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ Auth::user()->unit ?? 'Staff' }} - GANDARIA Arsip Digital
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/20 backdrop-blur-lg rounded-2xl p-6 text-center border border-white/30">
                        <p class="text-white/80 text-sm mb-1">Hari ini</p>
                        <p class="text-3xl font-bold text-white">{{ date('d') }}</p>
                        <p class="text-white/80 text-sm">{{ \Carbon\Carbon::now()->format('M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Cards - Staff Focused --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Disposisi Saya --}}
        <div class="group bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <div class="w-2 h-2 bg-orange-500 rounded-full mr-2 animate-pulse"></div>
                        <p class="text-sm text-gray-500 font-semibold uppercase">Disposisi Aktif</p>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-800 mb-1">{{ $pendingDispositions ?? 5 }}</h3>
                    <p class="text-xs text-orange-600 font-medium">Perlu ditindaklanjuti</p>
                </div>
                <div class="bg-gradient-to-br from-orange-100 to-orange-200 p-4 rounded-xl group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('staff.disposisi.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-semibold flex items-center">
                Lihat Detail
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        {{-- Arsip Saya --}}
        <div class="group bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></div>
                        <p class="text-sm text-gray-500 font-semibold uppercase">Arsip Saya</p>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-800 mb-1">{{ $myArchivesCount ?? 128 }}</h3>
                    <p class="text-xs text-green-600 font-medium">+12 bulan ini</p>
                </div>
                <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-4 rounded-xl group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('staff.arsip.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold flex items-center">
                Lihat Arsip
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        {{-- Arsip Favorit --}}
        <div class="group bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></div>
                        <p class="text-sm text-gray-500 font-semibold uppercase">Favorit</p>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-800 mb-1">{{ $favoritesCount ?? 24 }}</h3>
                    <p class="text-xs text-gray-600 font-medium">Dokumen tersimpan</p>
                </div>
                <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 p-4 rounded-xl group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('staff.arsip.favorit') }}" class="text-sm text-yellow-600 hover:text-yellow-700 font-semibold flex items-center">
                Lihat Favorit
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        {{-- Notifikasi Baru --}}
        <div class="group bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></div>
                        <p class="text-sm text-gray-500 font-semibold uppercase">Notifikasi</p>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-800 mb-1">{{ $unreadNotifications ?? 8 }}</h3>
                    <p class="text-xs text-red-600 font-medium">Belum dibaca</p>
                </div>
                <div class="bg-gradient-to-br from-red-100 to-red-200 p-4 rounded-xl group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('staff.notifikasi.index') }}" class="text-sm text-red-600 hover:text-red-700 font-semibold flex items-center">
                Lihat Notifikasi
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Disposisi yang Perlu Ditindaklanjuti --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Disposisi Perlu Tindakan</h3>
                            <p class="text-sm text-gray-500">Segera selesaikan tugas berikut</p>
                        </div>
                    </div>
                    <a href="{{ route('staff.disposisi.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                        Lihat Semua →
                    </a>
                </div>
                
               <div class="space-y-3">
                    @forelse($recentDispositions ?? [] as $disposition)
                    <div class="p-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl border-l-4 border-orange-500 hover:shadow-md transition-all">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="bg-orange-500 text-white text-xs font-bold px-2.5 py-1 rounded-lg mr-2">
                                        {{ strtoupper($disposition->priority) }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $disposition->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <h4 class="font-semibold text-gray-800 mb-1">
                                    {{ $disposition->subject ?? 'Tidak ada subjek' }}
                                </h4>
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ Str::limit($disposition->instruction ?? 'Tidak ada instruksi', 100) }}
                                </p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Dari: {{ $disposition->fromUser->name ?? 'Unknown' }}
                                </div>
                            </div>
                            {{-- 🔥 TOMBOL PROSES YANG SUDAH DIPERBAIKI --}}
                            <a href="{{ route('staff.disposisi.show', $disposition->id) }}" 
                            class="ml-4 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                Proses
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500 font-medium">Tidak ada disposisi yang perlu ditindaklanjuti</p>
                        <p class="text-gray-400 text-sm mt-1">Semua disposisi sudah selesai diproses</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column (1/3) --}}
        <div class="space-y-6">
            
            {{-- Quick Actions --}}
            <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white">
                <h3 class="text-xl font-bold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Aksi Cepat
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('staff.arsip.index') }}" class="block w-full bg-white/20 hover:bg-white/30 backdrop-blur-lg p-4 rounded-xl transition-all transform hover:scale-105">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="font-semibold">Cari Arsip</span>
                        </div>
                    </a>
                    <a href="{{ route('staff.arsip.favorit') }}" class="block w-full bg-white/20 hover:bg-white/30 backdrop-blur-lg p-4 rounded-xl transition-all transform hover:scale-105">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="font-semibold">Lihat Favorit</span>
                        </div>
                    </a>
                    <a href="{{ route('staff.laporan.index') }}" class="block w-full bg-white/20 hover:bg-white/30 backdrop-blur-lg p-4 rounded-xl transition-all transform hover:scale-105">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-semibold">Lihat Laporan</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Activity Timeline --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Aktivitas Terkini
                </h3>
                <div class="space-y-4">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $activity->title ?? 'Anda mengunggah arsip baru' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity->time ?? '5 menit lalu' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="border-l-4 border-blue-500 pl-4 py-3 bg-blue-50 rounded-r-lg">
                        <p class="text-sm font-medium text-gray-800">Anda mengunggah arsip baru</p>
                        <p class="text-xs text-blue-600 font-medium mt-1">Laporan Keuangan Q4 2023</p>
                        <p class="text-xs text-gray-500 mt-1">5 menit lalu</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-4 py-3 bg-green-50 rounded-r-lg">
                        <p class="text-sm font-medium text-gray-800">Disposisi selesai diproses</p>
                        <p class="text-xs text-green-600 font-medium mt-1">SK Bupati No. 001/2024</p>
                        <p class="text-xs text-gray-500 mt-1">2 jam lalu</p>
                    </div>
                    <div class="border-l-4 border-yellow-500 pl-4 py-3 bg-yellow-50 rounded-r-lg">
                        <p class="text-sm font-medium text-gray-800">Arsip ditandai favorit</p>
                        <p class="text-xs text-yellow-600 font-medium mt-1">Proposal Anggaran 2024</p>
                        <p class="text-xs text-gray-500 mt-1">1 hari lalu</p>
                    </div>
                    @endforelse
                </div>
                <a href="#" class="block text-center text-sm text-blue-600 hover:text-blue-700 font-semibold mt-4">
                    Lihat Semua Aktivitas →
                </a>
            </div>

            {{-- Tips & Info --}}
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl shadow-lg border border-yellow-200 p-6">
                <div class="flex items-start mb-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-bold text-yellow-900 mb-2">💡 Tips Hari Ini</h4>
                        <p class="text-sm text-yellow-800">Gunakan fitur <span class="font-semibold">Arsip Favorit</span> untuk menyimpan dokumen penting yang sering Anda akses!</p>
                    </div>
                </div>
            </div>

            {{-- Storage Info --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                    Penyimpanan
                </h3>
                <div class="mb-3">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Digunakan</span>
                        <span class="font-semibold text-gray-800">2.8 GB / 10 GB</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full" style="width: 28%"></div>
                    </div>
                </div>
                <p class="text-xs text-gray-500">Anda masih memiliki <span class="font-semibold text-blue-600">7.2 GB</span> ruang tersedia</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card {
    animation: fadeInUp 0.6s ease-out;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }
</style>
@endpush

@endsection