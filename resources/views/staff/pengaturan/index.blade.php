@extends('staff.layouts.app')

@section('title', 'Pengaturan')

@section('content')
{{-- Header --}}
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pengaturan</h1>
            <p class="text-gray-600 mt-1">Kelola profil dan preferensi akun Anda</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                <i class="fas fa-user-tie mr-1"></i>
                Staff
            </span>
        </div>
    </div>
</div>

{{-- Alert Messages --}}
@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg animate-fade-in">
    <div class="flex items-center">
        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
        <div>
            <p class="font-medium text-green-800">Berhasil!</p>
            <p class="text-green-700 text-sm">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg animate-fade-in">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
        <div>
            <p class="font-medium text-red-800">Gagal!</p>
            <p class="text-red-700 text-sm">{{ session('error') }}</p>
        </div>
    </div>
</div>
@endif

@if($errors->any())
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
    <div class="flex items-start">
        <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3 mt-0.5"></i>
        <div class="flex-1">
            <p class="font-medium text-red-800 mb-2">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

{{-- Main Content --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- Sidebar Profile Card --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
            {{-- Profile Header --}}
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-center">
                <div class="relative inline-block">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                             alt="Avatar" 
                             class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover"
                             id="sidebar-avatar">
                    @else
                        <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-white flex items-center justify-center"
                             id="sidebar-avatar-placeholder">
                            <i class="fas fa-user text-4xl text-gray-400"></i>
                        </div>
                    @endif
                    <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
                </div>
                <h3 class="mt-4 text-xl font-bold text-white">{{ Auth::user()->name }}</h3>
                <p class="text-blue-100 text-sm">{{ Auth::user()->email }}</p>
                <span class="inline-block mt-2 px-3 py-1 bg-blue-400 bg-opacity-30 text-white rounded-full text-xs font-medium">
                    Staff
                </span>
            </div>

            {{-- Quick Info --}}
            <div class="p-6 space-y-4">
                <div class="flex items-center text-sm">
                    <i class="fas fa-calendar-alt text-gray-400 w-5"></i>
                    <span class="text-gray-600 ml-3">Bergabung: </span>
                    <span class="text-gray-800 ml-auto font-medium">
                        {{ Auth::user()->created_at->format('d M Y') }}
                    </span>
                </div>
                <div class="flex items-center text-sm">
                    <i class="fas fa-clock text-gray-400 w-5"></i>
                    <span class="text-gray-600 ml-3">Terakhir login: </span>
                    <span class="text-gray-800 ml-auto font-medium">
                        {{ Auth::user()->updated_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Settings Form --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Profile Information Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-user-circle text-blue-500 mr-3"></i>
                    Informasi Profil
                </h2>
                <p class="text-sm text-gray-600 mt-1">Update informasi profil dan foto Anda</p>
            </div>

            <form action="{{ route('staff.pengaturan.update-profil') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Avatar Upload Section --}}
                <div class="flex flex-col items-center space-y-4 pb-6 border-b border-gray-200">
                    <div class="relative group">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                 alt="Avatar" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow-lg"
                                 id="avatar-preview">
                        @else
                            <div class="w-32 h-32 rounded-full border-4 border-gray-200 shadow-lg bg-gray-100 flex items-center justify-center"
                                 id="avatar-preview-placeholder">
                                <i class="fas fa-user text-5xl text-gray-400"></i>
                            </div>
                        @endif
                        <label for="avatar-input" 
                               class="absolute bottom-0 right-0 bg-blue-500 hover:bg-blue-600 text-white rounded-full p-3 cursor-pointer shadow-lg transition-all duration-200 transform hover:scale-110">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" 
                               id="avatar-input" 
                               name="avatar" 
                               accept="image/*" 
                               class="hidden"
                               onchange="previewAvatar(event)">
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Klik ikon kamera untuk mengubah foto</p>
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG atau GIF (Maks. 2MB)</p>
                        @if(Auth::user()->avatar)
                            <button type="button" 
                                    onclick="removeAvatar()"
                                    class="mt-2 text-red-500 hover:text-red-700 text-sm font-medium">
                                <i class="fas fa-trash-alt mr-1"></i>
                                Hapus Foto
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Form Fields --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-gray-400 mr-1"></i>
                            Nama Lengkap
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', Auth::user()->name) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="Masukkan nama lengkap"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope text-gray-400 mr-1"></i>
                            Email
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', Auth::user()->email) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="email@example.com"
                               required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Security Settings Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-shield-alt text-green-500 mr-3"></i>
                    Keamanan
                </h2>
                <p class="text-sm text-gray-600 mt-1">Ubah password untuk menjaga keamanan akun</p>
            </div>

            <form action="{{ route('staff.pengaturan.update-profil') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-500 mt-0.5 mr-3"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium mb-1">Tips Password Aman:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Minimal 8 karakter</li>
                                <li>Kombinasi huruf besar, kecil, angka dan simbol</li>
                                <li>Jangan gunakan informasi pribadi</li>
                                <li>Berbeda dari password lain</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    {{-- Current Password --}}
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>
                            Password Saat Ini
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12"
                                   placeholder="Masukkan password saat ini">
                            <button type="button" 
                                    onclick="togglePassword('current_password')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="current_password-icon"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- New Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-key text-gray-400 mr-1"></i>
                            Password Baru
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12"
                                   placeholder="Masukkan password baru"
                                   onkeyup="checkPasswordStrength()">
                            <button type="button" 
                                    onclick="togglePassword('password')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        {{-- Password Strength Indicator --}}
                        <div id="password-strength" class="mt-2 hidden">
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div id="strength-bar" class="h-full transition-all duration-300"></div>
                                </div>
                                <span id="strength-text" class="text-xs font-medium"></span>
                            </div>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-check-circle text-gray-400 mr-1"></i>
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12"
                                   placeholder="Konfirmasi password baru">
                            <button type="button" 
                                    onclick="togglePassword('password_confirmation')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="password_confirmation-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="flex justify-end pt-6 border-t border-gray-200 mt-6">
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-600 text-white font-medium py-2.5 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
// Preview Avatar
function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            event.target.value = '';
            return;
        }

        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak valid! Gunakan JPG, PNG atau GIF');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            // Update main preview
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-preview-placeholder');
            
            if (preview) {
                preview.src = e.target.result;
            } else if (placeholder) {
                placeholder.outerHTML = `<img src="${e.target.result}" alt="Avatar Preview" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow-lg" id="avatar-preview">`;
            }

            // Update sidebar preview
            const sidebarAvatar = document.getElementById('sidebar-avatar');
            const sidebarPlaceholder = document.getElementById('sidebar-avatar-placeholder');
            
            if (sidebarAvatar) {
                sidebarAvatar.src = e.target.result;
            } else if (sidebarPlaceholder) {
                sidebarPlaceholder.outerHTML = `<img src="${e.target.result}" alt="Avatar" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover" id="sidebar-avatar">`;
            }
        };
        reader.readAsDataURL(file);
    }
}

