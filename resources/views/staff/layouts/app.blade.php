<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GANDARIA | @yield('title', 'Staff Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .sidebar {
            width: 280px;
            background-color: white;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .main-content {
            margin-left: 280px;
            width: calc(100% - 280px);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }
        .main-content.expanded {
            margin-left: 80px;
            width: calc(100% - 80px);
        }
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
            transform: rotate(180deg);
        }
        .sidebar-link {
            color: #4b5563;
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            border-radius: 0.5rem;
        }
        .sidebar-link:hover {
            background-color: #eff6ff;
            color: #2563eb;
        }
        .sidebar-link.active {
            background-color: #2563eb;
            color: white !important;
            border-left-color: #2563eb;
        }
        .sidebar-link.active svg {
            color: white;
        }
        .sidebar-menu {
            height: calc(100vh - 120px);
            overflow-y: auto;
            padding-right: 8px;
        }
        .sidebar.collapsed .sidebar-link span,
        .sidebar.collapsed .sidebar-title,
        .sidebar.collapsed .sidebar-footer {
            display: none;
        }
        .sidebar.collapsed .sidebar-link {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        .stat-card {
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    
    <!-- Sidebar -->
    @include('staff.layouts.sidebar')
    
    <!-- Main Content -->
    <div id="main-content" class="main-content">
        
        <!-- Header -->
        @include('staff.layouts.header')
        
        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-6 px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-600">&copy; {{ date('Y') }} GANDARIA - Diskominfo Kabupaten Barito Kuala</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">Bantuan</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">Kontak</a>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleButton = document.getElementById('sidebar-toggle');
            
            if (toggleButton && sidebar && mainContent) {
                toggleButton.addEventListener('click', () => {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                });
            }
            
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed && sidebar && mainContent) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
        });
        
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            toast.className = `fixed top-24 right-6 ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center space-x-3`;
            toast.innerHTML = `
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    ${type === 'success' ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>' : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>'}
                </svg>
                <span class="font-medium">${message}</span>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>