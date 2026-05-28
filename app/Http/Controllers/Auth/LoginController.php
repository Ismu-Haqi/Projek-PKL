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
        // ✅ UPDATED: Jika sudah login, redirect ke dashboard sesuai role (termasuk pimpinan)
        if (Auth::check()) {
            $role = Auth::user()->role;
            
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'staff') {
                return redirect()->route('staff.dashboard');
            } elseif ($role === 'pimpinan') {
                return redirect()->route('pimpinan.dashboard');
            }
            
            // Fallback jika role tidak dikenali
            Auth::logout();
        }
        
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        // ✅ UPDATED: Validasi input termasuk captcha dan role pimpinan
        $request->validate([
            'email'        => 'required|string',
            'password'     => 'required|string',
            'captcha'      => 'required|string',
            'captcha_code' => 'required|string',
        ], [
            'email.required'    => 'Email/Username harus diisi',
            'password.required' => 'Password harus diisi',
            'captcha.required'  => 'Captcha harus diisi',
        ]);

        // ✅ VALIDASI CAPTCHA - Cek apakah captcha cocok
        if ($request->captcha !== $request->captcha_code) {
            return back()
                ->withErrors(['captcha' => 'Kode captcha yang Anda masukkan salah! Kode captcha bersifat case-sensitive, pastikan Anda memasukkan dengan benar.'])
                ->withInput($request->only('email', 'role'));
        }

        // Cek apakah email valid
        $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Cek user tanpa filter role — role ditentukan dari database
        $user = User::where($loginType, $request->email)->first();

        // ✅ Cek apakah user ada
        if (!$user) {
            return back()
            ->with('login_error', 'Email/username tidak ditemukan. Pastikan username yang Anda masukkan benar.')
            ->withInput($request->only('email'));
        }

        // Cek apakah user tidak aktif
        if (!$user->is_active) {
            return back()
                ->with('warning', 'Akun Anda tidak aktif. Silakan hubungi administrator.')
                ->withInput($request->only('email'));
        }

        $credentials = [
            $loginType => $request->email,
            'password' => $request->password,
        ];

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
            
            // ✅ Role-specific welcome message
            $roleLabel = match($user->role) {
                'admin' => 'Administrator',
                'staff' => 'Staff',
                'pimpinan' => 'Pimpinan',
                default => 'User'
            };
            
            $welcomeMessage = $greeting . ', ' . $user->name . '! Selamat datang di GANDARIA - Sistem Arsip Digital Diskominfo Batola.';
            
            // ✅ UPDATED: Redirect sesuai role dengan intended (termasuk pimpinan)
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', $welcomeMessage);
            } elseif ($user->role === 'staff') {
                return redirect()->intended(route('staff.dashboard'))
                    ->with('success', $welcomeMessage);
            } elseif ($user->role === 'pimpinan') {
                return redirect()->intended(route('pimpinan.dashboard'))
                    ->with('success', $welcomeMessage);
            }
            
            // Jika role tidak sesuai (fallback - seharusnya tidak terjadi)
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->with('error', 'Role tidak valid untuk akses sistem. Hubungi administrator.');
        }

        return back()
            ->with('password_error', 'Password yang Anda masukkan salah. Silakan periksa kembali.')
            ->withInput($request->only('email'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $userName = Auth::user()->name ?? 'User';
        $userRole = Auth::user()->role ?? 'user';
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
        
        // ✅ Role-specific goodbye message (optional)
        $roleMessage = match($userRole) {
            'admin' => 'Terima kasih telah mengelola sistem GANDARIA.',
            'staff' => 'Terima kasih atas kontribusi Anda hari ini.',
            'pimpinan' => 'Terima kasih telah menggunakan Dashboard Pimpinan.',
            default => 'Terima kasih telah menggunakan GANDARIA.'
        };
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ PESAN LOGOUT DENGAN UCAPAN
        return redirect()->route('login')
            ->with('success', 'Terima kasih, ' . $userName . '! Anda telah berhasil keluar dari sistem. ' . $farewell . '!');
    }

    /**
     * ✅ NEW: Get redirect route based on role
     * Helper method untuk mendapatkan route dashboard berdasarkan role
     *
     * @param string $role
     * @return string
     */
    protected function redirectTo($role)
    {
        return match($role) {
            'admin' => route('admin.dashboard'),
            'staff' => route('staff.dashboard'),
            'pimpinan' => route('pimpinan.dashboard'),
            default => route('login')
        };
    }

    /**
     * ✅ NEW: Get role display name
     * Helper method untuk mendapatkan nama role yang friendly
     *
     * @param string $role
     * @return string
     */
    protected function getRoleDisplayName($role)
    {
        return match($role) {
            'admin' => 'Administrator',
            'staff' => 'Staff',
            'pimpinan' => 'Pimpinan',
            default => 'User'
        };
    }
}