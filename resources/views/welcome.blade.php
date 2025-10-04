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
            {{-- Ganti dengan SVG/ikon Anda --}}
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
                @csrf

                {{-- Username Field --}}
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" name="username" id="username" class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm border" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
                    </div>
                </div>

                {{-- Password Field --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="password" name="password" id="password" class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm border" placeholder="Masukkan password" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            {{-- Ikon mata untuk show/hide password, diabaikan untuk contoh ini --}}
                        </div>
                    </div>
                </div>

                {{-- Role Selection Field (Sesuai Gambar) --}}
                <div class="mb-6">
                    <label for="role" class="block text-sm font-medium text-gray-700">Login Sebagai</label>
                    <select id="role" name="role" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="" disabled selected>Pilih role</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                    </select>
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