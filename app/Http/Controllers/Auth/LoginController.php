<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses percobaan login.
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:admin,staff', // Tambahkan validasi role
        ]);

        // Gunakan 'username' sebagai field login
        $field = 'username';
        $credentials = [
            $field => $request->username,
            'password' => $request->password,
            'role' => $request->role, // Tambahkan role ke array credentials
        ];

        // 2. Coba Login
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // 3. Redirection berdasarkan Role
            return $this->redirectToRoleDashboard(Auth::user()->role);
        }

        // 4. Jika Gagal, kembali ke form login dengan error
        return back()->withErrors([
            'username' => 'Kombinasi kredensial tidak cocok atau role salah.',
        ])->onlyInput('username', 'role');
    }

    /**
     * Logika Redirection.
     */
    protected function redirectToRoleDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            default:
                return redirect('/'); // Default fallback
        }
    }

    /**
     * Proses Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}