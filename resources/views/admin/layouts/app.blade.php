<!DOCTYPE html>
<html lang="id">
<head>
    {{-- Appearance Settings --}}
    @include('admin.layouts.appearance-styles')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GANDARIA - Arsip Digital')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> --}}
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f9fafb;
        }
        
        /* ========================================
           RESPONSIVE SIDEBAR
           ======================================== */
        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 260px;
            background: white;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            z-index: 40;
            overflow-y: auto;
        }
        
        #sidebar.hidden {
            transform: translateX(-100%);
        }
        
        /* ========================================
           RESPONSIVE MAIN CONTENT
           ======================================== */
        #main-content {
            margin-left: 260px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
        
        #main-content.full {
            margin-left: 0;
        }
        
        /* ========================================
           RESPONSIVE HEADER
           ======================================== */
        .top-header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 30;
        }
        
        /* ========================================
           SIDEBAR LINKS - RESPONSIVE
           ======================================== */
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            margin: 3px 6px;
            border-radius: 8px;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 13px;
            position: relative;
        }
        
        .sidebar-link:hover {
            background: #f3f4f6;
            color: #2563eb;
        }
        
        .sidebar-link.active {
            background: #dbeafe;
            color: #2563eb;
            font-weight: 600;
        }
        
        .sidebar-link svg {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            flex-shrink: 0;
        }
        
        /* ========================================
           MENU TOGGLE BUTTON
           ======================================== */
        .menu-toggle {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: none;
            background: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        
        .menu-toggle:hover {
            background: #f3f4f6;
        }
        
        /* ========================================
           DROPDOWN - RESPONSIVE
           ======================================== */
        .dropdown-menu {
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }
        
        .dropdown-menu.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        /* ========================================
           SCROLLBAR CUSTOM
           ======================================== */
        #sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        #sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        #sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        /* ========================================
           OVERLAY UNTUK MOBILE
           ======================================== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 35;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        /* ========================================
           SWEETALERT Z-INDEX FIX
           ======================================== */
        .swal2-container {
            z-index: 99999 !important;
        }

        .swal2-popup {
            z-index: 100000 !important;
        }

        .swal2-container.swal2-backdrop-show {
            background: rgba(0, 0, 0, 0.4) !important;
        }
        
        /* ========================================
           RESPONSIVE BREAKPOINTS
           ======================================== */
        
        /* TABLET (768px - 1024px) */
        @media (min-width: 768px) and (max-width: 1024px) {
            #sidebar {
                width: 220px;
            }
            
            #main-content {
                margin-left: 220px;
            }
            
            .sidebar-link {
                font-size: 12px;
                padding: 8px 10px;
            }
            
            .sidebar-link svg {
                width: 16px;
                height: 16px;
                margin-right: 8px;
            }
        }
        
        /* MOBILE (max-width: 768px) */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            #sidebar.show {
                transform: translateX(0);
            }
            
            #main-content {
                margin-left: 0;
            }
            
            .top-header {
                padding: 0.75rem;
            }
            
            /* Hide user name on mobile header */
            .top-header .hidden.md\\:block {
                display: none !important;
            }
            
            /* Adjust dropdown width on mobile */
            .dropdown-menu {
                width: 90vw;
                max-width: 320px;
                right: 0.5rem;
            }
            
            /* Adjust notification dropdown */
            #notificationDropdown {
                right: 0;
                left: auto;
            }
        }
        
        /* SMALL MOBILE (max-width: 480px) */
        @media (max-width: 480px) {
            #sidebar {
                width: 260px;
            }
            
            .top-header {
                padding: 0.5rem;
            }
            
            .dropdown-menu {
                width: calc(100vw - 1rem);
                max-width: none;
            }
            
            /* Make buttons smaller on small screens */
            .menu-toggle {
                width: 36px;
                height: 36px;
            }
            
            /* Adjust avatar size */
            .top-header .w-10.h-10 {
                width: 2rem !important;
                height: 2rem !important;
            }
        }
        
        /* ========================================
           RESPONSIVE UTILITIES
           ======================================== */
        
        /* Responsive padding for main content */
        @media (max-width: 768px) {
            main.p-6 {
                padding: 1rem !important;
            }
        }
        
        @media (max-width: 480px) {
            main.p-6 {
                padding: 0.75rem !important;
            }
        }
        
        /* Responsive text sizes */
        @media (max-width: 768px) {
            h1 {
                font-size: 1.5rem !important;
            }
            
            h2 {
                font-size: 1.25rem !important;
            }
            
            h3 {
                font-size: 1.125rem !important;
            }
        }
        
        /* Touch-friendly tap targets */
        @media (hover: none) {
            .sidebar-link,
            .menu-toggle,
            button,
            a {
                min-height: 44px;
                min-width: 44px;
            }
        }

        /* ========================================
           MOBILE OPTIMIZATION - POCO X6 PRO & similar
           Target: max-width 430px (6.67 inch screens)
           ======================================== */

        /* ── Tabel: scroll horizontal yang nyaman ── */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 0.5rem;
        }
        .table-responsive table {
            min-width: 600px;
        }
        /* Semua wrapper tabel yang ada overflow-x-auto */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 640px) {

            /* ── Layout padding lebih lega ── */
            .p-6 { padding: 1rem !important; }
            .p-8 { padding: 1rem !important; }
            .px-6 { padding-left: 1rem !important; padding-right: 1rem !important; }

            /* ── Stats grid: 2 kolom di mobile ── */
            .grid.grid-cols-1.md\:grid-cols-4,
            .grid.grid-cols-1.md\:grid-cols-5,
            .grid.grid-cols-1.sm\:grid-cols-2.lg\:grid-cols-4 {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.625rem !important;
            }

            /* ── Stat card lebih compact ── */
            .stat-icon { padding: 0.5rem !important; }
            .stat-icon svg { width: 1.25rem !important; height: 1.25rem !important; }

            /* ── Filter form: stack vertikal ── */
            form .flex.flex-wrap.gap-3,
            form .flex.flex-wrap.gap-4,
            .filter-form { flex-direction: column !important; }
            form .flex.flex-wrap.gap-3 > *,
            form .flex.flex-wrap.gap-4 > * { width: 100% !important; min-width: 0 !important; }
            form select, form input[type="text"],
            form input[type="date"], form input[type="search"] {
                width: 100% !important;
                font-size: 16px !important; /* cegah zoom di iOS */
            }

            /* ── Tombol aksi: full width di mobile ── */
            .action-buttons { flex-direction: column !important; gap: 0.5rem !important; }
            .action-buttons a, .action-buttons button { width: 100% !important; justify-content: center !important; }

            /* ── Header halaman: stack vertikal ── */
            .page-header.flex.justify-between,
            .flex.justify-between.items-center.mb-6 {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.75rem !important;
            }
            .flex.justify-between.items-center.mb-6 > a,
            .flex.justify-between.items-center.mb-6 > button {
                width: 100% !important;
                justify-content: center !important;
            }

            /* ── Tabel: min-width agar tidak terlalu sempit ── */
            table { min-width: 580px !important; }
            table th, table td { padding: 0.5rem 0.625rem !important; font-size: 0.8125rem !important; }

            /* ── Card grid aset: 1 kolom di mobile ── */
            .grid.grid-cols-1.md\:grid-cols-2.lg\:grid-cols-3,
            .grid.grid-cols-1.md\:grid-cols-2.lg\:grid-cols-3.xl\:grid-cols-4 {
                grid-template-columns: repeat(1, 1fr) !important;
            }

            /* ── Form input penuh ── */
            .max-w-3xl, .max-w-4xl, .max-w-2xl { max-width: 100% !important; }
            .grid.grid-cols-2 { grid-template-columns: repeat(1, 1fr) !important; }
            .grid.grid-cols-1.md\:grid-cols-2 { grid-template-columns: repeat(1, 1fr) !important; }

            /* ── Detail show: 2 kolom menjadi 1 ── */
            .lg\:col-span-2 { grid-column: span 1 !important; }
            .grid.grid-cols-1.lg\:grid-cols-3 { grid-template-columns: repeat(1, 1fr) !important; }

            /* ── Badge & chip lebih kecil ── */
            .px-3.py-1 { padding: 0.2rem 0.5rem !important; font-size: 0.75rem !important; }

            /* ── Modal / popup lebih lebar ── */
            .max-w-md { max-width: calc(100vw - 2rem) !important; }
            .max-w-lg { max-width: calc(100vw - 1.5rem) !important; }
            .max-w-xl { max-width: calc(100vw - 1rem) !important; }

            /* ── Tombol top-header ── */
            .top-header .flex.items-center.gap-3 { gap: 0.5rem !important; }

            /* ── Breadcrumb ── */
            .flex.items-center.gap-2.text-sm { flex-wrap: wrap !important; font-size: 0.75rem !important; }

            /* ── Chart/grafik container ── */
            canvas { max-width: 100% !important; }

            /* ── Perpindahan info (mutasi) ── */
            .flex.items-stretch.gap-3,
            .flex.items-center.gap-4 { flex-direction: column !important; }

            /* ── Scroll hint: indikator ada konten horizontal ── */
            .overflow-x-auto::after {
                content: '';
                display: block;
            }
        }

        /* ── Scrollbar mobile tipis ── */
        @media (max-width: 640px) {
            ::-webkit-scrollbar { height: 3px; width: 3px; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        }

        /* ── Tabel: highlight baris saat disentuh ── */
        @media (hover: none) {
            tbody tr:active { background-color: #f1f5f9; }
            .sidebar-link:active { opacity: 0.7; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <aside id="sidebar">
        <div class="p-4">
           <div class="flex items-center mb-6 pb-4 mt-3">
                <img src="{{ asset('images/gandaria.png') }}" 
                    alt="Logo GANDARIA" 
                    class="w-10 h-10 object-contain mr-3 ml-3">
                <span class="text-xl font-bold text-gray-800">GANDARIA</span>
            </div>
            
            <nav>
                <p class="text-xs text-gray-400 font-semibold uppercase mb-2 px-2">BERANDA</p>
                
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2 2v8a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                
                <p class="text-xs text-gray-400 font-semibold uppercase mt-4 mb-2 px-2">SURAT & ARSIP</p>

                {{-- ✅ SURAT MASUK (BARU) --}}
                <a href="{{ route(Auth::user()->role . '.surat-masuk.index') }}" class="sidebar-link {{ Request::routeIs(Auth::user()->role . '.surat-masuk.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Surat Masuk</span>
                </a>

                {{-- Surat Keluar --}}
                <a href="{{ route('admin.surat-keluar.index') }}" class="sidebar-link {{ Request::routeIs('admin.surat-keluar.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                    <span>Surat Keluar</span>
                </a>

                <p class="text-xs text-gray-400 font-semibold uppercase mt-4 mb-2 px-2">MANAJEMEN</p>
                
                <a href="{{ route('admin.arsip.index') }}" class="sidebar-link {{ Request::routeIs('admin.arsip.index') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Arsip Digital</span>
                </a>
                
                <a href="{{ route('admin.arsip.favorit') }}" class="sidebar-link {{ Request::routeIs('admin.arsip.favorit') ? 'active' : '' }}">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span>Arsip Favorit</span>
                </a>
                
                <a href="{{ route('admin.disposisi.index') }}" class="sidebar-link {{ Request::routeIs('admin.disposisi.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Disposisi</span>
                </a>
                
                <a href="{{ route('admin.aset.index') }}" class="sidebar-link {{ Request::routeIs('admin.aset.*') && !Request::routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>Manajemen Aset</span>
                </a>

                <a href="{{ route('admin.denah-aset.index') }}" class="sidebar-link {{ Request::routeIs('admin.denah-aset.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    <span>Denah Lokasi Aset</span>
                </a>

                <a href="{{ route('admin.retensi.index') }}" class="sidebar-link {{ Request::routeIs('admin.retensi.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Jadwal Retensi Arsip</span>
                </a>
                
                {{-- ============================================= --}}
                {{-- MENU PEMINJAMAN ASET - BARU --}}
                {{-- ============================================= --}}
                @if(Auth::user()->role === 'admin')
                    {{-- ADMIN: Menu Peminjaman Aset dengan Badge --}}
                    <a href="{{ route('admin.peminjaman.index') }}" 
                       class="sidebar-link {{ Request::routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <span>Peminjaman Aset</span>
                        
                        @php
                            $pendingCount = \App\Models\AssetBorrow::where('status', 'pending')->count();
                        @endphp
                        
                        @if($pendingCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $pendingCount }}
                        </span>
                        @endif
                    </a>

                @elseif(Auth::user()->role === 'staff')
                    {{-- STAFF: Menu Peminjaman Saya --}}
                    <a href="{{ route('staff.peminjaman.index') }}" 
                       class="sidebar-link {{ Request::routeIs('staff.peminjaman.index') || Request::routeIs('staff.peminjaman.show') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>Peminjaman Saya</span>
                    </a>
                    
                    {{-- STAFF: Menu Cari & Pinjam Aset --}}
                    <a href="{{ route('staff.peminjaman.browse') }}" 
                       class="sidebar-link {{ Request::routeIs('staff.peminjaman.browse') || Request::routeIs('staff.peminjaman.create') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Cari & Pinjam Aset</span>
                    </a>
                @endif
                {{-- ============================================= --}}
                {{-- END MENU PEMINJAMAN --}}
                {{-- ============================================= --}}

                {{-- ============================================= --}}
                {{-- MENU MUTASI ASET --}}
                {{-- ============================================= --}}
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.mutasi.index') }}"
                       class="sidebar-link {{ Request::routeIs('admin.mutasi.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        <span>Mutasi Aset</span>
                        @php $pendingMutasi = \App\Models\AssetMutation::where('status', 'menunggu')->count(); @endphp
                        @if($pendingMutasi > 0)
                        <span class="ml-auto bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $pendingMutasi }}
                        </span>
                        @endif
                    </a>
                @elseif(Auth::user()->role === 'staff')
                    <a href="{{ route('staff.mutasi.index') }}"
                       class="sidebar-link {{ Request::routeIs('staff.mutasi.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        <span>Mutasi Aset</span>
                    </a>
                @elseif(Auth::user()->role === 'pimpinan')
                    <a href="{{ route('pimpinan.mutasi.index') }}"
                       class="sidebar-link {{ Request::routeIs('pimpinan.mutasi.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        <span>Mutasi Aset</span>
                    </a>
                @endif
                {{-- ============================================= --}}
                {{-- END MENU MUTASI ASET --}}
                {{-- ============================================= --}}

                {{-- Pemusnahan Aset --}}
                <a href="{{ route('admin.pemusnahan.index') }}"
                   class="sidebar-link {{ Request::routeIs('admin.pemusnahan.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Pemusnahan Aset</span>
                    @php $pendingPemusnahan = \App\Models\AssetDestruction::where('status', 'menunggu')->count(); @endphp
                    @if($pendingPemusnahan > 0)
                    <span class="ml-auto bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        {{ $pendingPemusnahan }}
                    </span>
                    @endif
                </a>

                {{-- Galeri Dashboard --}}
                <a href="{{ route('admin.galeri.index') }}"
                   class="sidebar-link {{ Request::routeIs('admin.galeri.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Galeri Dashboard</span>
                </a>
                
                <a href="{{ route('admin.user.index') }}" class="sidebar-link {{ Request::routeIs('admin.user.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Manajemen User</span>
                </a>
                
                <a href="{{ route('admin.laporan.index') }}" class="sidebar-link {{ Request::routeIs('admin.laporan.*') && !Request::routeIs('admin.laporan.pengajuan.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Laporan</span>
                </a>

                {{-- Pengajuan TTE --}}
                <a href="{{ route('admin.laporan.pengajuan.index') }}" class="sidebar-link {{ Request::routeIs('admin.laporan.pengajuan.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Pengajuan TTE</span>
                </a>
                
                <a href="{{ route('admin.pengaturan.index') }}" class="sidebar-link {{ Request::routeIs('admin.pengaturan.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.82 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.82 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.82-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Pengaturan</span>
                </a>
            </nav>
            
            <div class="mt-6 pt-4 border-t">
                <p class="text-xs text-gray-400 px-2">Diskominfo.Batola.2025</p>
            </div>
        </div>
    </aside>
    
    <div id="main-content">
        <header class="top-header">
            <div class="flex items-center gap-2 md:gap-4">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <div class="flex items-center gap-2 md:gap-3">
                <div class="relative">
                    <button onclick="toggleNotification()" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        
                        @php
                            $unreadCount = Auth::user()->unreadNotifications()->count();
                        @endphp
                        @if($unreadCount > 0)
                        <span class="absolute top-0 right-0 w-4 h-4 md:w-5 md:h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                        @endif
                    </button>

                    <div id="notificationDropdown" class="dropdown-menu absolute right-0 mt-2 w-80 md:w-80 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden z-50">
                        <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-purple-50">
                            <div class="flex items-center justify-between">
                                <h3 class="font-bold text-gray-800">Notifikasi</h3>
                                @if($unreadCount > 0)
                                <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded-full">{{ $unreadCount }} Baru</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="max-h-96 overflow-y-auto">
                            @php
                                $recentNotifications = Auth::user()->notifications()
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp
                            
                            @forelse($recentNotifications as $notif)
                            <a href="{{ $notif->url ? route(Auth::user()->role . '.notifikasi.read', $notif->id) : route(Auth::user()->role . '.notifikasi.index') }}" 
                               class="flex items-start p-4 hover:bg-gray-50 border-b border-gray-100 transition-colors {{ $notif->isRead() ? '' : 'bg-blue-50' }}">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-{{ $notif->icon_class['color'] }}-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-{{ $notif->icon_class['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $notif->icon_class['icon'] }}"/>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">
                                        {{ $notif->title }}
                                        @if(!$notif->isRead())
                                        <span class="inline-block w-1.5 h-1.5 bg-blue-600 rounded-full ml-1"></span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ Str::limit($notif->message, 50) }}</p>
                                    <p class="text-xs text-blue-600 mt-1">{{ $notif->time_ago }}</p>
                                </div>
                            </a>
                            @empty
                            <div class="p-8 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-sm text-gray-500 mt-2">Tidak ada notifikasi</p>
                            </div>
                            @endforelse
                        </div>
                        
                        <div class="p-3 bg-gray-50 text-center border-t">
                            <a href="{{ route(Auth::user()->role . '.notifikasi.index') }}" 
                               class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                Lihat Semua Notifikasi
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <button onclick="toggleProfile()" class="flex items-center gap-2 md:gap-3 p-2 rounded-lg hover:bg-gray-100 transition-all">
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full shadow-md overflow-hidden">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs md:text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <svg class="w-4 h-4 text-gray-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="profileDropdown" class="dropdown-menu absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden z-50">
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-full shadow-lg overflow-hidden">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                             alt="{{ Auth::user()->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <a href="{{ route(Auth::user()->role . '.profil') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Profil Saya</span>
                            </a>
                            @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.pengaturan.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Pengaturan</span>
                            </a>
                            @endif
                        </div>
                        <div class="p-2 border-t border-gray-200">
                            <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                                @csrf
                                <button type="button" onclick="confirmLogout()" class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-red-50 transition-colors group">
                                    <svg class="w-5 h-5 text-gray-600 group-hover:text-red-600 mr-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-red-600 transition-colors">Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <main class="p-6">
            @yield('content')
        </main>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @include('partials.sweetalert')
    
    {{-- Toggle Sidebar, Notification, Profile Scripts --}}
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('hidden');
                mainContent.classList.toggle('full');
            }
        }
        
        function toggleNotification() {
            const dropdown = document.getElementById('notificationDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            
            if (profileDropdown.classList.contains('show')) {
                profileDropdown.classList.remove('show');
            }
            
            dropdown.classList.toggle('show');
        }

        function toggleProfile() {
            const dropdown = document.getElementById('profileDropdown');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            if (notificationDropdown.classList.contains('show')) {
                notificationDropdown.classList.remove('show');
            }
            
            dropdown.classList.toggle('show');
        }

        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            const menuToggle = event.target.closest('.menu-toggle');
            
            const notificationBtn = event.target.closest('button[onclick="toggleNotification()"]');
            if (!notificationBtn && !notificationDropdown.contains(event.target)) {
                notificationDropdown.classList.remove('show');
            }
            
            const profileBtn = event.target.closest('button[onclick="toggleProfile()"]');
            if (!profileBtn && !profileDropdown.contains(event.target)) {
                profileDropdown.classList.remove('show');
            }
            
            if (!sidebar.contains(event.target) && !menuToggle && window.innerWidth <= 768) {
                sidebar.classList.remove('show');
                document.getElementById('sidebar-overlay').classList.remove('active');
            }
        });
        
        document.getElementById('notificationDropdown').addEventListener('click', function(event) {
            event.stopPropagation();
        });
        
        document.getElementById('profileDropdown').addEventListener('click', function(event) {
            event.stopPropagation();
        });
    </script>
    
    @stack('scripts')
</body>
</html>