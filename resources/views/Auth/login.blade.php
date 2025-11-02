<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body class="gradient-bg min-h-screen">
    
    <div class="container mx-auto px-4 py-12 max-w-6xl">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            
            <!-- Left Side - Branding -->
            <div class="text-center">
                <!-- Logo Placeholder -->
                <div class="mb-6 flex justify-center">
                    <div class="w-64 h-64 bg-white rounded-full flex items-center justify-center logo-shadow">
                        <div class="text-center">
                            <svg class="w-32 h-32 mx-auto text-sky-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <div class="text-gray-400 text-xs">Logo Diskominfo</div>
                        </div>
                    </div>
                </div>
                
                <!-- System Name -->
                <h1 class="text-5xl font-bold text-white mb-4">GANDARIA</h1>
                <p class="text-xl text-white font-medium mb-2">Sistem Informasi Kearsipan Dinamis Terintegrasi</p>
                
                <!-- Description -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 mt-6 text-white">
                    <p class="text-base leading-relaxed">
                        <strong>GANDARIA</strong> adalah aplikasi umum <strong>PERTAMA</strong> yang ditetapkan 
                        berdasarkan Keputusan Kepala Diskominfo Kabupaten Barito Kuala tentang 
                        Sistem Pengelolaan Arsip dan Data Aset Terpadu, Terstruktur, Informatif, dan Akuntabel.
                    </p>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="bg-white rounded-3xl card-shadow p-8">
                
                <!-- Partner Logos -->
                <div class="mb-6">
                    <p class="text-center text-xs text-gray-500 mb-3">Tim Koordinasi Diskominfo Batola</p>
                    <div class="flex justify-center gap-3 flex-wrap">
                        <!-- Logo placeholders - ganti dengan logo Anda -->
                        <div class="partner-logo">
                            <svg class="w-6 h-6 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 9a1 1 0 112 0v3a1 1 0 11-2 0V9zm1-4a1 1 0 011 1v.01a1 1 0 11-2 0V6a1 1 0 011-1z"/>
                            </svg>
                        </div>
                        <div class="partner-logo">
                            <svg class="w-6 h-6 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="partner-logo">
                            <svg class="w-6 h-6 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="partner-logo">
                            <svg class="w-6 h-6 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                        </div>
                        <div class="partner-logo">
                            <svg class="w-6 h-6 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Login Title -->
                <h2 class="text-3xl font-bold text-gray-800 text-center mb-2">LOGIN AKUN</h2>
                
                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5 mt-6">
                    @csrf
                    
                    <!-- Username/Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pengguna</label>
                        <input type="text" 
                               name="email"
                               placeholder="Nama Pengguna"
                               required
                               class="input-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:outline-none bg-gray-50">
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
                                   class="input-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:outline-none bg-gray-50 pr-12">
                            <button type="button" 
                                    onclick="togglePassword()"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2">
                                <svg id="eyeIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="remember"
                                       class="w-4 h-4 text-sky-600 border-gray-300 rounded focus:ring-sky-500">
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
                                class="input-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:outline-none bg-gray-50 appearance-none cursor-pointer">
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Administrator</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>

                    <!-- Captcha -->
                    <div>
                        <div class="bg-gray-100 border-2 border-gray-200 rounded-xl p-4 flex items-center justify-between">
                            <canvas id="captchaCanvas" width="120" height="40" class="bg-white rounded"></canvas>
                            <button type="button" onclick="generateCaptcha()" class="text-sky-600 hover:text-sky-700 ml-3" title="Refresh Captcha">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                               class="input-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:outline-none bg-gray-50 mt-2">
                        <input type="hidden" id="captchaCode" name="captcha_code">
                        <span id="captchaError" class="text-red-500 text-xs mt-1 hidden">Kode captcha salah!</span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="submitBtn"
                            class="btn-login w-full py-4 text-white font-semibold rounded-xl">
                        MASUK
                    </button>
                </form>

                <!-- Balai Sertifikasi Badge -->
                <div class="mt-6 flex justify-center">
                    <div class="flex items-center gap-2 text-sky-600">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-left">
                            <div class="font-bold text-base">Balai Besar</div>
                            <div class="font-bold text-base">Sertifikasi</div>
                            <div class="font-bold text-base">Elektronik</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-white py-6 mt-8">
        <p class="text-sm">Temukan Aplikasi GANDARIA di <strong>Google Play Store</strong> dan <strong>App Store</strong></p>
        <p class="text-xs mt-2">Copyright © 2025 Arsip Nasional Republik Indonesia</p>
    </footer>

    <script>
        // Captcha Variables
        let captchaCode = '';
        
        // Generate Random Captcha
        function generateCaptcha() {
            const canvas = document.getElementById('captchaCanvas');
            const ctx = canvas.getContext('2d');
            
            // Clear canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Generate random string
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
            captchaCode = '';
            for (let i = 0; i < 6; i++) {
                captchaCode += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            
            // Store in hidden field
            document.getElementById('captchaCode').value = captchaCode;
            
            // Draw background
            ctx.fillStyle = '#f0f0f0';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Add noise lines
            for (let i = 0; i < 3; i++) {
                ctx.strokeStyle = `rgba(${Math.random()*255},${Math.random()*255},${Math.random()*255},0.3)`;
                ctx.beginPath();
                ctx.moveTo(Math.random() * canvas.width, Math.random() * canvas.height);
                ctx.lineTo(Math.random() * canvas.width, Math.random() * canvas.height);
                ctx.stroke();
            }
            
            // Draw text with different styles
            ctx.font = 'bold 24px Arial';
            ctx.textBaseline = 'middle';
            
            for (let i = 0; i < captchaCode.length; i++) {
                const x = 15 + (i * 17);
                const y = 20 + (Math.random() * 10 - 5);
                const angle = (Math.random() * 30 - 15) * Math.PI / 180;
                
                ctx.save();
                ctx.translate(x, y);
                ctx.rotate(angle);
                ctx.fillStyle = `rgb(${Math.random()*100},${Math.random()*100},${Math.random()*100})`;
                ctx.fillText(captchaCode[i], 0, 0);
                ctx.restore();
            }
            
            // Add noise dots
            for (let i = 0; i < 30; i++) {
                ctx.fillStyle = `rgba(${Math.random()*255},${Math.random()*255},${Math.random()*255},0.5)`;
                ctx.fillRect(Math.random() * canvas.width, Math.random() * canvas.height, 2, 2);
            }
            
            // Clear error message
            document.getElementById('captchaError').classList.add('hidden');
            document.getElementById('captchaInput').value = '';
        }
        
        // Validate Captcha
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
        
        // Form Submit Handler
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!validateCaptcha()) {
                e.preventDefault();
                alert('Kode captcha salah! Silakan coba lagi.');
                return false;
            }
            
            // Jika captcha benar, form akan submit
            // Di sini Anda bisa menambahkan validasi tambahan
            return true;
        });
        
        // Toggle Password Function
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
        
        // Generate captcha on page load
        window.onload = function() {
            generateCaptcha();
        };
    </script>
</body>
</html>