@extends('admin.layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="p-6">
    
    {{-- MAIN HEADER DASHBOARD --}}
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 p-6 rounded-2xl text-white mb-6 shadow-lg card-animate">
        <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}! </h1>
        <p class="text-yellow-100">GANDARIA Sistem pengelolaan arsip dan data aset terpadu, terstruktur, informatif, dan akuntabel</p>
    </div>

    {{-- REMINDER PERAWATAN H-7 --}}
    @include('partials.reminder-perawatan')

    {{-- STATISTIC CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Card 1: Total Arsip --}}
        <div class="stat-card bg-white p-6 rounded-2xl shadow-md border-l-4 border-blue-500 card-animate card-animate-delay-1">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-medium mb-2">Total Arsip</p>
                    <h3 class="text-4xl font-bold text-gray-800 stat-number" data-target="{{ $totalArchives }}">0</h3>
                    <p class="text-xs {{ $archivesGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            @if($archivesGrowth >= 0)
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                            @else
                            <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z"/>
                            @endif
                        </svg>
                        {{ abs($archivesGrowth) }}% dari bulan lalu
                    </p>
                </div>
                <div class="stat-icon bg-blue-100 p-3 rounded-xl flex-shrink-0 ml-3">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full progress-bar" style="width: 0%" data-progress="75"></div>
            </div>
        </div>

        {{-- Card 2: Arsip Bulan Ini --}}
        <div class="stat-card bg-white p-6 rounded-2xl shadow-md border-l-4 border-purple-500 card-animate card-animate-delay-2">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-medium mb-2">Arsip Bulan Ini</p>
                    <h3 class="text-4xl font-bold text-gray-800 stat-number" data-target="{{ $currentMonthArchives }}">0</h3>
                    <p class="text-xs {{ $monthlyGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            @if($monthlyGrowth >= 0)
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                            @else
                            <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z"/>
                            @endif
                        </svg>
                        {{ abs($monthlyGrowth) }}% dari bulan lalu
                    </p>
                </div>
                <div class="stat-icon bg-purple-100 p-3 rounded-xl flex-shrink-0 ml-3">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-purple-600 h-2 rounded-full progress-bar" style="width: 0%" data-progress="60"></div>
            </div>
        </div>

        {{-- Card 3: Pengguna Aktif --}}
        <div class="stat-card bg-white p-6 rounded-2xl shadow-md border-l-4 border-green-500 card-animate card-animate-delay-3">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-medium mb-2">Pengguna Aktif</p>
                    <h3 class="text-4xl font-bold text-gray-800 stat-number" data-target="{{ $activeUsers }}">0</h3>
                    <p class="text-xs text-green-600 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                        </svg>
                        {{ $activeUsersPercentage }}% aktif
                    </p>
                </div>
                <div class="stat-icon bg-green-100 p-3 rounded-xl flex-shrink-0 ml-3">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full progress-bar" style="width: 0%" data-progress="{{ $activeUsersPercentage }}"></div>
            </div>
        </div>

        {{-- Card 4: Disposisi --}}
        <div class="stat-card bg-white p-6 rounded-2xl shadow-md border-l-4 border-red-500 card-animate card-animate-delay-4">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-medium mb-2">Disposisi</p>
                    <h3 class="text-4xl font-bold text-gray-800 stat-number" data-target="{{ $pendingDispositions }}">0</h3>
                    <p class="text-xs text-orange-600 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                        </svg>
                        {{ $dispositionPercentage }}% pending
                    </p>
                </div>
                <div class="stat-icon bg-red-100 p-3 rounded-xl flex-shrink-0 ml-3">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-red-600 h-2 rounded-full progress-bar" style="width: 0%" data-progress="{{ $dispositionPercentage }}"></div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- LEFT COLUMN --}}
        <div class="lg:col-span-8 space-y-6">
            
            {{-- CHART: Tren Pengarsipan --}}
            <div class="bg-white p-6 rounded-2xl shadow-md card-animate" style="animation-delay: 0.5s;">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Tren Pengarsipan Bulanan</h3>
                        <p class="text-sm text-gray-500 mt-1" id="chartSubtitle">Data 6 bulan terakhir</p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <select id="periodFilter" class="text-sm border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="1month">1 Bulan Terakhir</option>
                            <option value="3month">3 Bulan Terakhir</option>
                            <option value="6month" selected>6 Bulan Terakhir</option>
                            <option value="1year">1 Tahun Terakhir</option>
                            <option value="custom">Custom Range</option>
                        </select>
                        <button id="refreshChart" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>

                {{-- Custom Date Range (Hidden by default) --}}
                <div id="customDateRange" class="hidden mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                            <input type="date" id="startDate" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                            <input type="date" id="endDate" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex items-end">
                            <button id="applyCustomDate" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Chart Statistics --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-600 mb-1">Total</p>
                        <p class="text-2xl font-bold text-blue-600" id="chartStatTotal">0</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-600 mb-1">Rata-rata</p>
                        <p class="text-2xl font-bold text-green-600" id="chartStatAvg">0</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-600 mb-1">Tertinggi</p>
                        <p class="text-2xl font-bold text-purple-600" id="chartStatMax">0</p>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-xl">
                        <p class="text-xs text-gray-600 mb-1">Terendah</p>
                        <p class="text-2xl font-bold text-orange-600" id="chartStatMin">0</p>
                    </div>
                </div>

                {{-- Loading State --}}
                <div id="chartLoading" class="hidden text-center py-12">
                    <svg class="animate-spin h-12 w-12 text-blue-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500">Memuat data chart...</p>
                </div>

                {{-- Chart Container --}}
                <div class="chart-container" style="height: 400px;">
                    <div id="archiveChart"></div>
                </div>
            </div>

            {{-- ✅ ARSIP TERBARU - WITH AUTO SCROLL --}}
            <div class="bg-white p-6 rounded-2xl shadow-md card-animate" style="animation-delay: 0.6s;">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Arsip Terbaru</h3>
                    <a href="{{ route('admin.arsip.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                        Lihat Semua →
                    </a>
                </div>
                
                {{-- Auto-scroll Container --}}
                <div class="auto-scroll-wrapper" style="height: 420px; overflow: hidden; position: relative;">
                    <div id="archiveScrollContainer" class="space-y-3">
                        @forelse($latestArchives as $archive)
                        <div class="archive-scroll-item activity-item p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-gray-50 transition-all">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <div class="flex-1">
                                    <a href="{{ route('admin.arsip.show', $archive->id) }}" class="font-semibold text-gray-800 hover:text-blue-600">
                                        {{ $archive->judul }}
                                    </a>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $archive->category->name ?? 'Tanpa Kategori' }} • {{ $archive->tanggal_surat->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="flex gap-2 flex-wrap">
                                    @if($archive->priority == 'urgent')
                                    <span class="text-xs font-medium text-red-700 bg-red-100 px-3 py-1 rounded-full">Mendesak</span>
                                    @elseif($archive->priority == 'high')
                                    <span class="text-xs font-medium text-orange-700 bg-orange-100 px-3 py-1 rounded-full">Tinggi</span>
                                    @else
                                    <span class="text-xs font-medium text-blue-700 bg-blue-100 px-3 py-1 rounded-full">Normal</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p>Belum ada arsip</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- ✅ AKTIVITAS TERKINI - WITH AUTO SCROLL & ASSET ACTIVITIES --}}
            <div class="bg-white p-6 rounded-2xl shadow-md card-animate" style="animation-delay: 0.5s;">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Aktivitas Terkini</h3>
                
                {{-- Auto-scroll Container --}}
                <div class="auto-scroll-wrapper" style="height: 420px; overflow: hidden; position: relative;">
                    <div id="activityScrollContainer" class="space-y-4">
                        @forelse($recentActivities as $activity)
                        <div class="activity-scroll-item activity-item border-l-4 border-{{ $activity['color'] }}-500 pl-4 py-3">
                            <div class="flex items-start gap-3">
                                {{-- Icon based on activity type --}}
                                <div class="flex-shrink-0 mt-1">
                                    @if($activity['type'] === 'upload')
                                        {{-- Archive Upload Icon --}}
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                            </svg>
                                        </div>
                                    @elseif($activity['type'] === 'disposition')
                                        {{-- Disposition Icon --}}
                                        <div class="bg-green-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"/>
                                            </svg>
                                        </div>
                                    @elseif(str_starts_with($activity['type'], 'asset_borrow'))
                                        {{-- Asset Borrow Icons --}}
                                        @if($activity['type'] === 'asset_borrow_pending')
                                            <div class="bg-yellow-100 p-2 rounded-lg">
                                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                                </svg>
                                            </div>
                                        @elseif($activity['type'] === 'asset_borrow_approved')
                                            <div class="bg-green-100 p-2 rounded-lg">
                                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                                </svg>
                                            </div>
                                        @elseif($activity['type'] === 'asset_borrow_rejected')
                                            <div class="bg-red-100 p-2 rounded-lg">
                                                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    @elseif($activity['type'] === 'asset_borrowed')
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"/>
                                            </svg>
                                        </div>
                                    @elseif($activity['type'] === 'asset_returned')
                                        <div class="bg-purple-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"/>
                                            </svg>
                                        </div>
                                    @elseif($activity['type'] === 'asset_overdue')
                                        <div class="bg-red-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                            </svg>
                                        </div>
                                    @elseif($activity['type'] === 'asset_created')
                                        <div class="bg-indigo-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"/>
                                            </svg>
                                        </div>
                                    @elseif($activity['type'] === 'asset_updated')
                                        <div class="bg-gray-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Activity Content --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $activity['user'] }} 
                                        @if(isset($activity['activity_text']))
                                            {{ $activity['activity_text'] }}
                                        @else
                                            @if($activity['type'] === 'upload')
                                                mengunggah arsip baru
                                            @else
                                                memberikan disposisi
                                            @endif
                                        @endif
                                    </p>
                                    <p class="text-sm text-{{ $activity['color'] }}-600 font-medium mt-1 truncate">
                                        {{ Str::limit($activity['title'], 50) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-sm">Belum ada aktivitas</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- CHART: Distribusi Kategori --}}
            <div class="bg-white p-6 rounded-2xl shadow-md card-animate" style="animation-delay: 0.6s;">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Distribusi Kategori Arsip</h3>
                <div class="chart-container" style="height: 300px;">
                    <div id="categoryChart"></div>
                </div>
            </div>

            {{-- AKSI CEPAT --}}
            <div class="bg-white p-6 rounded-2xl shadow-md card-animate" style="animation-delay: 0.7s;">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.arsip.create') }}" class="btn-action w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-xl flex items-center justify-center font-semibold shadow-md hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/>
                            <path d="M9 13h2v5a1 1 0 11-2 0v-5z"/>
                        </svg>
                        Upload Arsip Baru
                    </a>
                    <a href="{{ route('admin.disposisi.create') }}" class="btn-action w-full bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-xl flex items-center justify-center font-semibold shadow-md hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"/>
                        </svg>
                        Buat Disposisi
                    </a>
                    <a href="{{ route('admin.user.index') }}" class="btn-action w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4 rounded-xl flex items-center justify-center font-semibold shadow-md hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        Kelola User
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    .card-animate {
        animation: fadeInUp 0.6s ease-out;
        opacity: 0;
        animation-fill-mode: forwards;
    }
    
    .card-animate-delay-1 { animation-delay: 0.1s; }
    .card-animate-delay-2 { animation-delay: 0.2s; }
    .card-animate-delay-3 { animation-delay: 0.3s; }
    .card-animate-delay-4 { animation-delay: 0.4s; }
    
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.15) rotate(8deg);
    }
    
    .stat-icon {
        transition: transform 0.3s ease;
    }
    
    .stat-number {
        font-variant-numeric: tabular-nums;
    }
    
    .progress-bar {
        transition: width 1s ease-out;
    }
    
    .activity-item {
        animation: slideInRight 0.5s ease-out;
        transition: all 0.3s ease;
    }
    
    .activity-item:hover {
        transform: translateX(5px);
    }
    
    .btn-action {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-action::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .btn-action:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
    }
    
    .chart-container {
        position: relative;
        transition: all 0.3s ease;
    }

    /* ApexCharts custom styling */
    .apexcharts-tooltip {
        background: rgba(0, 0, 0, 0.85) !important;
        border: none !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
    }

    .apexcharts-tooltip-title {
        background: rgba(255, 255, 255, 0.1) !important;
        border: none !important;
        font-weight: 600 !important;
    }

    /* ============================================ */
    /* ✅ AUTO-SCROLL ANIMATION STYLES */
    /* ============================================ */
    .auto-scroll-wrapper {
        position: relative;
        overflow: hidden;
    }

    .auto-scroll-wrapper::before,
    .auto-scroll-wrapper::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        height: 30px;
        z-index: 10;
        pointer-events: none;
    }

    .auto-scroll-wrapper::before {
        top: 0;
        background: linear-gradient(to bottom, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0));
    }

    .auto-scroll-wrapper::after {
        bottom: 0;
        background: linear-gradient(to top, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0));
    }

    /* Pause on hover */
    .auto-scroll-wrapper:hover .scrolling {
        animation-play-state: paused;
    }

    /* Item fade effect */
    .activity-scroll-item,
    .archive-scroll-item {
        opacity: 1;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .auto-scroll-wrapper:hover .activity-scroll-item,
    .auto-scroll-wrapper:hover .archive-scroll-item {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
{{-- ApexCharts CDN --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================
        // COUNTER ANIMATION
        // ============================================
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;

            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    element.textContent = Math.floor(current).toLocaleString('id-ID');
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target.toLocaleString('id-ID');
                }
            };
            updateCounter();
        }

        // ============================================
        // PROGRESS BAR ANIMATION
        // ============================================
        function animateProgressBar(element) {
            const target = parseInt(element.getAttribute('data-progress'));
            setTimeout(() => {
                element.style.width = target + '%';
            }, 300);
        }

        // Initialize animations
        document.querySelectorAll('.stat-number').forEach(animateCounter);
        document.querySelectorAll('.progress-bar').forEach(animateProgressBar);

        // ============================================
        // GLOBAL CHART VARIABLES
        // ============================================
        let archiveChart = null;
        let categoryChart = null;

        // ============================================
        // ARCHIVE TREND CHART (ApexCharts)
        // ============================================
        function initArchiveChart(categories, data) {
            const options = {
                series: [{
                    name: 'Arsip Diunggah',
                    data: data
                }],
                chart: {
                    type: 'area',
                    height: 400,
                    fontFamily: 'Inter, sans-serif',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true
                        }
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
                },
                colors: ['#3B82F6'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.6,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: categories,
                    labels: {
                        style: {
                            fontSize: '12px',
                            fontWeight: 500,
                            colors: '#6B7280'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '12px',
                            fontWeight: 500,
                            colors: '#6B7280'
                        },
                        formatter: function(value) {
                            return Math.floor(value);
                        }
                    }
                },
                grid: {
                    borderColor: '#F3F4F6',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                tooltip: {
                    theme: 'dark',
                    x: {
                        show: true
                    },
                    y: {
                        formatter: function(value) {
                            return value + ' dokumen';
                        }
                    }
                },
                markers: {
                    size: 5,
                    colors: ['#3B82F6'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 7
                    }
                }
            };

            if (archiveChart) {
                archiveChart.destroy();
            }

            archiveChart = new ApexCharts(document.querySelector("#archiveChart"), options);
            archiveChart.render();
        }

        // ============================================
        // CATEGORY DISTRIBUTION CHART (Donut)
        // ============================================
        function initCategoryChart() {
            const categoryData = @json($categoryDistribution);
            
            const options = {
                series: categoryData.map(item => item.total),
                chart: {
                    type: 'donut',
                    height: 300,
                    fontFamily: 'Inter, sans-serif',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                labels: categoryData.map(item => item.name),
                colors: ['#3B82F6', '#F59E0B', '#8B5CF6', '#10B981', '#6B7280'],
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom',
                    fontSize: '12px',
                    fontWeight: 500,
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 12
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 5
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName + ': ' + opts.w.globals.series[opts.seriesIndex];
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '16px',
                                    fontWeight: 600,
                                    color: '#1F2937'
                                },
                                value: {
                                    show: true,
                                    fontSize: '24px',
                                    fontWeight: 700,
                                    color: '#3B82F6',
                                    formatter: function(val) {
                                        return val;
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total Arsip',
                                    fontSize: '14px',
                                    fontWeight: 500,
                                    color: '#6B7280',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                stroke: {
                    width: 0
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function(value) {
                            const total = categoryData.reduce((sum, item) => sum + item.total, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return value + ' (' + percentage + '%)';
                        }
                    }
                },
                states: {
                    hover: {
                        filter: {
                            type: 'darken',
                            value: 0.15
                        }
                    },
                    active: {
                        filter: {
                            type: 'darken',
                            value: 0.2
                        }
                    }
                }
            };

            if (categoryChart) {
                categoryChart.destroy();
            }

            categoryChart = new ApexCharts(document.querySelector("#categoryChart"), options);
            categoryChart.render();
        }

        // ============================================
        // LOAD CHART DATA
        // ============================================
        function loadChartData(period, startDate = null, endDate = null) {
            const chartLoading = document.getElementById('chartLoading');
            const chartContainer = document.getElementById('archiveChart');
            
            // Show loading
            chartLoading.classList.remove('hidden');
            chartContainer.style.opacity = '0.3';

            let url = '{{ route("admin.dashboard.chart-data") }}?period=' + period;
            if (period === 'custom' && startDate && endDate) {
                url += '&start_date=' + startDate + '&end_date=' + endDate;
            }

            fetch(url)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        // Update chart
                        initArchiveChart(result.categories, result.data);
                        
                        // Update statistics
                        document.getElementById('chartStatTotal').textContent = result.stats.total.toLocaleString('id-ID');
                        document.getElementById('chartStatAvg').textContent = result.stats.average.toLocaleString('id-ID');
                        document.getElementById('chartStatMax').textContent = result.stats.max.toLocaleString('id-ID');
                        document.getElementById('chartStatMin').textContent = result.stats.min.toLocaleString('id-ID');

                        // Update subtitle
                        const subtitles = {
                            '1month': 'Data 30 hari terakhir',
                            '3month': 'Data 3 bulan terakhir',
                            '6month': 'Data 6 bulan terakhir',
                            '1year': 'Data 12 bulan terakhir',
                            'custom': 'Data custom range'
                        };
                        document.getElementById('chartSubtitle').textContent = subtitles[period] || 'Data pengarsipan';
                    }
                })
                .catch(error => {
                    console.error('Error loading chart data:', error);
                    alert('Gagal memuat data chart. Silakan coba lagi.');
                })
                .finally(() => {
                    // Hide loading
                    chartLoading.classList.add('hidden');
                    chartContainer.style.opacity = '1';
                });
        }

        // ============================================
        // FILTER HANDLERS
        // ============================================
        const periodFilter = document.getElementById('periodFilter');
        const customDateRange = document.getElementById('customDateRange');
        const refreshChart = document.getElementById('refreshChart');
        const applyCustomDate = document.getElementById('applyCustomDate');

        periodFilter.addEventListener('change', function() {
            const period = this.value;
            
            if (period === 'custom') {
                customDateRange.classList.remove('hidden');
            } else {
                customDateRange.classList.add('hidden');
                loadChartData(period);
            }
        });

        refreshChart.addEventListener('click', function() {
            const period = periodFilter.value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (period === 'custom' && (!startDate || !endDate)) {
                alert('Silakan pilih tanggal mulai dan akhir');
                return;
            }

            loadChartData(period, startDate, endDate);
        });

        applyCustomDate.addEventListener('click', function() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate || !endDate) {
                alert('Silakan pilih tanggal mulai dan akhir');
                return;
            }

            if (new Date(startDate) > new Date(endDate)) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                return;
            }

            loadChartData('custom', startDate, endDate);
        });

        // ============================================
        // INITIALIZE CHARTS
        // ============================================
        const monthlyData = @json($monthlyTrend);
        initArchiveChart(
            monthlyData.map(item => item.month),
            monthlyData.map(item => item.count)
        );

        // Initialize stats
        const initialTotal = monthlyData.reduce((sum, item) => sum + item.count, 0);
        const initialAvg = initialTotal / monthlyData.length;
        const initialMax = Math.max(...monthlyData.map(item => item.count));
        const initialMin = Math.min(...monthlyData.map(item => item.count));

        document.getElementById('chartStatTotal').textContent = initialTotal.toLocaleString('id-ID');
        document.getElementById('chartStatAvg').textContent = initialAvg.toFixed(1);
        document.getElementById('chartStatMax').textContent = initialMax.toLocaleString('id-ID');
        document.getElementById('chartStatMin').textContent = initialMin.toLocaleString('id-ID');

        initCategoryChart();

        // ============================================
        // ✅ AUTO-SCROLL ANIMATION FOR ACTIVITIES & ARCHIVES
        // ============================================
        function initAutoScroll(containerId, itemClass) {
            const container = document.getElementById(containerId);
            if (!container) {
                console.warn(`Container ${containerId} not found`);
                return;
            }

            const items = container.querySelectorAll(`.${itemClass}`);
            const itemCount = items.length;

            console.log(`Initializing auto-scroll for ${containerId}: ${itemCount} items`);

            // Only enable auto-scroll if more than 5 items
            if (itemCount <= 5) {
                console.log(`Auto-scroll disabled for ${containerId}: not enough items (${itemCount} <= 5)`);
                return;
            }

            // Remove existing animation first
            container.style.animation = 'none';
            container.classList.remove('scrolling');

            // Duplicate items for seamless loop
            const itemsHTML = container.innerHTML;
            container.innerHTML = itemsHTML + itemsHTML;

            // Wait for DOM update
            setTimeout(() => {
                // Calculate heights with more accurate method
                const allItems = container.querySelectorAll(`.${itemClass}`);
                if (allItems.length === 0) {
                    console.error('No items found after duplication');
                    return;
                }

                const firstItem = allItems[0];
                const itemHeight = firstItem.offsetHeight;
                
                // Detect gap from container class
                let gap = 16; // default space-y-4 = 16px
                if (container.classList.contains('space-y-3')) {
                    gap = 12; // space-y-3 = 12px
                } else if (container.classList.contains('space-y-4')) {
                    gap = 16; // space-y-4 = 16px
                }

                const totalHeight = (itemHeight + gap) * itemCount;

                console.log(`Container: ${containerId}, Item height: ${itemHeight}px, Gap: ${gap}px, Total height: ${totalHeight}px`);

                // Set animation
                const speedPerItem = 4500; // ms per item (smooth & readable)
                const duration = speedPerItem * itemCount;

                // Create unique keyframe name
                const keyframeName = `smoothScroll_${containerId}`;

                // Remove old keyframe if exists
                const oldStyle = document.getElementById(`style_${containerId}`);
                if (oldStyle) {
                    oldStyle.remove();
                }

                // Create keyframes dynamically with unique ID
                const styleSheet = document.createElement('style');
                styleSheet.id = `style_${containerId}`;
                styleSheet.textContent = `
                    @keyframes ${keyframeName} {
                        0% {
                            transform: translateY(0);
                        }
                        100% {
                            transform: translateY(-${totalHeight}px);
                        }
                    }
                `;
                document.head.appendChild(styleSheet);

                // Apply animation
                container.style.animation = `${keyframeName} ${duration}ms linear infinite`;
                container.classList.add('scrolling');

                console.log(`✅ Auto-scroll activated for ${containerId}`);
            }, 100);

            // Pause on hover
            const wrapper = container.parentElement;
            wrapper.addEventListener('mouseenter', () => {
                container.style.animationPlayState = 'paused';
            });

            wrapper.addEventListener('mouseleave', () => {
                container.style.animationPlayState = 'running';
            });
        }

        // Initialize auto-scroll for both sections
        console.log('🚀 Initializing dashboard auto-scroll...');
        
        // Add delay to ensure DOM is fully loaded
        setTimeout(() => {
            initAutoScroll('activityScrollContainer', 'activity-scroll-item');
            initAutoScroll('archiveScrollContainer', 'archive-scroll-item');
        }, 500);

        // ============================================
        // ✅ AUTO-REFRESH DATA WITH ASSET ACTIVITIES
        // ============================================
        
        setInterval(() => {
            fetch('{{ route("admin.dashboard.data") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update Recent Activities WITH ASSET ACTIVITIES
                        const activityContainer = document.getElementById('activityScrollContainer');
                        if (activityContainer && data.recentActivities && data.recentActivities.length > 0) {
                            // Stop existing animation
                            activityContainer.style.animation = 'none';
                            activityContainer.classList.remove('scrolling');
                            
                            let activitiesHTML = '';
                            data.recentActivities.forEach(activity => {
                                // Determine icon and color based on activity type
                                let iconHTML = '';
                                let activityText = '';
                                
                                if (activity.type === 'upload') {
                                    iconHTML = `
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                            </svg>
                                        </div>
                                    `;
                                    activityText = 'mengunggah arsip baru';
                                } else if (activity.type === 'disposition') {
                                    iconHTML = `
                                        <div class="bg-green-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"/>
                                            </svg>
                                        </div>
                                    `;
                                    activityText = 'memberikan disposisi';
                                } else if (activity.type === 'asset_borrow_pending') {
                                    iconHTML = `
                                        <div class="bg-yellow-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                            </svg>
                                        </div>
                                    `;
                                    activityText = activity.activity_text || 'mengajukan peminjaman aset';
                                } else if (activity.type === 'asset_borrow_approved') {
                                    iconHTML = `
                                        <div class="bg-green-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                            </svg>
                                        </div>
                                    `;
                                    activityText = activity.activity_text || 'peminjaman aset disetujui';
                                } else if (activity.type === 'asset_borrowed') {
                                    iconHTML = `
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"/>
                                            </svg>
                                        </div>
                                    `;
                                    activityText = activity.activity_text || 'meminjam aset';
                                } else if (activity.type === 'asset_returned') {
                                    iconHTML = `
                                        <div class="bg-purple-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"/>
                                            </svg>
                                        </div>
                                    `;
                                    activityText = activity.activity_text || 'mengembalikan aset';
                                } else if (activity.type === 'asset_overdue') {
                                    iconHTML = `
                                        <div class="bg-red-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                            </svg>
                                        </div>
                                    `;
                                    activityText = activity.activity_text || 'terlambat mengembalikan aset';
                                } else if (activity.type === 'asset_created') {
                                    iconHTML = `
                                        <div class="bg-indigo-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"/>
                                            </svg>
                                        </div>
                                    `;
                                    activityText = activity.activity_text || 'menambahkan aset baru';
                                } else if (activity.type === 'asset_updated') {
                                    iconHTML = `
                                        <div class="bg-gray-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                            </svg>
                                        </div>
                                    `;
                                    activityText = activity.activity_text || 'memperbarui aset';
                                } else if (activity.type === 'asset_borrow_rejected') {
                                    iconHTML = `
                                        <div class="bg-red-100 p-2 rounded-lg">
                                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                            </svg>
                                        </div>
                                    `;
                                    activityText = activity.activity_text || 'peminjaman aset ditolak';
                                }
                                
                                activitiesHTML += `
                                    <div class="activity-scroll-item activity-item border-l-4 border-${activity.color}-500 pl-4 py-3">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 mt-1">
                                                ${iconHTML}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-800">
                                                    ${activity.user} ${activityText}
                                                </p>
                                                <p class="text-sm text-${activity.color}-600 font-medium mt-1 truncate">
                                                    ${activity.title.substring(0, 50)}${activity.title.length > 50 ? '...' : ''}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">${activity.time}</p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            
                            activityContainer.innerHTML = activitiesHTML;
                            
                            // Reinitialize auto-scroll after a short delay
                            setTimeout(() => {
                                initAutoScroll('activityScrollContainer', 'activity-scroll-item');
                            }, 200);
                        }

                        // Update Latest Archives
                        const archiveContainer = document.getElementById('archiveScrollContainer');
                        if (archiveContainer && data.latestArchives && data.latestArchives.length > 0) {
                            // Stop existing animation
                            archiveContainer.style.animation = 'none';
                            archiveContainer.classList.remove('scrolling');
                            
                            let archivesHTML = '';
                            data.latestArchives.forEach(archive => {
                                let priorityBadge = '';
                                if (archive.priority === 'urgent') {
                                    priorityBadge = '<span class="text-xs font-medium text-red-700 bg-red-100 px-3 py-1 rounded-full">Mendesak</span>';
                                } else if (archive.priority === 'high') {
                                    priorityBadge = '<span class="text-xs font-medium text-orange-700 bg-orange-100 px-3 py-1 rounded-full">Tinggi</span>';
                                } else {
                                    priorityBadge = '<span class="text-xs font-medium text-blue-700 bg-blue-100 px-3 py-1 rounded-full">Normal</span>';
                                }

                                archivesHTML += `
                                    <div class="archive-scroll-item activity-item p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-gray-50 transition-all">
                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                            <div class="flex-1">
                                                <a href="/admin/arsip/${archive.id}" class="font-semibold text-gray-800 hover:text-blue-600">
                                                    ${archive.judul}
                                                </a>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    ${archive.category} • ${archive.tanggal_surat}
                                                </p>
                                            </div>
                                            <div class="flex gap-2 flex-wrap">
                                                ${priorityBadge}
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            
                            archiveContainer.innerHTML = archivesHTML;
                            
                            // Reinitialize auto-scroll after a short delay
                            setTimeout(() => {
                                initAutoScroll('archiveScrollContainer', 'archive-scroll-item');
                            }, 100);
                        }

                        // Update Category Chart if data provided
                        if (data.categoryDistribution) {
                            updateCategoryChart(data.categoryDistribution);
                        }

                        console.log('✅ Dashboard data refreshed successfully (with asset activities)');
                    }
                })
                .catch(error => {
                    console.error('❌ Error refreshing dashboard data:', error);
                });
        }, 30000); // 30 seconds
        
        // Function to update category chart
        function updateCategoryChart(categoryData) {
            if (categoryChart && categoryData && categoryData.length > 0) {
                categoryChart.updateOptions({
                    series: categoryData.map(item => item.total),
                    labels: categoryData.map(item => item.name)
                });
            }
        }
    });
</script>
@endpush