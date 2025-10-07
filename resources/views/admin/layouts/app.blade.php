<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GANDARIA - Arsip Digital')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        
        /* Sidebar */
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
        
        /* Main Content */
        #main-content {
            margin-left: 260px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
        
        #main-content.full {
            margin-left: 0;
        }
        
        /* Sidebar Link */
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin: 4px 8px;
            border-radius: 8px;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
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
            width: 20px;
            height: 20px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        /* Header */
        .top-header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 30;
        }
        
        /* Toggle Button */
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
        
        /* Scrollbar */
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
        
        /* Overlay untuk mobile */
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
        
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }
            
            #sidebar.show {
                transform: translateX(0);
            }
            
            #main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    
    <!-- Overlay untuk mobile -->
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <aside id="sidebar">
        <div class="p-4">
            <!-- Logo -->
            <div class="flex items-center mb-6 pb-4 border-b">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold mr-3">
                    G
                </div>
                <span class="text-xl font-bold text-gray-800">GANDARIA</span>
            </div>
            
            <!-- Menu -->
            <nav>
                <p class="text-xs text-gray-400 font-semibold uppercase mb-2 px-2">BERANDA</p>
                
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2 2v8a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
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
                
                <a href="{{ route('admin.notifikasi.index') }}" class="sidebar-link {{ Request::routeIs('admin.notifikasi.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span>Notifikasi</span>
                </a>
                
                <a href="{{ route('admin.aset.index') }}" class="sidebar-link {{ Request::routeIs('admin.aset.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>Manajemen Aset</span>
                </a>
                
                <a href="{{ route('admin.user.index') }}" class="sidebar-link {{ Request::routeIs('admin.user.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Manajemen User</span>
                </a>
                
                <a href="{{ route('admin.laporan.index') }}" class="sidebar-link {{ Request::routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Laporan</span>
                </a>
                
                <a href="{{ route('admin.pengaturan.index') }}" class="sidebar-link {{ Request::routeIs('admin.pengaturan.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.82 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.82 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.82-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.82-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Pengaturan</span>
                </a>
            </nav>
            
            <!-- Footer -->
            <div class="mt-6 pt-4 border-t">
                <p class="text-xs text-gray-400 px-2">Versi 1.0.0</p>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div id="main-content">
        <!-- Header -->
        <header class="top-header">
            <div class="flex items-center gap-4">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <div class="relative flex-1 max-w-md">
                    <input type="text" placeholder="Cari arsip, surat, atau dokumen..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Notifikasi -->
                <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                
                <!-- Profile -->
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Content -->
        <main class="p-6">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth <= 768) {
                // Mobile
                sidebar.classList.toggle('show');
                overlay.classList.toggle('active');
            } else {
                // Desktop
                sidebar.classList.toggle('hidden');
                mainContent.classList.toggle('full');
            }
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = event.target.closest('.menu-toggle');
            
            if (!sidebar.contains(event.target) && !menuToggle && window.innerWidth <= 768) {
                sidebar.classList.remove('show');
                document.getElementById('sidebar-overlay').classList.remove('active');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>