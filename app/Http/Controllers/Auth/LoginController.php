<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        // ✅ Jika sudah login, redirect ke dashboard
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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $role = Auth::user()->role;
            
            // ✅ Redirect sesuai role
            if ($role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($role === 'staff') {
                return redirect()->intended(route('staff.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}