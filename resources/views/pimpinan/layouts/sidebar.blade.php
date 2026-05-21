@php
    $baseIconClass = 'w-5 h-5 mr-3 flex-shrink-0';
    $linkClass = 'flex items-center px-4 py-3 text-gray-700 hover:bg-purple-50 hover:text-purple-600 rounded-lg transition-colors';
    $activeClass = 'bg-purple-50 text-purple-600 font-semibold';
    $isActive = fn($route) => request()->routeIs($route) ? $activeClass : '';
@endphp

<div id="sidebar" class="sidebar fixed h-full p-4 flex flex-col justify-between z-10">
    <div class="flex flex-col h-full">

        <button id="sidebar-toggle" class="rounded-full sidebar-toggle-btn">
            <svg id="toggle-icon" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <a href="{{ route('pimpinan.dashboard') }}" class="text-2xl font-bold text-gray-800 mb-8 border-b border-gray-100 pb-4 flex items-center sidebar-title hover:opacity-80 transition-opacity">
            <img src="https://ui-avatars.com/api/?name=G&background=2563eb&color=fff&size=40" alt="Logo" class="w-8 h-8 mr-2 rounded-full">
            <span class="text-blue-700">GANDARIA</span>
        </a>

        <nav class="space-y-1 sidebar-menu overflow-y-auto flex-1 pb-4">

            <p class="text-xs text-gray-400 font-semibold uppercase mb-2 sidebar-title">BERANDA</p>

            <a href="{{ route('pimpinan.dashboard') }}"
               class="{{ $linkClass }} {{ $isActive('pimpinan.dashboard') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <p class="text-xs text-gray-400 font-semibold uppercase pt-4 pb-2 sidebar-title">SURAT & ARSIP</p>

            {{-- ✅ MENU BARU: Surat Masuk (read-only untuk pimpinan) --}}
            <a href="{{ route('pimpinan.surat-masuk.index') }}"
               class="{{ $linkClass }} {{ $isActive('pimpinan.surat-masuk.*') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Surat Masuk</span>
            </a>

            {{-- Disposisi --}}
            <a href="{{ route('pimpinan.disposisi.index') }}"
               class="{{ $linkClass }} {{ $isActive('pimpinan.disposisi.*') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Disposisi</span>
            </a>

            {{-- Arsip Digital --}}
            <a href="{{ route('pimpinan.arsip.index') }}"
               class="{{ $linkClass }} {{ $isActive('pimpinan.arsip.*') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                <span>Arsip Digital</span>
            </a>

            <p class="text-xs text-gray-400 font-semibold uppercase pt-4 pb-2 sidebar-title">MANAJEMEN</p>

            {{-- Manajemen User --}}
            <a href="{{ route('pimpinan.user.index') }}"
               class="{{ $linkClass }} {{ $isActive('pimpinan.user.*') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Manajemen User</span>
            </a>

            {{-- Manajemen Aset --}}
            <a href="{{ route('pimpinan.aset.index') }}"
               class="{{ $linkClass }} {{ $isActive('pimpinan.aset.*') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Manajemen Aset</span>
            </a>

            <p class="text-xs text-gray-400 font-semibold uppercase pt-4 pb-2 sidebar-title">LAPORAN & SISTEM</p>

            {{-- Laporan --}}
            <a href="{{ route('pimpinan.laporan.index') }}"
               class="{{ $linkClass }} {{ $isActive('pimpinan.laporan.*') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Laporan</span>
            </a>

            {{-- Notifikasi --}}
            <a href="{{ route('pimpinan.notifikasi.index') }}"
               class="{{ $linkClass }} {{ $isActive('pimpinan.notifikasi.*') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span>Notifikasi</span>
                @if(isset($unreadNotifications) && $unreadNotifications > 0)
                <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                    {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                </span>
                @endif
            </a>

            {{-- Pengaturan --}}
            <a href="{{ route('pimpinan.pengaturan.index') }}"
               class="{{ $linkClass }} {{ $isActive('pimpinan.pengaturan.*') }}">
                <svg class="{{ $baseIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Pengaturan</span>
            </a>
        </nav>
    </div>

    <div class="p-4 border-t border-gray-100 sidebar-footer mt-auto">
        <p class="text-xs text-gray-400">GANDARIA Versi 1.1</p>
    </div>
</div>