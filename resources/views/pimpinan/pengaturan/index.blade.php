@extends('pimpinan.layouts.app')

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
            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">
                <i class="fas fa-user-tie mr-1"></i>
                Pimpinan
            </span>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- Sidebar Profile Card --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
            {{-- Profile Header --}}
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-center">
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
                <p class="text-purple-100 text-sm">{{ Auth::user()->email }}</p>
                <span class="inline-block mt-2 px-3 py-1 bg-purple-400 bg-opacity-30 text-white rounded-full text-xs font-medium">
                    Pimpinan
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
                    <i class="fas fa-user-circle text-purple-500 mr-3"></i>
                    Informasi Profil
                </h2>
                <p class="text-sm text-gray-600 mt-1">Update informasi profil dan foto Anda</p>
            </div>

            <form action="{{ route('pimpinan.pengaturan.update-profil') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
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
                               class="absolute bottom-0 right-0 bg-purple-500 hover:bg-purple-600 text-white rounded-full p-3 cursor-pointer shadow-lg transition-all duration-200 transform hover:scale-110">
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
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
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
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                               placeholder="email@example.com"
                               required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ✅ TAMBAHAN BARU (Poin 5 revisi) - Nomor WhatsApp --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-whatsapp text-gray-400 mr-1"></i>
                            Nomor WhatsApp
                        </label>
                        <input type="text" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone', Auth::user()->phone) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                               placeholder="08xxxxxxxxxx">
                        <p class="text-xs text-gray-400 mt-1">Dipakai sistem untuk mengirim notifikasi WhatsApp saat ada dokumen yang butuh TTE Anda.</p>
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" 
                            class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2.5 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
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

            <form action="{{ route('pimpinan.pengaturan.update-profil') }}" method="POST" class="p-6">
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
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all pr-12"
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
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all pr-12"
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
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all pr-12"
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
// ========================================
// ✅ PREVIEW AVATAR
// ========================================
function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran file maksimal 2MB',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Mengerti'
            });
            event.target.value = '';
            return;
        }

        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format File Tidak Valid',
                text: 'Gunakan format JPG, PNG atau GIF',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Mengerti'
            });
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

            // Show success toast
            Swal.fire({
                icon: 'success',
                title: 'Preview berhasil',
                text: 'Klik "Simpan Perubahan" untuk mengupload foto',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                backdrop: false
            });
        };
        reader.readAsDataURL(file);
    }
}

// ========================================
// ✅ REMOVE AVATAR WITH SWEETALERT
// ========================================
function removeAvatar() {
    Swal.fire({
        title: 'Hapus Foto Profil',
        html: `
            <div class="text-left">
                <p class="text-gray-700 mb-3">Foto profil Anda akan dihapus secara permanen</p>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-info-circle mr-2"></i>Anda dapat mengunggah foto baru kapan saja
                    </p>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Ya, Hapus',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true,
        allowOutsideClick: true,
        allowEscapeKey: true,
        customClass: {
            popup: 'animated-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Sedang Memproses',
                html: `
                    <div class="loading-smooth-container">
                        <div class="spinner-wrapper">
                            <i class="fas fa-spinner fa-pulse"></i>
                        </div>
                        <p class="loading-text">Menghapus foto profil...</p>
                        <div class="loading-progress">
                            <div class="loading-progress-bar"></div>
                        </div>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                customClass: {
                    popup: 'loading-popup-smooth'
                },
                didOpen: () => {
                    const progressBar = document.querySelector('.loading-progress-bar');
                    if (progressBar) {
                        setTimeout(() => {
                            progressBar.style.width = '100%';
                        }, 100);
                    }
                }
            });
            
            // Send delete request
            fetch('{{ route("pimpinan.profil.avatar.remove") }}', {
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Foto profil berhasil dihapus',
                        confirmButtonColor: '#10b981',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'animated-popup'
                        }
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Gagal menghapus foto profil',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'Tutup'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Terjadi kesalahan saat menghapus foto',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Tutup'
                });
            });
        }
    });
}

// ========================================
// TOGGLE PASSWORD VISIBILITY
// ========================================
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

// ========================================
// CHECK PASSWORD STRENGTH
// ========================================
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
</script>

<style>
/* Loading Smooth Container */
.loading-smooth-container {
    text-align: center;
    padding: 30px 20px;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.spinner-wrapper {
    position: relative;
    width: 80px;
    height: 80px;
    margin-bottom: 30px;
}

.spinner-wrapper::before {
    content: '';
    position: absolute;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid #e0e7ff;
    animation: pulse-ring 1.5s ease-out infinite;
}

.spinner-wrapper i {
    font-size: 48px !important;
    color: #9333ea !important;
    animation: spinPulse 1.2s ease-in-out infinite !important;
    display: block;
    position: relative;
    z-index: 1;
}

@keyframes pulse-ring {
    0% {
        transform: scale(0.8);
        opacity: 1;
    }
    100% {
        transform: scale(1.4);
        opacity: 0;
    }
}

@keyframes spinPulse {
    0% {
        transform: rotate(0deg) scale(1);
    }
    50% {
        transform: rotate(180deg) scale(1.15);
    }
    100% {
        transform: rotate(360deg) scale(1);
    }
}

.loading-text {
    color: #64748b !important;
    font-size: 16px !important;
    font-weight: 500 !important;
    margin: 0 0 20px 0 !important;
    animation: fadeInOut 1.5s ease-in-out infinite;
}

@keyframes fadeInOut {
    0%, 100% {
        opacity: 0.6;
    }
    50% {
        opacity: 1;
    }
}

.loading-progress {
    width: 100%;
    max-width: 300px;
    height: 4px;
    background: #e0e7ff;
    border-radius: 2px;
    overflow: hidden;
    position: relative;
}

.loading-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #9333ea, #c084fc, #9333ea);
    background-size: 200% 100%;
    border-radius: 2px;
    width: 0%;
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

.loading-popup-smooth {
    animation: loadingPopupIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
}

@keyframes loadingPopupIn {
    from {
        transform: scale(0.8) translateY(-20px);
        opacity: 0;
    }
    to {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
}
</style>
@endpush