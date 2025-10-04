@php
    // Ambil data user yang sedang login
    $userName = Auth::user()->name ?? 'Admin';
    $userRole = Auth::user()->role ?? 'Admin';
@endphp

<header class="bg-white shadow-md flex justify-between items-center px-6 py-3">
    
    {{-- Search Bar --}}
    <div class="relative w-1/3">
        <input type="text" placeholder="🔍 Cari arsip, surat, atau dokumen..." 
               class="w-full py-2 pl-4 pr-4 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100 text-sm">
    </div>
    
    {{-- Notifikasi, Avatar, dan Logout --}}
    <div class="flex items-center space-x-4">
        
        {{-- Tombol Notifikasi --}}
        <a href="#" class="text-gray-500 hover:text-gray-700 relative">
            <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        </a>
        
        {{-- Profil Pengguna --}}
        <div class="flex items-center space-x-2 border rounded-full py-1 pl-3 pr-1 bg-gray-50">
            <span class="text-xs text-gray-700 font-medium">{{ $userRole }}</span>
            <img class="h-8 w-8 rounded-full" 
                 src="https://ui-avatars.com/api/?name={{ $userName }}&background=3b82f6&color=fff&size=32" 
                 alt="User Avatar">
        </div>
        
        {{-- Tombol Logout --}}
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="flex items-center text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-gray-100 transition duration-150"
           title="Logout">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        </a>
        
        {{-- Form tersembunyi untuk memicu POST request logout --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</header>