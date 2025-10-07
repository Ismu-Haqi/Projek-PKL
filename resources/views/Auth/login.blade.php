<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GANDARIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes particle {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
        }
        body {
            background: linear-gradient(-45deg, #0047AB, #00A8E8, #00B894, #0066CC);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            min-height: 100vh;
            position: relative;
            overflow-y: auto;
        }
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        .particle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: particle 15s linear infinite;
        }
        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; width: 8px; height: 8px; }
        .particle:nth-child(3) { left: 30%; animation-delay: 4s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 6s; width: 6px; height: 6px; }
        .particle:nth-child(5) { left: 50%; animation-delay: 8s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 10s; width: 8px; height: 8px; }
        .particle:nth-child(7) { left: 70%; animation-delay: 12s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 14s; width: 6px; height: 6px; }
        .particle:nth-child(9) { left: 90%; animation-delay: 16s; }
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.8s ease-out;
        }
        .logo-container {
            animation: float 3s ease-in-out infinite;
        }
        .input-group {
            position: relative;
            margin-bottom: 1.2rem;
        }
        .input-field {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid transparent;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            outline: none;
            background: white;
            border-color: #00A8E8;
            box-shadow: 0 0 0 4px rgba(0, 168, 232, 0.1);
            transform: translateY(-2px);
        }
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            transition: color 0.3s ease;
        }
        .input-field:focus ~ .input-icon {
            color: #00A8E8;
        }
        .floating-label {
            position: absolute;
            left: 48px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
            transition: all 0.3s ease;
            background: white;
            padding: 0 8px;
        }
        .input-field:focus ~ .floating-label,
        .input-field:not(:placeholder-shown) ~ .floating-label {
            top: -10px;
            font-size: 0.75rem;
            color: #00A8E8;
        }
        .btn-login {
            background: linear-gradient(135deg, #0047AB 0%, #00A8E8 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        .btn-login:hover::before {
            width: 400px;
            height: 400px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 71, 171, 0.4);
        }
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .spinner {
            animation: spin 1s linear infinite;
        }
        .demo-box {
            background: rgba(255, 243, 205, 0.95);
            backdrop-filter: blur(10px);
            border: 2px solid #fbbf24;
            animation: fadeInUp 1s ease-out 0.4s both;
        }
        .toggle-password {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .toggle-password:hover {
            color: #00A8E8;
            transform: scale(1.1);
        }
        select.input-field {
            appearance: none;
            cursor: pointer;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 py-8">
    
    <!-- Animated Particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        
        <!-- Logo Section -->
        <div class="text-center mb-6 logo-container">
            <div class="mx-auto w-20 h-20 bg-white rounded-2xl flex items-center justify-center mb-3 shadow-2xl p-2">
                <img src="{{ asset('images/Logo Gandaria.png') }}" 
                     alt="Logo GANDARIA" 
                     class="w-full h-full object-contain"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<svg class=\'w-12 h-12 text-blue-600\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/></svg>';">
            </div>
            <h1 class="text-3xl font-bold text-white mb-2 drop-shadow-lg">GANDARIA</h1>
            <p class="text-white/90 text-sm font-medium drop-shadow">Gandeng Arsip Digital Barito Kuala</p>
            <p class="text-white/80 text-xs mt-1 drop-shadow">Diskominfo Kabupaten Barito Kuala</p>
        </div>

        <!-- Login Card -->
        <div class="login-card rounded-3xl p-6 shadow-2xl">
            <h2 class="text-xl font-bold text-white mb-1 text-center">Selamat Datang</h2>
            <p class="text-white/80 text-sm mb-5 text-center">Masukkan kredensial Anda untuk melanjutkan</p>

            <!-- Error Alert -->
            @if ($errors->any())
            <div class="bg-red-500/90 backdrop-blur-sm text-white px-4 py-3 rounded-xl mb-5 flex items-start">
                <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                </svg>
                <span class="text-sm font-medium">{{ $errors->first() }}</span>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf

                <!-- Username Field -->
                <div class="input-group">
                    <input type="text" id="username" name="username" class="input-field" placeholder=" " value="{{ old('username') }}" required autofocus>
                    <label for="username" class="floating-label">Username</label>
                    <div class="input-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="input-group">
                    <input type="password" id="password" name="password" class="input-field" placeholder=" " required>
                    <label for="password" class="floating-label">Password</label>
                    <div class="input-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 toggle-password" onclick="togglePassword()">
                        <svg id="eyeIcon" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="input-group">
                    <select id="role" name="role" class="input-field" required>
                        <option value="" disabled selected></option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                    <label for="role" class="floating-label">Login Sebagai</label>
                    <div class="input-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" class="btn-login w-full text-white font-bold py-4 px-6 rounded-xl shadow-xl relative z-10">
                    <span id="btnText">Masuk ke Sistem</span>
                    <svg id="btnSpinner" class="hidden spinner inline w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>

                <!-- Forgot Password -->
                <div class="mt-5 text-center">
                    <a href="#" class="text-sm font-medium text-white/90 hover:text-white transition-colors">Lupa Password?</a>
                </div>
            </form>
        </div>

        <!-- Demo Credentials -->
        <div class="demo-box rounded-2xl p-4 mt-5 shadow-xl">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-700 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                </svg>
                <div class="flex-1 text-sm">
                    <p class="font-bold text-yellow-900 mb-2">Kredensial Demo:</p>
                    <p class="text-yellow-800 mb-1 cursor-pointer hover:text-yellow-900 transition-colors" onclick="fillDemo('admin')">
                        <span class="font-semibold">Admin:</span> admin / password123
                    </p>
                    <p class="text-yellow-800 cursor-pointer hover:text-yellow-900 transition-colors" onclick="fillDemo('staff')">
                        <span class="font-semibold">Staff:</span> staff1 / password123
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-white/60 text-xs mt-5">&copy; 2025 Diskominfo Kabupaten Barito Kuala</p>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }

        function fillDemo(type) {
            if (type === 'admin') {
                document.getElementById('username').value = 'admin';
                document.getElementById('password').value = 'password123';
                document.getElementById('role').value = 'admin';
            } else {
                document.getElementById('username').value = 'staff1';
                document.getElementById('password').value = 'password123';
                document.getElementById('role').value = 'staff';
            }
        }

        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');

        form.addEventListener('submit', function(e) {
            submitBtn.classList.add('loading');
            btnText.textContent = 'Memproses...';
            btnSpinner.classList.remove('hidden');
        });
    </script>
</body>
</html>