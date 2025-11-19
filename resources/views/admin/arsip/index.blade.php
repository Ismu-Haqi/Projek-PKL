@extends('admin.layouts.app') 

@section('title', 'Arsip Digital')

@section('content')
<div class="p-6">

    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg animate-fade-in">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg animate-fade-in">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📁 Arsip Digital</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola dan organisir dokumen arsip digital Anda</p>
        </div>
        
        {{-- Tombol Upload - REDIRECT KE HALAMAN CREATE --}}
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.arsip.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg flex items-center transition duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            Unggah Arsip
        </a>
        @endif
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Arsip</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalArchives ?? 0 }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Favorit</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $favoritesCount ?? 0 }}</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Kategori</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $categoriesCount ?? 0 }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Bulan Ini</p>
                    {{-- Nilai $thisMonthCount akan menunjukkan arsip di bulan November 2025 --}}
                    <h3 class="text-2xl font-bold text-gray-800">{{ $thisMonthCount ?? 0 }}</h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================
        FILTER SETUP (PHP)
        ============================================ --}}
    @php
        // Setting up current date variables based on the context time: November 19, 2025
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $currentYear = date('Y'); // 2025
        $currentMonth = date('n'); // 11
        $currentMonthName = $monthNames[$currentMonth];
        $lastYear = $currentYear - 1; // 2024

        // Logic untuk menentukan label yang sedang aktif di dropdown (sama seperti sebelumnya)
        $activeMonth = request('month');
        $activeYear = request('year');
        $currentPeriodLabel = 'Semua Data';
        
        if ($activeMonth && $activeYear) {
            $currentPeriodLabel = $monthNames[(int)$activeMonth] . ' ' . $activeYear;
        } elseif (!$activeMonth && $activeYear) {
            $currentPeriodLabel = 'Tahun ' . $activeYear;
        }
        if (!$activeMonth && !$activeYear) {
            $currentPeriodLabel = 'Semua Data';
        }

    @endphp

    {{-- ============================================
        FILTER SECTION - AUTO LOAD RESPONSIF
        ============================================ --}}
    <form action="{{ route('admin.arsip.index') }}" method="GET" id="filterForm" class="bg-white p-4 rounded-lg shadow mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            {{-- Search --}}
            <div class="md:col-span-1">
                <div class="relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" 
                            name="search" 
                            id="searchInput"
                            value="{{ request('search') }}" 
                            placeholder="Cari nomor, judul, atau pengirim..." 
                            class="w-full pl-10 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            autocomplete="off">
                    {{-- Loading Indicator untuk Search --}}
                    <div id="searchLoading" class="hidden absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            {{-- Dropdown Filter Periode Cepat (BARU & LENGKAP) --}}
            <div>
                <select name="quick_period_selector" 
                        id="quickPeriodSelect"
                        class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer">
                    
                    {{-- Label Aktif --}}
                    <option value="" selected disabled>{{ $currentPeriodLabel }}</option>
                    <option value="all" data-month="" data-year="">Semua Data</option>
                    <option disabled>--- BULAN {{ $currentYear }} ---</option>

                    {{-- Opsi Bulan-Bulan yang Sudah Berlalu di Tahun Ini (Januari sampai Bulan Lalu) --}}
                    @for ($i = 1; $i < $currentMonth; $i++)
                        @php
                            $monthName = $monthNames[$i];
                            // Set selected jika ini adalah bulan yang sedang difilter
                            $selected = ($activeMonth == $i && $activeYear == $currentYear) ? 'selected' : '';
                        @endphp
                        <option value="month" data-month="{{ $i }}" data-year="{{ $currentYear }}" {{ $selected }}>
                            {{ $monthName }} {{ $currentYear }}
                        </option>
                    @endfor

                    {{-- Opsi Bulan Ini --}}
                    @php
                        $selected = ($activeMonth == $currentMonth && $activeYear == $currentYear) ? 'selected' : '';
                    @endphp
                    <option value="month" data-month="{{ $currentMonth }}" data-year="{{ $currentYear }}" {{ $selected }}>
                        Bulan Ini ({{ $currentMonthName }} {{ $currentYear }})
                    </option>

                    <option disabled>--- TAHUN ---</option>
                    {{-- Opsi Tahun Ini --}}
                    @php
                        $selected = (!$activeMonth && $activeYear == $currentYear) ? 'selected' : '';
                    @endphp
                    <option value="year" data-month="" data-year="{{ $currentYear }}" {{ $selected }}>Tahun Ini ({{ $currentYear }})</option>

                    {{-- Opsi Tahun Lalu dan sebelumnya --}}
                    @for ($y = $lastYear; $y >= $currentYear - 4; $y--)
                        @php
                            $selected = (!$activeMonth && $activeYear == $y) ? 'selected' : '';
                        @endphp
                        <option value="year" data-month="" data-year="{{ $y }}" {{ $selected }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
                
                {{-- Hidden input untuk menampung month dan year yang sebenarnya akan dikirim --}}
                <input type="hidden" name="month" id="hiddenMonth" value="{{ $activeMonth }}">
                <input type="hidden" name="year" id="hiddenYear" value="{{ $activeYear }}">
            </div>

            {{-- Kategori --}}
            <div>
                <select name="category" 
                        id="categoryFilter"
                        class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Unit --}}
            <div>
                <select name="unit" 
                        id="unitFilter"
                        class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer">
                    <option value="">Semua Unit</option>
                    @foreach($units ?? [] as $unit)
                        <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>
                            {{ $unit }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        {{-- Action Buttons --}}
        <div class="mt-4 flex gap-2">
            
            {{-- Reset Button --}}
            <a href="{{ route('admin.arsip.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all flex items-center justify-center" 
               title="Reset Filter">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span class="ml-2 text-sm font-medium text-gray-700 hidden sm:inline">Reset Filter</span>
            </a>
            
            {{-- Loading Indicator --}}
            <div id="filterLoading" class="hidden flex-1 flex items-center justify-center bg-blue-50 rounded-lg px-3">
                <svg class="animate-spin h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm text-blue-600 font-medium hidden md:inline">Memuat...</span>
            </div>
            
        </div>

        {{-- Active Filters Display --}}
        @if(request('search') || request('category') || request('unit') || request('month') || request('year'))
        <div id="activeFilters" class="mt-4 pt-3 border-t border-gray-200 flex flex-wrap gap-2 items-center animate-fade-in">
            <span class="text-sm text-gray-600 font-medium">Filter Aktif:</span>
            <div class="flex flex-wrap gap-2">
                {{-- Periode Filter --}}
                @if(request('month') || request('year'))
                <span class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-semibold mr-1">Periode:</span>
                    <span>
                        @if(request('month') && request('year'))
                            {{ $monthNames[(int)request('month')] }} {{ request('year') }}
                        @elseif(request('year'))
                            Tahun {{ request('year') }}
                        @endif
                    </span>
                    <a href="{{ route('admin.arsip.index', array_filter(['search' => request('search'), 'category' => request('category'), 'unit' => request('unit')])) }}" 
                       class="ml-2 text-blue-600 hover:text-blue-800">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </span>
                @endif
                
                @if(request('search'))
                <span class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="font-semibold mr-1">Pencarian:</span>
                    <span>{{ request('search') }}</span>
                    <a href="{{ route('admin.arsip.index', array_filter(['category' => request('category'), 'unit' => request('unit'), 'month' => request('month'), 'year' => request('year')])) }}" 
                       class="ml-2 text-blue-600 hover:text-blue-800">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </span>
                @endif
                
                @if(request('category'))
                    @php
                        $categoryName = collect($categories)->firstWhere('id', request('category'))->name ?? 'Unknown';
                    @endphp
                <span class="inline-flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="font-semibold mr-1">Kategori:</span>
                    <span>{{ $categoryName }}</span>
                    <a href="{{ route('admin.arsip.index', array_filter(['search' => request('search'), 'unit' => request('unit'), 'month' => request('month'), 'year' => request('year')])) }}" 
                       class="ml-2 text-green-600 hover:text-green-800">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </span>
                @endif
                
                @if(request('unit'))
                <span class="inline-flex items-center bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="font-semibold mr-1">Unit:</span>
                    <span>{{ request('unit') }}</span>
                    <a href="{{ route('admin.arsip.index', array_filter(['search' => request('search'), 'category' => request('category'), 'month' => request('month'), 'year' => request('year')])) }}" 
                       class="ml-2 text-purple-600 hover:text-purple-800">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </span>
                @endif
            </div>
        </div>
        @endif
    </form>

    {{-- Archives List --}}
    @if($archives->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nomor Surat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($archives as $index => $archive)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $archives->firstItem() + $index }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $archive->nomor_surat }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ Str::limit($archive->judul, 50) }}</p>
                                    <p class="text-xs text-gray-500">{{ $archive->pengirim ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $archive->unit ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($archive->tanggal_arsip)->format('d/m/Y') }}
                        </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">
                            {{-- View - Lihat Detail --}}
                            <a href="{{ route('admin.arsip.show', $archive->id) }}" 
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded transition" 
                            title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>

                            {{-- Download --}}
                            <a href="javascript:void(0)" 
                            onclick="confirmDownload('{{ route('admin.arsip.download', $archive->id) }}', '{{ $archive->file_name ?? $archive->nomor_surat }}')"
                            class="p-2 text-green-600 hover:bg-green-50 rounded transition" 
                            title="Download">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </a>

                            {{-- Favorite --}}
                            <form action="{{ route('admin.arsip.favorite', $archive->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="button" 
                                        onclick="confirmToggleFavorite(this, {{ $archive->is_favorite ? 'true' : 'false' }}, '{{ Str::limit($archive->judul, 30) }}')"
                                        class="p-2 text-yellow-600 hover:bg-yellow-50 rounded transition" 
                                        title="{{ $archive->is_favorite ? 'Hapus dari Favorit' : 'Tambahkan ke Favorit' }}">
                                    <svg class="w-5 h-5 {{ $archive->is_favorite ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </button>
                            </form>

                            @if(Auth::user()->role === 'admin')
                            {{-- Edit --}}
                            <a href="{{ route('admin.arsip.edit', $archive->id) }}" 
                            class="p-2 text-orange-600 hover:bg-orange-50 rounded transition" 
                            title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('admin.arsip.destroy', $archive->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmDelete(this, 'Arsip &quot;{{ addslashes(Str::limit($archive->judul, 40)) }}&quot; akan dihapus permanen!')" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded transition" 
                                        title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($archives->hasPages())
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $archives->firstItem() }}</span> sampai 
                    <span class="font-medium">{{ $archives->lastItem() }}</span> dari 
                    <span class="font-medium">{{ $archives->total() }}</span> arsip
                </div>
                <div>
                    {{ $archives->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
    @else
    {{-- Empty State --}}
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Arsip</h3>
        <p class="text-gray-500 mb-6">Mulai unggah arsip digital Anda dengan klik tombol di atas</p>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.arsip.create') }}" 
           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Unggah Arsip Pertama
        </a>
        @endif
    </div>
    @endif

</div>

@push('styles')
<style>
/* ... (CSS Anda, tidak ada perubahan) ... */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

/* Smooth transitions */
#filterForm input,
#filterForm select,
#filterForm button,
#filterForm a {
    transition: all 0.2s ease-in-out;
}

/* Focus states */
#filterForm input:focus,
#filterForm select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #filterForm .grid {
        gap: 0.75rem;
    }
    
    #filterForm input,
    #filterForm select {
        font-size: 14px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const quickPeriodSelect = document.getElementById('quickPeriodSelect');
    const categoryFilter = document.getElementById('categoryFilter');
    const unitFilter = document.getElementById('unitFilter');
    const filterLoading = document.getElementById('filterLoading');
    const searchLoading = document.getElementById('searchLoading');
    const hiddenMonth = document.getElementById('hiddenMonth');
    const hiddenYear = document.getElementById('hiddenYear');
    
    let searchTimeout;
    
    // Function to submit form with loading indicator
    function submitForm() {
        if (filterLoading) {
            filterLoading.classList.remove('hidden');
        }
        filterForm.submit();
    }
    
    // Function to update hidden month/year inputs based on selected option attributes
    function updatePeriodInputs() {
        const selectedOption = quickPeriodSelect.options[quickPeriodSelect.selectedIndex];
        
        // Mengambil nilai dari data-* attribute
        const dataMonth = selectedOption.getAttribute('data-month');
        const dataYear = selectedOption.getAttribute('data-year');
        const valueType = selectedOption.value; // 'month', 'year', atau 'all'

        if (valueType === 'all') {
             // Reset semua filter periode
             hiddenMonth.value = '';
             hiddenYear.value = '';
        } else if (valueType === 'month') {
             // Filter berdasarkan bulan spesifik
             hiddenMonth.value = dataMonth;
             hiddenYear.value = dataYear;
        } else if (valueType === 'year') {
             // Filter berdasarkan tahun spesifik
             hiddenMonth.value = ''; // Pastikan bulan dikosongkan
             hiddenYear.value = dataYear;
        }
        
        // Panggil submit hanya jika ada perubahan yang relevan atau bukan opsi default disabled
        if (quickPeriodSelect.value !== '') {
            submitForm();
        }
    }
    
    // Event listeners untuk perubahan filter
    if (quickPeriodSelect) {
        quickPeriodSelect.addEventListener('change', updatePeriodInputs);
        
        // Logika untuk memastikan opsi yang sedang aktif di URL tetap terpilih saat halaman dimuat
        const currentMonthFromURL = '{{ request('month') }}';
        const currentYearFromURL = '{{ request('year') }}';

        if (currentMonthFromURL || currentYearFromURL) {
            // Cari opsi yang cocok dengan filter yang sedang aktif
            const options = quickPeriodSelect.options;
            let found = false;
            for (let i = 0; i < options.length; i++) {
                const opt = options[i];
                const optMonth = opt.getAttribute('data-month');
                const optYear = opt.getAttribute('data-year');

                // Cek apakah opsi ini cocok dengan filter di URL
                if (optMonth == currentMonthFromURL && optYear == currentYearFromURL) {
                    opt.selected = true;
                    // Hapus opsi default agar opsi yang difilter tampil sebagai header
                    const defaultOption = quickPeriodSelect.querySelector('option[disabled]');
                    if (defaultOption) defaultOption.remove();
                    found = true;
                    break;
                }
            }
            
            // Jika tidak ditemukan dan URL memiliki filter (misalnya tahun yang sangat tua), 
            // biarkan opsi default tampil dengan label dari PHP
            if (!found) {
                // Ensure the initial label is maintained
            }
        }
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            if (searchLoading) {
                searchLoading.classList.remove('hidden');
            }
            
            searchTimeout = setTimeout(function() {
                if (searchLoading) {
                    searchLoading.classList.add('hidden');
                }
                submitForm();
            }, 800); // Debounce
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                if (searchLoading) {
                    searchLoading.classList.add('hidden');
                }
                submitForm();
            }
        });
    }
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', submitForm);
    }
    
    if (unitFilter) {
        unitFilter.addEventListener('change', submitForm);
    }
    
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm();
    });
});

