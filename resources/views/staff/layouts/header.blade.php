@php
    $userName = Auth::user()->name ?? 'Staff';
    $userRole = ucfirst(Auth::user()->role ?? 'Staff');
@endphp

<header class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4">
        
        <!-- Left: Toggle + Search -->
        <div class="flex items-center space-x-4 flex-1 max-w-3xl">
            <!-- Sidebar Toggle Button -->
            <button id="sidebar-toggle" class="p-2 rounded-lg hover:bg-gray-100 transition-all text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            
            <!-- Search Bar -->
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                    class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                    placeholder="Cari arsip, surat, atau dokumen...">
            </div>
        </div>

        <!-- Right: Notifications + Profile -->
        <div class="flex items-center space-x-3 ml-6">
            <!-- Notification -->
            <button class="relative p-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">3</span>
            </button>

            <!-- Profile -->
            <div class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-100 transition-all cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-md">
                    {{ strtoupper(substr($userName, 0, 2)) }}
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-semibold text-gray-800">{{ $userName }}</p>
                    <p class="text-xs text-gray-500">{{ $userRole }}</p>
                </div>
            </div>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="p-2.5 rounded-xl text-red-500 hover:bg-red-50 transition-all" title="Logout">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</header>