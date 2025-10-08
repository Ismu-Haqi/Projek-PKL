<!-- Fixed Header Component -->
<header class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4">
        
        <!-- Left Side: Toggle Button + Search Bar -->
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
                    class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Cari arsip, surat, atau dokumen...">
            </div>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center space-x-3 ml-6">
            
            <!-- Notification Button -->
            <div class="relative">
                <button onclick="toggleNotification()" class="relative p-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">3</span>
                </button>

                <!-- Notification Dropdown -->
                <div id="notificationDropdown" style="display: none;" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden z-50">
                    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-purple-50">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-gray-800">Notifikasi</h3>
                            <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded-full">3 Baru</span>
                        </div>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <a href="{{ route(Auth::user()->role . '.notifikasi.index') }}" class="flex items-start p-4 hover:bg-gray-50 border-b border-gray-100 transition-colors">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"/>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-800">Arsip baru diunggah</p>
                                <p class="text-xs text-gray-500 mt-1">Surat Edaran COVID-19 telah ditambahkan</p>
                                <p class="text-xs text-blue-600 mt-1">5 menit lalu</p>
                            </div>
                        </a>
                    </div>
                    <div class="p-3 bg-gray-50 text-center">
                        <a href="{{ route(Auth::user()->role . '.notifikasi.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua Notifikasi</a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button onclick="toggleProfile()" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-100 transition-all">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-md">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role ?? 'admin') }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Profile Dropdown Menu -->
                <div id="profileDropdown" style="display: none;" class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden z-50">
                    <div class="p-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-gray-600">{{ Auth::user()->email ?? 'admin@diskominfo.go.id' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-2">
                        <a href="{{ route(Auth::user()->role . '.pengaturan.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Profil Saya</span>
                        </a>
                        <a href="{{ route(Auth::user()->role . '.pengaturan.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Pengaturan</span>
                        </a>
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
    </div>
</header>

<script>
// Toggle Notification Dropdown
function toggleNotification() {
    const dropdown = document.getElementById('notificationDropdown');
    const profileDropdown = document.getElementById('profileDropdown');
    
    // Close profile dropdown
    if (profileDropdown.style.display === 'block') {
        profileDropdown.style.display = 'none';
    }
    
    // Toggle notification dropdown
    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
        dropdown.style.display = 'block';
    } else {
        dropdown.style.display = 'none';
    }
}

// Toggle Profile Dropdown
function toggleProfile() {
    const dropdown = document.getElementById('profileDropdown');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    // Close notification dropdown
    if (notificationDropdown.style.display === 'block') {
        notificationDropdown.style.display = 'none';
    }
    
    // Toggle profile dropdown
    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
        dropdown.style.display = 'block';
    } else {
        dropdown.style.display = 'none';
    }
}

// Confirm Logout
function confirmLogout() {
    if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) {
        document.getElementById('logoutForm').submit();
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const notificationDropdown = document.getElementById('notificationDropdown');
    const profileDropdown = document.getElementById('profileDropdown');
    
    // Check if click is outside notification dropdown
    const notificationBtn = event.target.closest('button[onclick="toggleNotification()"]');
    if (!notificationBtn && !notificationDropdown.contains(event.target)) {
        notificationDropdown.style.display = 'none';
    }
    
    // Check if click is outside profile dropdown
    const profileBtn = event.target.closest('button[onclick="toggleProfile()"]');
    if (!profileBtn && !profileDropdown.contains(event.target)) {
        profileDropdown.style.display = 'none';
    }
});
</script>