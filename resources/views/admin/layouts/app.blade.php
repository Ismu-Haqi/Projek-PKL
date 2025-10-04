<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GANDARIA | @yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* CSS UTAMA UNTUK LAYOUT, ANIMASI, DAN TOGGLE */
        
        /* 1. SIDEBAR STRUCTURE */
        .sidebar {
            width: 280px; 
            background-color: white;
            transition: width 0.3s ease-in-out; 
            overflow: hidden; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }
        .sidebar.collapsed {
            width: 80px; /* Lebar saat disembunyikan */
        }
        
        /* 2. MAIN CONTENT AREA */
        .main-content {
            margin-left: 280px;
            width: calc(100% - 280px);
            /* Memastikan konten melebar saat sidebar menyusut */
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }
        .main-content.expanded {
            margin-left: 80px; /* Konten menempel ke sidebar 80px */
            width: calc(100% - 80px); /* Konten melebar */
        }
        
        /* 3. TOMBOL TOGGLE */
        .sidebar-toggle-btn {
            background-color: #2563eb; 
            color: white;
            border: 2px solid white; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            z-index: 99; 
            transition: transform 0.3s ease-in-out;
            position: absolute; 
            top: 20px; 
            right: 15px; 
        }
        .sidebar.collapsed .sidebar-toggle-btn {
            transform: rotate(180deg); /* Putar ikon saat collapsed */
            right: 15px; 
        }
        
        /* 4. STYLE MENU LINK */
        .sidebar-link {
            color: #4b5563;
            border-left: 4px solid transparent;
            transition: background-color 0.1s ease-in-out, color 0.1s ease-in-out;
            white-space: nowrap; 
            overflow: hidden;
            padding: 0.75rem; 
            display: flex;
            align-items: center;
            border-radius: 0.5rem;
        }
        .sidebar-link:hover { background-color: #eff6ff; color: #2563eb; }
        .sidebar-link.active {
            background-color: #2563eb; 
            color: white !important;
            border-left-color: #2563eb;
        }
        .sidebar-link.active svg { color: white; }
        
        /* 5. SCROLL DAN TEXT HIDE */
        .sidebar-menu {
            height: calc(100vh - 120px);
            overflow-y: auto; 
            padding-right: 8px;
        }
        .sidebar.collapsed .sidebar-link span, .sidebar.collapsed .sidebar-title, .sidebar.collapsed .sidebar-footer {
            display: none;
        }
        .sidebar.collapsed .sidebar-link {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen">
    
    {{-- 1. SIDEBAR (Component) --}}
    @include('admin.layouts.sidebar')
    
    <div id="main-content" class="main-content flex-grow">
        
        {{-- 2. HEADER/NAVBAR (Component) --}}
        @include('admin.layouts.header') 

        {{-- 3. CONTENT AREA --}}
        <main>
            {{-- Konten dari dashboard.blade.php atau index.blade.php akan masuk di sini --}}
            @yield('content')
        </main>
        
    </div>

    {{-- MODAL DAN SCRIPT JAVASCRIPT --}}
    @include('admin.arsip.upload-modal') 
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    
    <script>
        // === SCRIPT TOGGLE SIDEBAR ===
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const toggleButton = document.getElementById('sidebar-toggle');
        
        // PENTING: Panggil fungsi toggle saat tombol diklik
        if (toggleButton) {
             toggleButton.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
             });
        }
        
        // Atur margin awal main content (inisialisasi)
        if (mainContent) {
            mainContent.style.marginLeft = '280px'; 
            mainContent.style.width = 'calc(100% - 280px)';
        }
    </script>
    
    @stack('scripts')
</body>
</html>