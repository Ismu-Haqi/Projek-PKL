@extends('admin.layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
{{-- Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Pengaturan Sistem</h1>
    <p class="text-gray-600 mt-1">Kelola pengaturan aplikasi dan profil Anda</p>
</div>

{{-- Alert Messages --}}
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

{{-- Tabs Navigation --}}
<div class="bg-white rounded-lg shadow-sm">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px" aria-label="Tabs">
            <button onclick="switchTab('profil')" 
                    id="tab-profil" 
                    class="tab-button active border-b-2 border-blue-500 py-4 px-6 text-center font-medium text-blue-600 focus:outline-none">
                <i class="fas fa-user mr-2"></i>
                Profil
            </button>
            
            @if(Auth::user()->role === 'admin')
            <button onclick="switchTab('sistem')" 
                    id="tab-sistem" 
                    class="tab-button border-b-2 border-transparent py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none">
                <i class="fas fa-cog mr-2"></i>
                Sistem
            </button>
            
            <button onclick="switchTab('backup')" 
                    id="tab-backup" 
                    class="tab-button border-b-2 border-transparent py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none">
                <i class="fas fa-database mr-2"></i>
                Backup
            </button>
            @endif
        </nav>
    </div>

    {{-- Tab Contents --}}
    <div class="p-6">
        {{-- Tab Profil --}}
        <div id="content-profil" class="tab-content">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Profil Pengguna</h2>
            
            <form action="{{ route('admin.pengaturan.update-profil') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', Auth::user()->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', Auth::user()->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Ubah Password</h3>
                    <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end pt-4">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        @if(Auth::user()->role === 'admin')
        {{-- Tab Sistem --}}
        <div id="content-sistem" class="tab-content hidden">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pengaturan Sistem</h2>
            
            <form action="{{ route('admin.pengaturan.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Aplikasi</label>
                        <input type="text" 
                               name="app_name" 
                               id="app_name" 
                               value="{{ old('app_name', $settings['app_name'] ?? 'SIPPB') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                        <select name="timezone" 
                                id="timezone" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>WIB - Jakarta</option>
                            <option value="Asia/Makassar" {{ ($settings['timezone'] ?? '') == 'Asia/Makassar' ? 'selected' : '' }}>WITA - Makassar</option>
                            <option value="Asia/Jayapura" {{ ($settings['timezone'] ?? '') == 'Asia/Jayapura' ? 'selected' : '' }}>WIT - Jayapura</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="items_per_page" class="block text-sm font-medium text-gray-700 mb-1">Items Per Halaman</label>
                        <input type="number" 
                               name="items_per_page" 
                               id="items_per_page" 
                               value="{{ old('items_per_page', $settings['items_per_page'] ?? 10) }}"
                               min="5"
                               max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="date_format" class="block text-sm font-medium text-gray-700 mb-1">Format Tanggal</label>
                        <select name="date_format" 
                                id="date_format" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="d/m/Y" {{ ($settings['date_format'] ?? 'd/m/Y') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                            <option value="Y-m-d" {{ ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                        </select>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Fitur Sistem</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="enable_registration" 
                                   id="enable_registration" 
                                   value="1"
                                   {{ ($settings['enable_registration'] ?? 0) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="enable_registration" class="ml-2 text-sm text-gray-700">
                                Aktifkan Registrasi Pengguna Baru
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="enable_notifications" 
                                   id="enable_notifications" 
                                   value="1"
                                   {{ ($settings['enable_notifications'] ?? 1) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="enable_notifications" class="ml-2 text-sm text-gray-700">
                                Aktifkan Notifikasi Sistem
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="maintenance_mode" 
                                   id="maintenance_mode" 
                                   value="1"
                                   {{ ($settings['maintenance_mode'] ?? 0) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="maintenance_mode" class="ml-2 text-sm text-gray-700">
                                Mode Maintenance (Nonaktifkan akses sementara)
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-6">
                    <button type="button" 
                            onclick="clearCache()"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Bersihkan Cache
                    </button>
                    
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        {{-- Tab Backup --}}
        <div id="content-backup" class="tab-content hidden">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Backup Database</h2>
            
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <h3 class="font-medium text-blue-800 mb-1">Informasi Backup</h3>
                        <p class="text-sm text-blue-700">
                            Backup database akan disimpan di folder <code class="bg-blue-100 px-2 py-1 rounded">storage/app/backups</code>.
                            Pastikan folder memiliki permission yang sesuai.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex gap-3">
                    <button type="button" 
                            onclick="createBackup()"
                            class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Buat Backup Sekarang
                    </button>
                    
                    <button type="button" 
                            onclick="loadBackupList()"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Daftar Backup
                    </button>
                </div>
                
                <div id="backup-list" class="mt-6 hidden">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">Daftar Backup</h3>
                    <div id="backup-list-content" class="bg-gray-50 rounded-md p-4">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                            <p>Memuat daftar backup...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
// Tab Switching Function
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    const selectedContent = document.getElementById(`content-${tabName}`);
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
    
    // Add active class to selected tab
    const selectedTab = document.getElementById(`tab-${tabName}`);
    if (selectedTab) {
        selectedTab.classList.add('active', 'border-blue-500', 'text-blue-600');
        selectedTab.classList.remove('border-transparent', 'text-gray-500');
    }
}

// Clear Cache Function
function clearCache() {
    if (confirm('Bersihkan semua cache sistem?\n\nIni akan membersihkan:\n- Cache konfigurasi\n- Cache view\n- Cache route\n- Cache aplikasi')) {
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Membersihkan...';
        
        fetch('{{ route("admin.pengaturan.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
            } else {
                alert('❌ ' + (data.message || 'Gagal membersihkan cache'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat membersihkan cache');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        });
    }
}

// Create Backup Function
function createBackup() {
    if (confirm('Buat backup database sekarang?\n\nProses ini mungkin memakan waktu beberapa saat tergantung ukuran database.')) {
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Membuat Backup...';
        
        fetch('{{ route("admin.pengaturan.backup") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message + '\n\nFile: ' + data.filename);
                // Reload backup list if visible
                const backupList = document.getElementById('backup-list');
                if (backupList && !backupList.classList.contains('hidden')) {
                    loadBackupList();
                }
            } else {
                alert('❌ ' + (data.message || 'Gagal membuat backup'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat membuat backup');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        });
    }
}

// Load Backup List Function
function loadBackupList() {
    const backupListDiv = document.getElementById('backup-list');
    const backupListContent = document.getElementById('backup-list-content');
    
    backupListDiv.classList.remove('hidden');
    backupListContent.innerHTML = `
        <div class="text-center text-gray-500">
            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
            <p>Memuat daftar backup...</p>
        </div>
    `;
    
    fetch('{{ route("admin.pengaturan.backup-list") }}', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.backups.length > 0) {
            let html = '<div class="space-y-2">';
            data.backups.forEach(backup => {
                html += `
                    <div class="flex items-center justify-between p-3 bg-white rounded border border-gray-200 hover:border-blue-300 transition">
                        <div class="flex items-center">
                            <i class="fas fa-file-archive text-blue-500 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-800">${backup.name}</p>
                                <p class="text-sm text-gray-500">${backup.size} - ${backup.date}</p>
                            </div>
                        </div>
                        <a href="/admin/pengaturan/backup/download/${backup.name}" 
                           class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                `;
            });
            html += '</div>';
            backupListContent.innerHTML = html;
        } else {
            backupListContent.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-folder-open text-4xl mb-3"></i>
                    <p class="text-lg font-medium">Belum ada backup</p>
                    <p class="text-sm">Buat backup pertama Anda sekarang</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        backupListContent.innerHTML = `
            <div class="text-center text-red-500 py-8">
                <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                <p>Gagal memuat daftar backup</p>
            </div>
        `;
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Show first tab by default
    switchTab('profil');
    
    // Auto-hide success/error messages after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('[role="alert"]').forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endpush