// Show loading on pagination click
document.addEventListener('DOMContentLoaded', function() {
    const paginationLinks = document.querySelectorAll('.pagination a');
    const filterLoading = document.getElementById('filterLoading');
    
    if (paginationLinks.length > 0 && filterLoading) {
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.classList.contains('disabled')) {
                    filterLoading.classList.remove('hidden');
                }
            });
        });
    }
});

/**
 * Confirm Toggle Favorite Function (Using SweetAlert assumed)
 */
function confirmToggleFavorite(button, isFavorite, title = 'arsip ini') {
    // ... (Fungsi SweetAlert/Confirm tetap sama) ...
    const form = button.closest('form');
    
    if (!form) {
        console.error('❌ Error: Form tidak ditemukan!');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error Sistem',
                text: 'Form tidak ditemukan. Silakan refresh halaman.',
                confirmButtonColor: '#dc2626'
            });
        } else {
            alert('Form tidak ditemukan!');
        }
        return false;
    }
    
    if (typeof Swal === 'undefined') {
        if (confirm(isFavorite ? 'Hapus dari favorit?' : 'Tambahkan ke favorit?')) {
            form.submit();
        }
        return;
    }
    
    Swal.fire({
        title: isFavorite ? '⭐ Hapus dari Favorit?' : '⭐ Tambahkan ke Favorit?',
        html: `
            <div class="text-left">
                <p class="text-gray-700 mb-2">${isFavorite ? 'Arsip akan dihapus dari daftar favorit' : 'Arsip akan ditambahkan ke daftar favorit'}</p>
                <p class="text-sm text-gray-600 font-semibold">"${title}"</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: isFavorite ? '#dc2626' : '#eab308',
        cancelButtonColor: '#6b7280',
        confirmButtonText: isFavorite ? '<i class="fas fa-star mr-2"></i> Hapus dari Favorit' : '<i class="fas fa-star mr-2"></i> Tambahkan ke Favorit',
        reverseButtons: true,
        focusCancel: true,
        allowOutsideClick: true,
        allowEscapeKey: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: isFavorite ? 'Menghapus dari Favorit...' : 'Menambahkan ke Favorit...',
                html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-yellow-500 mb-3"></i><p>Mohon tunggu sebentar</p></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            form.submit();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.close();
        } else {
            Swal.close();
        }
    }).catch((error) => {
        console.error('SweetAlert Error:', error);
        Swal.close();
    });
    
    return false;
}

/**
 * Confirm Download Function (Using SweetAlert assumed)
 */
function confirmDownload(downloadUrl, fileName = 'file') {
    // ... (Fungsi SweetAlert/Confirm tetap sama) ...
    if (typeof Swal === 'undefined') {
        window.location.href = downloadUrl;
        return;
    }
    
    Swal.fire({
        title: '📥 Download File?',
        html: `
            <div class="text-left">
                <p class="text-gray-700 mb-2">Anda akan mengunduh file:</p>
                <p class="text-sm text-blue-600 font-semibold bg-blue-50 p-3 rounded">"${fileName}"</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-download mr-2"></i> Ya, Download',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
        reverseButtons: true,
        allowOutsideClick: true,
        allowEscapeKey: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses Download...',
                html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-green-500 mb-3"></i><p>File sedang diunduh</p></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                timer: 1500,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            setTimeout(() => {
                window.location.href = downloadUrl;
                
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Download Dimulai!',
                        text: 'File sedang diunduh ke perangkat Anda',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }, 500);
            }, 500);
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.close();
        } else {
            Swal.close();
        }
    }).catch((error) => {
        console.error('SweetAlert Error:', error);
        Swal.close();
    });
}

// Auto-hide success/error message
setTimeout(function() {
    const alerts = document.querySelectorAll('.animate-fade-in');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);

// Replicate confirmDelete function (assumed to be available elsewhere)
function confirmDelete(button, message) {
    if (typeof Swal === 'undefined') {
        if (confirm(message)) {
            button.closest('form').submit();
        }
        return;
    }
    
    Swal.fire({
        title: '⚠️ Konfirmasi Hapus',
        html: `<p class="text-gray-700">${message}</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i> Ya, Hapus',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}
</script>
@endpush

@endsection