@php
    $baseIconClass = 'w-5 h-5 mr-3 flex-shrink-0';
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
        <a href="{{ route('pimpinan.dashboard') }}" class="text-2xl font-bold text-gray-800 mb-8 border-b border-gray-100 pb-4 flex items-center sidebar-title hover:opacity-80 transition-opacity">
            <img src="https://ui-avatars.com/api/?name=G&background=2563eb&color=fff&size=40" alt="Logo" class="w-8 h-8 mr-2 rounded-full">
            <span class="text-blue-700">GANDARIA</span>
        </a>
        
        {{-- Navigasi Menu (Scrollable) --}}
        <nav class="space-y-1 sidebar-menu overflow-y-auto flex-1">
            
            <p class="text-xs text-gray-400 font-semibold uppercase mb-2 sidebar-title">BERANDA</p>
            
            {{-- Dashboard --}}
            <a href="{{ route('pimpinan.dashboard') }}" 
               class="sidebar-link {{ Request::routeIs('pimpinan.dashboard') ? 'active' : '' }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <p class="text-xs text-gray-400 font-semibold uppercase pt-4 pb-2 sidebar-title">MANAJEMEN</p>

<!-- Arsip Digital -->
<a href="{{ route('pimpinan.arsip.index') }}" 
   class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-lg transition-colors {{ request()->routeIs('pimpinan.arsip.*') ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
    </svg>
    Arsip Digital
</a>

<!-- Disposisi -->
<a href="{{ route('pimpinan.disposisi.index') }}" 
   class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-lg transition-colors {{ request()->routeIs('pimpinan.disposisi.*') ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    Disposisi
</a>

<!-- Manajemen User -->
<a href="{{ route('pimpinan.user.index') }}" 
   class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-lg transition-colors {{ request()->routeIs('pimpinan.user.*') ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
    </svg>
    Manajemen User
</a>

<!-- Manajemen Aset -->
<a href="{{ route('pimpinan.aset.index') }}" 
   class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-lg transition-colors {{ request()->routeIs('pimpinan.aset.*') ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
    </svg>
    Manajemen Aset
</a>

<!-- Laporan -->
<a href="{{ route('pimpinan.laporan.index') }}" 
   class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-lg transition-colors {{ request()->routeIs('pimpinan.laporan.*') ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    Laporan
</a>

<!-- Notifikasi -->
<a href="{{ route('pimpinan.notifikasi.index') }}" 
   class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-lg transition-colors {{ request()->routeIs('pimpinan.notifikasi.*') ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
    </svg>
    Notifikasi
    @if(isset($unreadNotifications) && $unreadNotifications > 0)
    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
        {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
    </span>
    @endif
</a>

<!-- Pengaturan -->
<a href="{{ route('pimpinan.pengaturan.index') }}" 
   class="flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-lg transition-colors {{ request()->routeIs('pimpinan.pengaturan.*') ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    Pengaturan
</a>