<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        // Only admin can access
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $query = User::orderBy('created_at', 'desc');

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('unit', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15);

        // Statistics
        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'staff' => User::where('role', 'staff')->count(),
            'active' => User::where('is_active', true)->count(),
        ];

        return view('admin.user.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.user.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:admin,staff',
            'unit' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        User::create($validated);

        // ✅ PESAN SUKSES TAMBAH USER
        return redirect()->route('admin.user.index')
            ->with('success', 'User "' . $validated['name'] . '" berhasil ditambahkan ke sistem Diskominfo Batola!');
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($id);

        // Get user statistics dengan pengecekan method exists
        $userStats = [
            'dispositions_received' => 0,
            'dispositions_sent' => 0,
            'archives_created' => 0,
            'incoming_letters' => 0,
            'outgoing_letters' => 0,
        ];

        // Check if methods exist before calling
        if (method_exists($user, 'receivedDispositions') && $user->role === 'staff') {
            try {
                $userStats['dispositions_received'] = $user->receivedDispositions()->count();
            } catch (\Exception $e) {
                $userStats['dispositions_received'] = 0;
            }
        }

        if (method_exists($user, 'sentDispositions') && $user->role === 'admin') {
            try {
                $userStats['dispositions_sent'] = $user->sentDispositions()->count();
            } catch (\Exception $e) {
                $userStats['dispositions_sent'] = 0;
            }
        }

        if (method_exists($user, 'archives')) {
            try {
                $userStats['archives_created'] = $user->archives()->count();
            } catch (\Exception $e) {
                $userStats['archives_created'] = 0;
            }
        }

        if (method_exists($user, 'incomingLetters')) {
            try {
                $userStats['incoming_letters'] = $user->incomingLetters()->count();
            } catch (\Exception $e) {
                $userStats['incoming_letters'] = 0;
            }
        }

        if (method_exists($user, 'outgoingLetters')) {
            try {
                $userStats['outgoing_letters'] = $user->outgoingLetters()->count();
            } catch (\Exception $e) {
                $userStats['outgoing_letters'] = 0;
            }
        }

        return view('admin.user.show', compact('user', 'userStats'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($id);

        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,staff',
            'unit' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        // ✅ PESAN SUKSES UPDATE USER
        return redirect()->route('admin.user.index')
            ->with('success', 'Data user "' . $user->name . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($id);

        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $userName = $user->name;
        $user->delete();

        // ✅ PESAN SUKSES DELETE USER
        return redirect()->route('admin.user.index')
            ->with('success', 'User "' . $userName . '" berhasil dihapus dari sistem!');
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        // ✅ PESAN SUKSES RESET PASSWORD
        return back()->with('success', 'Password user "' . $user->name . '" berhasil direset!');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($id);

        // Prevent deactivating own account
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri!');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        // ✅ PESAN SUKSES TOGGLE STATUS
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "User \"{$user->name}\" berhasil {$status} dalam sistem!");
    }
}