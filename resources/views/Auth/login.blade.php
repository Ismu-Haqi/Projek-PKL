<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - GANDARIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #0369a1 100%);
        }
        
        .logo-shadow {
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.15));
        }
        
        .card-shadow {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        .partner-logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .partner-logo:hover {
            transform: translateY(-5px);
        }
        
        .input-field {
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
        }
        
        .btn-login {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.3);
        }
        
        /* ✅ NEW: Role option styling */
        .role-option {
            position: relative;
            padding-left: 2rem;
        }
        
        .role-option::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        
        .role-option[value="admin"]::before {
            background: #3b82f6;
        }
        
        .role-option[value="staff"]::before {
            background: #10b981;
        }
        
        .role-option[value="pimpinan"]::before {
            background: #8b5cf6;
        }
        
        /* RESPONSIVE STYLES */
        @media (max-width: 768px) {
            .logo-container {
                width: 180px !important;
                height: 180px !important;
            }
            
            h1.system-title {
                font-size: 2rem !important;
            }
            
            .subtitle {
                font-size: 0.875rem !important;
            }
            
            .description-box {
                font-size: 0.75rem !important;
                padding: 0.875rem !important;
            }
            
            .partner-logo {
                width: 40px !important;
                height: 40px !important;
            }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    
    <div class="container mx-auto px-4 py-8 md:py-12 max-w-6xl">
        <div class="grid md:grid-cols-2 gap-6 md:gap-8 items-center">
            
            <!-- Left Side - Branding -->
            <div class="text-center">
                <!-- Logo GANDARIA -->
                <div class="mb-6 md:mb-8 flex justify-center">
                    <div class="logo-container w-64 h-64 md:w-80 md:h-80 bg-white rounded-full flex items-center justify-center logo-shadow p-6 md:p-8">
                        <div class="text-center w-full">
                            <img src="{{ asset('images/gandaria.png') }}" 
                                 alt="Logo GANDARIA" 
                                 class="w-full h-auto mx-auto object-contain">
                        </div>
                    </div>
                </div>
                
                <!-- System Name -->
                <h1 class="system-title text-3xl md:text-5xl font-bold text-white mb-2 md:mb-3 tracking-wide">GANDARIA</h1>
                <p class="subtitle text-base md:text-xl text-white font-normal mb-4 md:mb-6 px-4">Aplikasi arsip dan Aset Dinamis Terintegrasi</p>
                
                <!-- Description -->
                <div class="description-box bg-white/10 backdrop-blur-sm rounded-2xl p-4 md:p-6 mt-3 md:mt-4 text-white mx-4 md:mx-0">
                    <p class="text-xs md:text-sm leading-relaxed text-center">
                        <strong>GANDARIA</strong> adalah aplikasi <strong>PERTAMA</strong> yang ditetapkan 
                        berdasarkan Keputusan Kepala Diskominfo Kabupaten Barito Kuala untuk 
                        Sistem Pengelolaan Arsip dan Data Aset Terpadu, Terstruktur, Informatif, dan Akuntabel.
                    </p>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="login-card bg-white rounded-3xl card-shadow p-6 md:p-8">
                
                <!-- Partner Logos -->
                <div class="partner-logos-container mb-4 md:mb-6">
                    <p class="text-center text-xs text-gray-500 mb-2 md:mb-3"> GANDARIA</p>
                    <div class="flex justify-center gap-2 md:gap-3 flex-wrap">
                        <!-- Logo Kabupaten Barito Kuala -->
                        <div class="partner-logo">
                            <img src="{{ asset('images/logo-selidah.png') }}" 
                                 alt="Kabupaten Barito Kuala" 
                                 class="w-8 h-8 md:w-10 md:h-10 object-contain"
                                 title="Kabupaten Barito Kuala">
                        </div>
                        <!-- Logo Diskominfo Batola -->
                        <div class="partner-logo">
                            <img src="{{ asset('images/logo-kibar.png') }}" 
                                 alt="Diskominfo Batola" 
                                 class="w-8 h-8 md:w-10 md:h-10 object-contain"
                                 title="Diskominfo Barito Kuala">
                        </div>
                        <!-- Logo GANDARIA -->
                        <div class="partner-logo">
                            <img src="{{ asset('images/gandaria.png') }}" 
                                 alt="GANDARIA" 
                                 class="w-8 h-8 md:w-10 md:h-10 object-contain"
                                 title="GANDARIA">
                        </div>
                    </div>
                </div>

                <!-- Login Title -->
                <h2 class="login-title text-2xl md:text-3xl font-bold text-gray-800 text-center mb-2">LOGIN AKUN</h2>
                
                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="form-spacing space-y-4 md:space-y-5 mt-4 md:mt-6">
                    @csrf
                    
                    <!-- Username/Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pengguna</label>
                        <input type="text" 
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Nama Pengguna"
                               required
                               class="input-field w-full px-3 md:px-4 py-2.5 md:py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:outline-none bg-gray-50 text-sm md:text-base">
                        <a href="#" class="text-xs text-sky-600 hover:text-sky-700 mt-1 inline-block">Lupa Nama Pengguna?</a>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi</label>
                        <div class="relative">
                            <input type="password" 
                                   id="password"
                                   name="password"
                                   placeholder="Kata Sandi"
                                   required
                                   class="input-field w-full px-3 md:px-4 py-2.5 md:py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:outline-none bg-gray-50 pr-10 md:pr-12 text-sm md:text-base">
                            <button type="button" 
                                    onclick="togglePassword()"
                                    class="absolute right-3 md:right-4 top-1/2 transform -translate-y-1/2">
                                <svg id="eyeIcon" class="w-4 h-4 md:w-5 md:h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-between mt-2 flex-wrap gap-2">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="remember"
                                       class="w-3.5 h-3.5 md:w-4 md:h-4 text-sky-600 border-gray-300 rounded focus:ring-sky-500">
                                <span class="ml-2 text-xs text-gray-600">Ingat Saya</span>
                            </label>
                            <a href="#" class="text-xs text-sky-600 hover:text-sky-700">Lupa Kata Sandi?</a>
                        </div>
                    </div>

                    <!-- ✅ UPDATED: Role Selection with Pimpinan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Login Sebagai</label>
                        <select name="role" 
                                required
                                class="input-field w-full px-3 md:px-4 py-2.5 md:py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:outline-none bg-gray-50 appearance-none cursor-pointer text-sm md:text-base">
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" class="role-option" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                👨‍💼 Administrator
                            </option>
                            <option value="staff" class="role-option" {{ old('role') == 'staff' ? 'selected' : '' }}>
                                👤 Staff
                            </option>
                            <option value="pimpinan" class="role-option" {{ old('role') == 'pimpinan' ? 'selected' : '' }}>
                                👔 Pimpinan
                            </option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pilih sesuai dengan akses akun Anda
                        </p>
                    </div>

                    <!-- Captcha -->
                    <div>
                        <div class="captcha-container bg-gray-100 border-2 border-gray-200 rounded-xl p-3 md:p-4 flex items-center justify-between">
                            <canvas id="captchaCanvas" width="120" height="40" class="bg-white rounded"></canvas>
                            <button type="button" onclick="generateCaptcha()" class="text-sky-600 hover:text-sky-700 ml-2 md:ml-3 p-1" title="Refresh Captcha">
                                <svg class="captcha-refresh-btn w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </button>
                        </div>
                        <input type="text" 
                               id="captchaInput"
                               name="captcha"
                               placeholder="Masukkan kode captcha di atas"
                               required
                               autocomplete="off"
                               class="input-field w-full px-3 md:px-4 py-2.5 md:py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:outline-none bg-gray-50 mt-2 text-sm md:text-base">
                        <input type="hidden" id="captchaCode" name="captcha_code">
                        <span id="captchaError" class="text-red-500 text-xs mt-1 hidden">Kode captcha salah!</span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="submitBtn"
                            class="btn-login w-full py-3 md:py-4 text-white font-semibold rounded-xl text-sm md:text-base">
                        <i class="fas fa-sign-in-alt mr-2"></i> MASUK
                    </button>
                </form>

                <!-- Info Badge -->
                <div class="badge-container mt-4 md:mt-6 text-center">
                    {{-- <div class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-50 to-purple-50 px-4 py-2 rounded-xl border border-blue-200">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs text-gray-700">3 Level Akses: Admin, Staff & Pimpinan</span>
                    </div> --}}
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-white py-4 md:py-6 mt-6 md:mt-8">
        <p class="text-xs mt-2">Ismu Haqi © 2025 Diskominfo Kabupaten Barito Kuala</p>
    </footer>

    <script>
        // Captcha Variables
        let captchaCode = '';
        
        // ============================================
        // SWEETALERT ERROR NOTIFICATIONS
        // ============================================
        
        // Check for Laravel validation errors
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: '❌ Validasi Gagal',
                html: `
                    <div class="text-left">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-sm text-gray-700">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                `,
                confirmButtonColor: '#dc2626',
                confirmButtonText: '<i class="fas fa-check mr-2"></i>Mengerti',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3'
                }
            });
        @endif
        
        // Check for login error (username/email not found)
        @if(session('login_error'))
            Swal.fire({
                icon: 'error',
                title: '👤 User Tidak Ditemukan',
                html: `
                    <div class="text-left">
                        <p class="text-gray-700 mb-3">{{ session('login_error') }}</p>
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Tips:</strong> Pastikan Anda memasukkan username/email yang benar dan memilih role yang sesuai.
                            </p>
                        </div>
                    </div>
                `,
                confirmButtonColor: '#dc2626',
                confirmButtonText: '<i class="fas fa-redo mr-2"></i>Coba Lagi',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3'
                }
            });
        @endif
        
        // Check for password error
        @if(session('password_error'))
            Swal.fire({
                icon: 'error',
                title: '🔐 Password Salah',
                html: `
                    <div class="text-left">
                        <p class="text-gray-700 mb-3">{{ session('password_error') }}</p>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-lightbulb mr-2"></i>
                                <strong>Lupa Password?</strong> Hubungi administrator untuk reset password Anda.
                            </p>
                        </div>
                    </div>
                `,
                confirmButtonColor: '#dc2626',
                confirmButtonText: '<i class="fas fa-key mr-2"></i>Coba Lagi',
                footer: '<a href="#" class="text-sky-600 hover:text-sky-700 text-sm"><i class="fas fa-question-circle mr-1"></i> Butuh Bantuan?</a>',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3'
                }
            });
        @endif
        
        // Check for account inactive warning
        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: '⚠️ Akun Tidak Aktif',
                html: `
                    <div class="text-left">
                        <p class="text-gray-700 mb-3">{{ session('warning') }}</p>
                        <div class="bg-orange-50 border-l-4 border-orange-500 p-3 rounded">
                            <p class="text-sm text-orange-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Silakan hubungi administrator sistem untuk aktivasi akun.
                            </p>
                        </div>
                    </div>
                `,
                confirmButtonColor: '#f59e0b',
                confirmButtonText: '<i class="fas fa-phone mr-2"></i>Hubungi Admin',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3'
                }
            });
        @endif
        
        // Check for success message (logout success)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '✅ Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#10b981',
                confirmButtonText: '<i class="fas fa-check mr-2"></i>OK',
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3'
                }
            });
        @endif
        
        // Generate Random Captcha - RESPONSIVE
        function generateCaptcha() {
            const canvas = document.getElementById('captchaCanvas');
            const ctx = canvas.getContext('2d');
            
            // Adjust canvas size for mobile
            const isMobile = window.innerWidth <= 480;
            canvas.width = isMobile ? 90 : 120;
            canvas.height = isMobile ? 30 : 40;
            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
            captchaCode = '';
            for (let i = 0; i < 6; i++) {
                captchaCode += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            
            document.getElementById('captchaCode').value = captchaCode;
            
            ctx.fillStyle = '#f0f0f0';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            for (let i = 0; i < 3; i++) {
                ctx.strokeStyle = `rgba(${Math.random()*255},${Math.random()*255},${Math.random()*255},0.3)`;
                ctx.beginPath();
                ctx.moveTo(Math.random() * canvas.width, Math.random() * canvas.height);
                ctx.lineTo(Math.random() * canvas.width, Math.random() * canvas.height);
                ctx.stroke();
            }
            
            const fontSize = isMobile ? 18 : 24;
            ctx.font = `bold ${fontSize}px Arial`;
            ctx.textBaseline = 'middle';
            
            const charSpacing = isMobile ? 13 : 17;
            const startX = isMobile ? 10 : 15;
            const centerY = canvas.height / 2;
            
            for (let i = 0; i < captchaCode.length; i++) {
                const x = startX + (i * charSpacing);
                const y = centerY + (Math.random() * 10 - 5);
                const angle = (Math.random() * 30 - 15) * Math.PI / 180;
                
                ctx.save();
                ctx.translate(x, y);
                ctx.rotate(angle);
                ctx.fillStyle = `rgb(${Math.random()*100},${Math.random()*100},${Math.random()*100})`;
                ctx.fillText(captchaCode[i], 0, 0);
                ctx.restore();
            }
            
            for (let i = 0; i < 30; i++) {
                ctx.fillStyle = `rgba(${Math.random()*255},${Math.random()*255},${Math.random()*255},0.5)`;
                ctx.fillRect(Math.random() * canvas.width, Math.random() * canvas.height, 2, 2);
            }
            
            document.getElementById('captchaError').classList.add('hidden');
            document.getElementById('captchaInput').value = '';
        }
        
        // Validate Captcha with SweetAlert
        function validateCaptcha() {
            const userInput = document.getElementById('captchaInput').value;
            const originalCode = document.getElementById('captchaCode').value;
            
            if (userInput === originalCode) {
                return true;
            } else {
                document.getElementById('captchaError').classList.remove('hidden');
                generateCaptcha();
                return false;
            }
        }
        
        // Form Submit Handler with SweetAlert
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all fields
            const email = document.querySelector('input[name="email"]').value.trim();
            const password = document.querySelector('input[name="password"]').value;
            const role = document.querySelector('select[name="role"]').value;
            const captcha = document.getElementById('captchaInput').value.trim();
            
            // Check empty fields
            if (!email) {
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ Nama Pengguna Kosong',
                    text: 'Silakan masukkan nama pengguna atau email Anda!',
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: '<i class="fas fa-edit mr-2"></i>Isi Sekarang',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3'
                    }
                });
                return false;
            }
            
            if (!password) {
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ Password Kosong',
                    text: 'Silakan masukkan password Anda!',
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: '<i class="fas fa-key mr-2"></i>Isi Sekarang',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3'
                    }
                });
                return false;
            }
            
            if (!role) {
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ Role Belum Dipilih',
                    text: 'Silakan pilih role login Anda (Administrator, Staff, atau Pimpinan)!',
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: '<i class="fas fa-user-check mr-2"></i>Pilih Role',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3'
                    }
                });
                return false;
            }
            
            if (!captcha) {
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ Captcha Kosong',
                    text: 'Silakan masukkan kode captcha yang terlihat!',
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: '<i class="fas fa-shield-alt mr-2"></i>Isi Captcha',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3'
                    }
                });
                return false;
            }
            
            // Validate captcha
            if (!validateCaptcha()) {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Captcha Salah!',
                    html: `
                        <div class="text-left">
                            <p class="text-gray-700 mb-3">Kode captcha yang Anda masukkan tidak sesuai.</p>
                            <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
                                <p class="text-sm text-red-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Kode captcha baru telah dibuat. Silakan masukkan kode yang baru.
                                </p>
                            </div>
                        </div>
                    `,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: '<i class="fas fa-redo mr-2"></i>Coba Lagi',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3'
                    }
                });
                return false;
            }
            
            // Show loading with role-specific message
            const roleNames = {
                'admin': 'Administrator',
                'staff': 'Staff',
                'pimpinan': 'Pimpinan'
            };
            const roleName = roleNames[role] || 'User';
            
            Swal.fire({
                title: '🔐 Memverifikasi...',
                html: `
                    <div class="text-center py-4">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-4 border-sky-600 mb-4"></div>
                        <p class="text-gray-600">Login sebagai <strong>${roleName}</strong></p>
                        <p class="text-sm text-gray-500 mt-2">Mohon tunggu sebentar</p>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
            
            // Submit form
            this.submit();
            return true;
        });
        
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
        
        // Regenerate captcha on window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                generateCaptcha();
            }, 250);
        });
        
        // ✅ NEW: Role selection change handler with visual feedback
        document.querySelector('select[name="role"]').addEventListener('change', function() {
            const selectedRole = this.value;
            const roleMessages = {
                'admin': '👨‍💼 Akses penuh ke semua fitur sistem',
                'staff': '👤 Akses untuk mengelola arsip & disposisi',
                'pimpinan': '👔 Akses monitoring & laporan eksekutif'
            };
            
            if (selectedRole && roleMessages[selectedRole]) {
                // Show brief info about selected role
                const infoElement = this.nextElementSibling;
                if (infoElement && infoElement.classList.contains('text-xs')) {
                    infoElement.innerHTML = `<i class="fas fa-info-circle mr-1"></i>${roleMessages[selectedRole]}`;
                    infoElement.style.color = selectedRole === 'admin' ? '#3b82f6' : 
                                             selectedRole === 'staff' ? '#10b981' : '#8b5cf6';
                }
            }
        });
        
        window.onload = function() {
            generateCaptcha();
        };
    </script>
</body>
</html>