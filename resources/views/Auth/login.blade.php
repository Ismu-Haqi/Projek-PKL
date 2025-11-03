<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - GANDARIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        
        /* ========================================
           RESPONSIVE STYLES
           ======================================== */
        
        /* TABLET (768px - 1024px) */
        @media (min-width: 768px) and (max-width: 1024px) {
            .logo-container {
                width: 200px;
                height: 200px;
            }
            
            h1.system-title {
                font-size: 2.5rem;
            }
            
            .description-box {
                font-size: 0.875rem;
                padding: 1rem;
            }
        }
        
        /* MOBILE (max-width: 768px) */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            /* Logo Circle - Smaller on Mobile */
            .logo-container {
                width: 180px;
                height: 180px;
                margin-bottom: 1.5rem;
            }
            
            .logo-container img {
                padding: 1.5rem;
            }
            
            /* System Title - Smaller */
            h1.system-title {
                font-size: 2rem;
                margin-bottom: 0.5rem;
            }
            
            .subtitle {
                font-size: 0.875rem;
                margin-bottom: 1rem;
            }
            
            /* Description Box - Compact */
            .description-box {
                font-size: 0.75rem;
                padding: 0.875rem;
                margin-top: 1rem;
            }
            
            /* Login Card - Full Width on Mobile */
            .login-card {
                border-radius: 1.5rem;
                padding: 1.5rem;
            }
            
            /* Partner Logos - Smaller */
            .partner-logo {
                width: 40px;
                height: 40px;
            }
            
            .partner-logo img {
                width: 1.75rem;
                height: 1.75rem;
            }
            
            /* Login Title */
            h2.login-title {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }
            
            /* Form Labels - Smaller */
            label {
                font-size: 0.813rem;
                margin-bottom: 0.375rem;
            }
            
            /* Input Fields - Adjust Padding */
            .input-field {
                padding: 0.75rem;
                font-size: 0.875rem;
            }
            
            /* Select Dropdown */
            select.input-field {
                padding: 0.75rem;
            }
            
            /* Captcha Canvas - Responsive */
            #captchaCanvas {
                width: 100px;
                height: 35px;
            }
            
            .captcha-container {
                padding: 0.75rem;
            }
            
            .captcha-refresh-btn {
                width: 1.25rem;
                height: 1.25rem;
            }
            
            /* Submit Button */
            .btn-login {
                padding: 0.875rem;
                font-size: 0.875rem;
            }
            
            /* Badge - Smaller */
            .badge-container {
                margin-top: 1rem;
            }
            
            .badge-icon {
                width: 2rem;
                height: 2rem;
            }
            
            .badge-text {
                font-size: 0.75rem;
            }
            
            /* Footer */
            footer {
                padding: 1rem;
                margin-top: 1.5rem;
            }
            
            footer p {
                font-size: 0.75rem;
            }
            
            footer p.text-xs {
                font-size: 0.625rem;
            }
        }
        
        /* SMALL MOBILE (max-width: 480px) */
        @media (max-width: 480px) {
            .container {
                padding: 0.75rem;
            }
            
            /* Logo - Even Smaller */
            .logo-container {
                width: 150px;
                height: 150px;
                margin-bottom: 1rem;
            }
            
            /* System Title */
            h1.system-title {
                font-size: 1.75rem;
            }
            
            .subtitle {
                font-size: 0.75rem;
            }
            
            /* Description Box - Very Compact */
            .description-box {
                font-size: 0.688rem;
                padding: 0.75rem;
                line-height: 1.4;
            }
            
            /* Login Card */
            .login-card {
                padding: 1.25rem;
            }
            
            /* Partner Logos - Smallest */
            .partner-logo {
                width: 35px;
                height: 35px;
            }
            
            .partner-logo img {
                width: 1.5rem;
                height: 1.5rem;
            }
            
            /* Login Title */
            h2.login-title {
                font-size: 1.25rem;
            }
            
            /* Input Fields - Compact */
            .input-field {
                padding: 0.625rem;
                font-size: 0.813rem;
            }
            
            /* Captcha - Smallest */
            #captchaCanvas {
                width: 90px;
                height: 30px;
            }
            
            /* Submit Button */
            .btn-login {
                padding: 0.75rem;
                font-size: 0.813rem;
            }
            
            /* Badge - Hide on Very Small Screens */
            .badge-container {
                transform: scale(0.85);
            }
            
            /* Footer */
            footer {
                padding: 0.75rem;
            }
        }
        
        /* LANDSCAPE MODE - Mobile */
        @media (max-height: 600px) and (orientation: landscape) {
            .logo-container {
                width: 120px;
                height: 120px;
                margin-bottom: 0.5rem;
            }
            
            h1.system-title {
                font-size: 1.5rem;
            }
            
            .subtitle {
                font-size: 0.75rem;
            }
            
            .description-box {
                display: none;
            }
            
            .login-card {
                padding: 1rem;
            }
            
            .partner-logos-container {
                margin-bottom: 0.5rem;
            }
            
            h2.login-title {
                font-size: 1.25rem;
                margin-bottom: 0.5rem;
            }
            
            .form-spacing {
                margin-top: 0.75rem;
            }
            
            .badge-container {
                display: none;
            }
            
            footer {
                padding: 0.5rem;
                margin-top: 0.5rem;
            }
        }
        
        /* Touch-friendly Buttons */
        @media (hover: none) {
            .input-field,
            .btn-login,
            button,
            a {
                min-height: 44px;
            }
            
            .partner-logo {
                min-height: 44px;
                min-width: 44px;
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
                <p class="subtitle text-base md:text-xl text-white font-normal mb-4 md:mb-6 px-4">Sistem Informasi Kearsipan Dinamis Terintegrasi</p>
                
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
                    <p class="text-center text-xs text-gray-500 mb-2 md:mb-3">Tim Koordinasi Diskominfo Batola</p>
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
                                <span class="ml-2 text-xs text-gray-600">Lihat Kata Sandi</span>
                            </label>
                            <a href="#" class="text-xs text-sky-600 hover:text-sky-700">Lupa Kata Sandi?</a>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Login Sebagai</label>
                        <select name="role" 
                                required
                                class="input-field w-full px-3 md:px-4 py-2.5 md:py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:outline-none bg-gray-50 appearance-none cursor-pointer text-sm md:text-base">
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Administrator</option>
                            <option value="staff">Staff</option>
                        </select>
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
                        MASUK
                    </button>
                </form>

                <!-- Balai Sertifikasi Badge -->
                <div class="badge-container mt-4 md:mt-6 flex justify-center">
                    <div class="flex items-center gap-2 text-sky-600">
                        <svg class="badge-icon w-8 h-8 md:w-10 md:h-10" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="badge-text text-left text-xs md:text-sm">
                            <div class="font-bold">Balai Besar</div>
                            <div class="font-bold">Sertifikasi</div>
                            <div class="font-bold">Elektronik</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-white py-4 md:py-6 mt-6 md:mt-8">
        <p class="text-xs md:text-sm px-4">Temukan Aplikasi GANDARIA di <strong>Google Play Store</strong> dan <strong>App Store</strong></p>
        <p class="text-xs mt-2">Copyright © 2025 Arsip Nasional Republik Indonesia</p>
    </footer>

    <script>
        // Captcha Variables
        let captchaCode = '';
        
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
        
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!validateCaptcha()) {
                e.preventDefault();
                alert('Kode captcha salah! Silakan coba lagi.');
                return false;
            }
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
        
        window.onload = function() {
            generateCaptcha();
        };
    </script>
</body>
</html>