// Remove Avatar
function removeAvatar() {
    if (confirm('Hapus foto profil?\n\nFoto akan dihapus secara permanen. Lanjutkan?')) {
        const btn = event.target;
        btn.disabled = true;
        
        fetch('{{ route("staff.profil.avatar.remove") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus foto: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus foto');
        });
    }
}

// Toggle Password Visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Check Password Strength
function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');
    const strengthContainer = document.getElementById('password-strength');
    
    if (password.length === 0) {
        strengthContainer.classList.add('hidden');
        return;
    }
    
    strengthContainer.classList.remove('hidden');
    
    let strength = 0;
    
    // Length check
    if (password.length >= 8) strength += 25;
    if (password.length >= 12) strength += 15;
    
    // Contains lowercase
    if (/[a-z]/.test(password)) strength += 15;
    
    // Contains uppercase
    if (/[A-Z]/.test(password)) strength += 15;
    
    // Contains numbers
    if (/\d/.test(password)) strength += 15;
    
    // Contains special characters
    if (/[^A-Za-z0-9]/.test(password)) strength += 15;
    
    // Update strength bar
    strengthBar.style.width = strength + '%';
    
    if (strength < 40) {
        strengthBar.className = 'h-full transition-all duration-300 bg-red-500';
        strengthText.textContent = 'Lemah';
        strengthText.className = 'text-xs font-medium text-red-500';
    } else if (strength < 70) {
        strengthBar.className = 'h-full transition-all duration-300 bg-yellow-500';
        strengthText.textContent = 'Sedang';
        strengthText.className = 'text-xs font-medium text-yellow-500';
    } else {
        strengthBar.className = 'h-full transition-all duration-300 bg-green-500';
        strengthText.textContent = 'Kuat';
        strengthText.className = 'text-xs font-medium text-green-500';
    }
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const alerts = document.querySelectorAll('[class*="animate-fade-in"]');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s, transform 0.5s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endpush