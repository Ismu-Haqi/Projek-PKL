@php
    $currentRoute = Request::routeIs('staff.*') ? Request::route()->getName() : '';
    $activeRouteClass = 'active';
    $baseIconClass = 'w-5 h-5 mr-3 flex-shrink-0';
    $isActive = fn ($route) => Request::routeIs($route) ? $activeRouteClass : '';
@endphp

{{-- SIDEBAR CONTAINER --}}
<div id="sidebar" class="sidebar fixed h-full p-4 flex flex-col justify-between z-10">
    <div class="flex flex-col h-full">
        
        {{-- Tombol Toggle Sidebar --}}
        <button id="sidebar-toggle" class="rounded-full sidebar-toggle-btn">
            <svg id="toggle-icon" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        {{-- Logo GANDARIA --}}
        <div class="text-2xl font-bold text-gray-800 mb-8 border-b border-gray-100 pb-4 flex items-center sidebar-title">
            <img src="https://ui-avatars.com/api/?name=G&background=2563eb&color=fff&size=40" alt="Logo" class="w-8 h-8 mr-2 rounded-full">
            <span class="text-blue-700">GANDARIA</span>
        </div>
        
        {{-- Navigasi Menu (Scrollable) --}}
        <nav class="space-y-1 sidebar-menu">
            
            <p class="text-xs text-gray-400 font-semibold uppercase mb-2 sidebar-title">BERANDA</p>
            {{-- Dashboard --}}
            <a href="{{ route('staff.dashboard') }}" 
               class="sidebar-link {{ $isActive('staff.dashboard') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7m7-7V4a1 1 0 00-1-1h-1m-4 17V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v16a1 1 0 001 1h4a1 1 0 001-1z"></path></svg>
                <span>Dashboard</span>
            </a>

            <p class="text-xs text-gray-400 font-semibold uppercase pt-4 pb-2 sidebar-title">MANAJEMEN</p>
            
            {{-- Arsip Digital --}}
            <a href="{{ route('staff.arsip.index') }}" 
               class="sidebar-link {{ $isActive('staff.arsip.index') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span>Arsip Digital</span>
            </a>
            
            {{-- ARSIP FAVORIT --}}
            <a href="{{ route('staff.arsip.favorit') }}" 
               class="sidebar-link {{ $isActive('staff.arsip.favorit') }}">
                <svg class="{{ $baseIconClass }}" fill="currentColor" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span>Arsip Favorit</span>
            </a>
            
            {{-- KATEGORI ARSIP --}}
            <a href="{{ route('staff.category.index') }}" 
               class="sidebar-link {{ $isActive('staff.category.index') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <span>Kategori Arsip</span>
            </a>

            {{-- DISPOSISI --}}
            <a href="{{ route('staff.disposition.index') }}" 
               class="sidebar-link {{ $isActive('staff.disposition.index') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Disposisi</span>
            </a>
            
            {{-- Manajemen Aset --}}
            <a href="{{ route('staff.asset.index') }}" 
               class="sidebar-link {{ $isActive('staff.asset.index') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-1 4h1m8-12h2M12 7h1m-1 4h1m-1 4h1m8-4h2"></path></svg>
                <span>Manajemen Aset</span>
            </a>

            {{-- Laporan --}}
            <a href="{{ route('staff.report.index') }}" 
               class="sidebar-link {{ $isActive('staff.report.index') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-4m3 4v-4m3 4v-4m-9-4h18M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"></path></svg>
                <span>Laporan</span>
            </a>
            
            {{-- NOTIFIKASI --}}
            <a href="{{ route('staff.notification.index') }}" 
               class="sidebar-link {{ $isActive('staff.notification.index') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span>Notifikasi</span>
            </a>
            
            {{-- Manajemen User (DIHILANGKAN UNTUK STAFF) --}}
            {{-- Pengaturan (DIHILANGKAN UNTUK STAFF) --}}
        </nav>
    </div>
    
    {{-- Footer Sidebar (Placeholder) --}}
    <div class="p-4 border-t border-gray-100 sidebar-footer">
        <p class="text-xs text-gray-400">Versi 1.0.0</p>
    </div>
</div>