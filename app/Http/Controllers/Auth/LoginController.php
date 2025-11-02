<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            $role = Auth::user()->role;
            return redirect()->route($role === 'admin' ? 'admin.dashboard' : 'staff.dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        // Validasi input termasuk captcha
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:admin,staff',
            'captcha' => 'required|string',
            'captcha_code' => 'required|string',
        ], [
            'email.required' => 'Email harus diisi',
            'password.required' => 'Password harus diisi',
            'role.required' => 'Pilih role terlebih dahulu',
            'captcha.required' => 'Captcha harus diisi',
            'captcha_code.required' => 'Kode captcha tidak valid',
        ]);

        // ✅ VALIDASI CAPTCHA - Cek apakah captcha cocok
        if ($request->captcha !== $request->captcha_code) {
            return back()
                ->withErrors(['captcha' => 'Kode captcha yang Anda masukkan salah! Silakan coba lagi.'])
                ->withInput($request->only('email', 'role'));
        }

        // Cek apakah email valid
        $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // ✅ Cek user dulu sebelum attempt login
        $user = User::where($loginType, $request->email)
                    ->where('role', $request->role)
                    ->first();

        // ✅ Cek apakah user ada
        if (!$user) {
            return back()
                ->with('login_error', 'Email/username tidak ditemukan atau role tidak sesuai. Pastikan Anda memilih role yang benar.')
                ->withInput($request->only('email', 'role'));
        }

        // ✅ Cek apakah user aktif
        if (!$user->is_active) {
            return back()
                ->with('warning', 'Akun Anda tidak aktif. Silakan hubungi administrator untuk mengaktifkan kembali akun Anda.')
                ->withInput($request->only('email', 'role'));
        }

        // Credentials untuk login
        $credentials = [
            $loginType => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ];

        // ✅ Attempt login dengan remember me
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // ✅ Regenerate session untuk keamanan
            $request->session()->regenerate();

            $user = Auth::user();
            
            // ✅ PESAN SUKSES LOGIN DENGAN SAMBUTAN
            $currentHour = now()->format('H');
            $greeting = '';
            
            if ($currentHour >= 5 && $currentHour < 12) {
                $greeting = 'Selamat Pagi';
            } elseif ($currentHour >= 12 && $currentHour < 15) {
                $greeting = 'Selamat Siang';
            } elseif ($currentHour >= 15 && $currentHour < 18) {
                $greeting = 'Selamat Sore';
            } else {
                $greeting = 'Selamat Malam';
            }
            
            $welcomeMessage = $greeting . ', ' . $user->name . '! Selamat datang di GANDARIA - Sistem Arsip Digital Diskominfo Batola.';
            
            // ✅ Redirect sesuai role dengan intended
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', $welcomeMessage);
            } elseif ($user->role === 'staff') {
                return redirect()->intended(route('staff.dashboard'))
                    ->with('success', $welcomeMessage);
            }
            
            // Jika role tidak sesuai
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->with('error', 'Role tidak valid untuk akses sistem.');
        }

        // ✅ LOGIN GAGAL - PASSWORD SALAH
        return back()
            ->with('password_error', 'Password yang Anda masukkan salah. Silakan periksa kembali atau gunakan fitur lupa password jika Anda tidak ingat.')
            ->withInput($request->only('email', 'role'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $userName = Auth::user()->name ?? 'User';
        $currentHour = now()->format('H');
        
        // Ucapan sesuai waktu
        $farewell = '';
        if ($currentHour >= 5 && $currentHour < 12) {
            $farewell = 'Selamat beraktivitas';
        } elseif ($currentHour >= 12 && $currentHour < 15) {
            $farewell = 'Selamat beristirahat';
        } elseif ($currentHour >= 15 && $currentHour < 18) {
            $farewell = 'Semoga hari Anda menyenangkan';
        } else {
            $farewell = 'Selamat beristirahat';
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ PESAN LOGOUT DENGAN UCAPAN
        return redirect()->route('login')
            ->with('success', 'Terima kasih, ' . $userName . '! Anda telah berhasil keluar dari sistem. ' . $farewell . '!');
    }
}