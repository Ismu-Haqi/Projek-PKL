<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
            'email' => 'required|string', // UBAH dari 'email' menjadi 'string'
            'password' => 'required|string',
            'role' => 'required|in:admin,staff', // Tambahkan validasi role
        ], [
            'email.required' => 'Email harus diisi',
            'password.required' => 'Password harus diisi',
            'role.required' => 'Pilih role terlebih dahulu',
        ]);

        // Cek apakah email valid
        $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Credentials untuk login
        $credentials = [
            $loginType => $request->email,
            'password' => $request->password,
            'role' => $request->role, // Pastikan role sesuai
        ];

        // Attempt login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Redirect sesuai role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang, ' . $user->name . '!');
            } elseif ($user->role === 'staff') {
                return redirect()->intended(route('staff.dashboard'))
                    ->with('success', 'Selamat datang, ' . $user->name . '!');
            }
            
            // Jika role tidak sesuai
            Auth::logout();
            return back()->withErrors([
                'role' => 'Role tidak valid.',
            ]);
        }

        // Login gagal
        return back()->withErrors([
            'email' => 'Email, password, atau role yang Anda masukkan salah.',
        ])->withInput($request->only('email', 'role'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout');
    }
}