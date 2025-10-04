<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GANDARIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .login-bg {
            background: linear-gradient(135deg, #f0f8ff 0%, #e6f7ff 100%); /* Mirip gradien background */
        }
        .login-card {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Shadow lembut */
        }
        .demo-credentials {
            border: 1px solid #ffe58f;
            background-color: #fffbe6;
            padding: 1rem;
            margin-top: 1.5rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center">

    <div class="max-w-md w-full p-4">
        <div class="text-center mb-6">
            {{-- Ikon Arsip/Logo GANDARIA --}}
            <div class="mx-auto w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center mb-2">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h1 class="text-2xl font-semibold text-gray-800">GANDARIA</h1>
            <p class="text-sm text-gray-600">Gandeng Arsip Digital Barito Kuala</p>
            <p class="text-xs text-gray-500">Diskominfo Kabupaten Barito Kuala</p>
        </div>

        <div class="bg-white p-8 rounded-lg login-card">
            <h2 class="text-xl font-medium text-gray-700 mb-2 text-center">Masuk ke Sistem</h2>
            <p class="text-sm text-gray-500 mb-6 text-center">Silakan masukkan kredensial Anda untuk mengakses sistem</p>

            {{-- Menampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal Login!</strong>
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf {{-- Username Field --}}
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        {{-- Ikon User --}}
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" name="username" id="username" class="block w-full rounded-md border-gray-300 py-2 pl-10 pr-3 focus:border-blue-500 focus:ring-blue-500 sm:text-sm border" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
                    </div>
                </div>

                {{-- Password Field --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        {{-- Ikon Gembok --}}
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7a3 3 0 016 0v1H9V7z"></path></svg>
                        </div>
                        <input type="password" name="password" id="password" class="block w-full rounded-md border-gray-300 py-2 pl-10 pr-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm border" placeholder="Masukkan password" required>
                        {{-- Ikon Mata (Show/Hide) --}}
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 cursor-pointer">
                             <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Role Selection Field --}}
                <div class="mb-6">
                    <label for="role" class="block text-sm font-medium text-gray-700">Login Sebagai</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        {{-- Ikon Role --}}
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <select id="role" name="role" class="block w-full rounded-md border-gray-300 py-2 pl-10 pr-10 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm border">
                            <option value="" disabled selected>Pilih role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>


                {{-- Tombol Login --}}
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Masuk ke Sistem
                    </button>
                </div>
            </form>

            {{-- Lupa Password --}}
            <div class="mt-4 text-center">
                <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                    Lupa Password?
                </a>
            </div>
        </div>

        {{-- Demo Credentials --}}
        <div class="demo-credentials">
            <p class="font-bold">Demo Credentials:</p>
            <p><strong class="font-semibold text-orange-700">Admin:</strong> username: admin, password: password123</p>
            <p><strong class="font-semibold text-orange-700">Staff:</strong> username: staff1, password: password123</p>
            <p class="mt-2 text-xs text-gray-500">
                &copy; {{ date('Y') }} Diskominfo Kabupaten Barito Kuala. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>