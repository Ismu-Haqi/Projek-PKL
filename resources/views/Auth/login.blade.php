<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - GANDARIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
        .role-option[value="admin"]::before { background: #3b82f6; }
        .role-option[value="staff"]::before { background: #10b981; }
        .role-option[value="pimpinan"]::before { background: #8b5cf6; }
        
        @media (max-width: 768px) {
            .logo-container { width: 180px !important; height: 180px !important; }
            h1.system-title { font-size: 2rem !important; }
            .subtitle { font-size: 0.875rem !important; }
            .description-box { font-size: 0.75rem !important; padding: 0.875rem !important; }
            .partner-logo { width: 40px !important; height: 40px !important; }
        }

        /* ── Login: Mobile khusus layar HP ── */
        @media (max-width: 640px) {
            /* Sembunyikan panel kiri (logo/deskripsi), fokus ke form login */
            .grid.md\:grid-cols-2 { grid-template-columns: 1fr !important; }
            .grid.md\:grid-cols-2 > div:first-child { display: none !important; }

            /* Form card full width */
            .container { padding: 1rem !important; }
            .container > .grid > div:last-child { padding: 0 !important; }

            /* Card form lebih lega */
            .bg-white.rounded-3xl { border-radius: 1.25rem !important; padding: 1.5rem !important; }

            /* Input font-size 16px cegah zoom di iOS/Android */
            input[type="text"], input[type="password"], input[type="email"],
            input[type="number"], select, textarea {
                font-size: 16px !important;
            }

            /* Logo kecil di atas form */
            .bg-white.rounded-3xl::before {
                content: '';
                display: block;
                text-align: center;
                margin-bottom: 1rem;
            }

            /* Captcha canvas full width */
            #captchaCanvas { width: 100% !important; max-width: 160px; }

            /* Tombol masuk full width */
            button[type="submit"] { width: 100% !important; padding: 0.875rem !important; font-size: 1rem !important; }

            /* Kurangi padding body */
            body.gradient-bg { padding: 0.5rem !important; }
            .py-8 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    
    <div class="container mx-auto px-4 py-8 md:py-12 max-w-6xl">
        <div class="grid md:grid-cols-2 gap-6 md:gap-8 items-center">
            
            <div class="text-center">
                <div class="mb-6 md:mb-8 flex justify-center">
                    <div class="logo-container w-64 h-64 md:w-80 md:h-80 bg-white rounded-full flex items-center justify-center logo-shadow p-6 md:p-8">
                        <div class="text-center w-full">
                            <img src="{{ asset('images/gandaria.png') }}" alt="Logo GANDARIA" class="w-full h-auto mx-auto object-contain">
                        </div>
                    </div>
                </div>
                
                <h1 class="system-title text-3xl md:text-5xl font-bold text-white mb-2 md:mb-3 tracking-wide">GANDARIA</h1>
                <p class="subtitle text-base md:text-xl text-white font-normal mb-4 md:mb-6 px-4">Pengelolaan arsip dan data aset, terstruktur, informatif, dan akuntabel</p>
                
                <div class="description-box bg-white/10 backdrop-blur-sm rounded-2xl p-4 md:p-6 mt-3 md:mt-4 text-white mx-4 md:mx-0">
                    <p class="text-xs md:text-sm leading-relaxed text-center">
                        <strong>GANDARIA</strong> adalah aplikasi <strong>pertama kantor diskominfo</strong> yang ditetapkan 
                        berdasarkan Keputusan Kepala Diskominfo Kabupaten Barito Kuala untuk 
                        aplikasi pengelolaan arsip dan data aset terstruktur, informatif, dan akuntabel.
                    </p>
                </div>
            </div>

            <div class="login-card bg-white rounded-3xl card-shadow p-6 md:p-8">
                
                <div class="partner-logos-container mb-4 md:mb-6">
                    <p class="text-center text-xs text-gray-500 mb-2 md:mb-3"> GANDARIA</p>
                    <div class="flex justify-center gap-2 md:gap-3 flex-wrap">
                        <div class="partner-logo"><img src="{{ asset('images/logo-selidah.png') }}" class="w-8 h-8 md:w-10 md:h-10 object-contain"></div>
                        <div class="partner-logo"><img src="{{ asset('images/logo-kibar.png') }}" class="w-8 h-8 md:w-10 md:h-10 object-contain"></div>
                        <div class="partner-logo"><img src="{{ asset('images/gandaria.png') }}" class="w-8 h-8 md:w-10 md:h-10 object-contain"></div>
                    </div>
                </div>

                <h2 class="login-title text-2xl md:text-3xl font-bold text-gray-800 text-center mb-4">LOGIN AKUN</h2>

                @if($errors->any() || session('login_error') || session('password_error') || session('warning') || session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            @if($errors->has('captcha'))
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Captcha Salah',
                                    text: @json($errors->first('captcha')),
                                    confirmButtonColor: '#0284c7',
                                    confirmButtonText: 'Coba Lagi'
                                });
                            @elseif($errors->has('email'))
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Username Belum Diisi',
                                    text: @json($errors->first('email')),
                                    confirmButtonColor: '#0284c7',
                                    confirmButtonText: 'Oke'
                                });
                            @elseif($errors->has('password'))
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Password Belum Diisi',
                                    text: @json($errors->first('password')),
                                    confirmButtonColor: '#0284c7',
                                    confirmButtonText: 'Oke'
                                });
                            @elseif($errors->any())
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Data Belum Lengkap',
                                    text: @json($errors->first()),
                                    confirmButtonColor: '#0284c7',
                                    confirmButtonText: 'Oke'
                                });
                            @elseif(session('login_error'))
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Username Tidak Ditemukan',
                                    text: @json(session('login_error')),
                                    confirmButtonColor: '#0284c7',
                                    confirmButtonText: 'Coba Lagi'
                                });
                            @elseif(session('password_error'))
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Password Salah',
                                    text: @json(session('password_error')),
                                    confirmButtonColor: '#0284c7',
                                    confirmButtonText: 'Coba Lagi'
                                });
                            @elseif(session('warning'))
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Perhatian',
                                    text: @json(session('warning')),
                                    confirmButtonColor: '#0284c7',
                                    confirmButtonText: 'Mengerti'
                                });
                            @elseif(session('success'))
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: @json(session('success')),
                                    confirmButtonColor: '#0284c7',
                                    confirmButtonText: 'Oke'
                                });
                            @endif
                        });
                    </script>
                @endif
                
                
                <form method="POST" action="{{ route('login') }}" class="form-spacing space-y-4 md:space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pengguna</label>
                        <input type="text" name="email" value="{{ old('email') }}" placeholder="Nama Pengguna" required
                               class="input-field w-full px-3 md:px-4 py-2.5 md:py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:outline-none bg-gray-50 text-sm md:text-base">
                        <a href="#" class="text-xs text-sky-600 hover:text-sky-700 mt-1 inline-block">Lupa Nama Pengguna?</a>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Kata Sandi" required
                                   class="input-field w-full px-3 md:px-4 py-2.5 md:py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:outline-none bg-gray-50 pr-10 md:pr-12 text-sm md:text-base">
                            <button type="button" onclick="togglePassword()" class="absolute right-3 md:right-4 top-1/2 transform -translate-y-1/2">
                                <svg id="eyeIcon" class="w-4 h-4 md:w-5 md:h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-between mt-2 flex-wrap gap-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="w-3.5 h-3.5 md:w-4 md:h-4 text-sky-600 border-gray-300 rounded focus:ring-sky-500">
                                <span class="ml-2 text-xs text-gray-600">Ingat Saya</span>
                            </label>
                            <a href="#" class="text-xs text-sky-600 hover:text-sky-700">Lupa Kata Sandi?</a>
                        </div>
                    </div>



                    <div>
                        <div class="captcha-container bg-gray-100 border-2 border-gray-200 rounded-xl p-3 md:p-4 flex items-center justify-between">
                            <canvas id="captchaCanvas" width="120" height="40" class="bg-white rounded"></canvas>
                            <button type="button" onclick="generateCaptcha()" class="text-sky-600 hover:text-sky-700 ml-2 md:ml-3 p-1" title="Refresh Captcha">
                                <svg class="captcha-refresh-btn w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </button>
                        </div>
                        <input type="text" id="captchaInput" name="captcha" placeholder="Masukkan kode captcha di atas" required autocomplete="off"
                               class="input-field w-full px-3 md:px-4 py-2.5 md:py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:outline-none bg-gray-50 mt-2 text-sm md:text-base">
                        <input type="hidden" id="captchaCode" name="captcha_code">
                        <span id="captchaError" class="text-red-500 text-xs mt-1 hidden">Kode captcha salah!</span>
                    </div>

                    <button type="submit" id="submitBtn" class="btn-login w-full py-3 md:py-4 text-white font-semibold rounded-xl text-sm md:text-base">
                        <i class="fas fa-sign-in-alt mr-2"></i> MASUK
                    </button>
                </form>
            </div>
        </div>
    </div>

    <footer class="text-center text-white py-4 md:py-6 mt-6 md:mt-8">
        <p class="text-xs mt-2">Ismu Haqi © 2025 Diskominfo Kabupaten Barito Kuala</p>
    </footer>

    <script>
        let captchaCode = '';
        
        function generateCaptcha() {
            const canvas = document.getElementById('captchaCanvas');
            const ctx = canvas.getContext('2d');
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
        
        function validateCaptcha() {
            const userInput = document.getElementById('captchaInput').value;
            const originalCode = document.getElementById('captchaCode').value;
            if (userInput === originalCode) return true;
            
            document.getElementById('captchaError').classList.remove('hidden');
            generateCaptcha();
            return false;
        }
        
        // Validasi captcha sisi klien sebelum submit (SweetAlert)
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.querySelector('input[name="email"]').value.trim();
            const password = document.querySelector('input[name="password"]').value;
const captcha = document.getElementById('captchaInput').value.trim();
            
            if (!validateCaptcha()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Captcha Salah',
                    text: 'Kode captcha yang Anda masukkan salah. Silakan coba lagi.',
                    confirmButtonColor: '#0284c7',
                    confirmButtonText: 'Coba Lagi'
                });
                return false;
            }
            
            // Ubah text tombol menjadi loading
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> MEMVERIFIKASI...';
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            
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
        
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() { generateCaptcha(); }, 250);
        });
        

        window.onload = function() { generateCaptcha(); };
    </script>
</body>
</html>