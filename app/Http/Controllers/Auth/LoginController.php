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
        // Validasi input
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:admin,staff',
        ], [
            'email.required' => 'Email harus diisi',
            'password.required' => 'Password harus diisi',
            'role.required' => 'Pilih role terlebih dahulu',
        ]);

        // Cek apakah email valid
        $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // ✅ FIX: Cek user dulu sebelum attempt login
        $user = User::where($loginType, $request->email)
                    ->where('role', $request->role)
                    ->first();

        // ✅ Cek apakah user ada
        if (!$user) {
            return back()
                ->with('error', 'Email/username atau role tidak ditemukan.')
                ->withInput($request->only('email', 'role'));
        }

        // ✅ Cek apakah user aktif
        if (!$user->is_active) {
            return back()
                ->with('error', 'Akun Anda tidak aktif. Hubungi administrator.')
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
            
            // ✅ PESAN SUKSES DENGAN SWEETALERT2
            $welcomeMessage = $user->role === 'admin' 
                ? 'Selamat datang kembali di Sistem Arsip Digital Diskominfo Batola, ' . $user->name . '!' 
                : 'Selamat datang di Sistem Arsip Digital Diskominfo Batola, ' . $user->name . '!';
            
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

        // ✅ LOGIN GAGAL - PESAN ERROR
        return back()
            ->with('error', 'Password yang Anda masukkan salah.')
            ->withInput($request->only('email', 'role'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $userName = Auth::user()->name ?? 'User';
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ PESAN LOGOUT SUKSES
        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem. Terima kasih, ' . $userName . '!');
    }
}