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
     * ✅ Hardcoded units list
     */
    private function getUnits()
    {
        return ['Sekretariat', 'IKP', 'SP', 'E-Government'];
    }

    /**
     * Display a listing of users
     * ✅ UPDATED: Allow admin and pimpinan to view
     */
    public function index(Request $request)
    {
        $role = Auth::user()->role;

        // ✅ Admin dan Pimpinan dapat mengakses
        if (!in_array($role, ['admin', 'pimpinan'])) {
            abort(403, 'Unauthorized - Admin and Pimpinan only');
        }

        $query = User::orderBy('created_at', 'desc');

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
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

        // Statistics with pimpinan
        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'staff' => User::where('role', 'staff')->count(),
            'pimpinan' => User::where('role', 'pimpinan')->count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
        ];

        // ✅ Use hardcoded units instead of database query
        $units = $this->getUnits();

        // Tentukan view berdasarkan role
        $viewPath = $role === 'pimpinan' 
            ? 'pimpinan.user.index' 
            : 'admin.user.index';

        return view($viewPath, compact('users', 'stats', 'units'));
    }

    /**
     * Show the form for creating a new user
     * ✅ ADMIN ONLY
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        // ✅ Use hardcoded units
        $units = $this->getUnits();

        return view('admin.user.create', compact('units'));
    }

    /**
     * Store a newly created user
     * ✅ ADMIN ONLY
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:admin,staff,pimpinan',
            'unit' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('admin.user.index')
            ->with('success', 'User "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Display the specified user
     * ✅ UPDATED: Allow admin and pimpinan
     */
    public function show($id)
    {
        $role = Auth::user()->role;

        // ✅ Admin dan Pimpinan dapat melihat detail
        if (!in_array($role, ['admin', 'pimpinan'])) {
            abort(403, 'Unauthorized - Admin and Pimpinan only');
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
        if (method_exists($user, 'receivedDispositions') && in_array($user->role, ['staff', 'pimpinan'])) {
            try {
                $userStats['dispositions_received'] = $user->receivedDispositions()->count();
            } catch (\Exception $e) {
                $userStats['dispositions_received'] = 0;
            }
        }

        if (method_exists($user, 'sentDispositions') && in_array($user->role, ['admin', 'pimpinan'])) {
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

        // Recent activities
        $recentArchives = [];
        if (method_exists($user, 'archives')) {
            try {
                $recentArchives = $user->archives()
                    ->with('category')
                    ->latest()
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                $recentArchives = [];
            }
        }

        // Tentukan view berdasarkan role
        $viewPath = $role === 'pimpinan' 
            ? 'pimpinan.user.show' 
            : 'admin.user.show';

        return view($viewPath, compact('user', 'userStats', 'recentArchives'));
    }

    /**
     * Show the form for editing the specified user
     * ✅ ADMIN ONLY
     */
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        $user = User::findOrFail($id);

        // ✅ Use hardcoded units
        $units = $this->getUnits();

        return view('admin.user.edit', compact('user', 'units'));
    }

    /**
     * Update the specified user
     * ✅ ADMIN ONLY
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,staff,pimpinan',
            'unit' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()->route('admin.user.index')
            ->with('success', 'Data user "' . $user->name . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified user
     * ✅ ADMIN ONLY
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        $user = User::findOrFail($id);

        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'User "' . $userName . '" berhasil dihapus!');
    }

    /**
     * Reset user password
     * ✅ ADMIN ONLY
     */
    public function resetPassword(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Password user "' . $user->name . '" berhasil direset!');
    }

    /**
     * Toggle user active status
     * ✅ ADMIN ONLY
     */
    public function toggleStatus($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        $user = User::findOrFail($id);

        // Prevent deactivating own account
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri!');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "User \"{$user->name}\" berhasil {$status}!");
    }

    /**
     * Get users by role (API endpoint for dropdowns)
     * ✅ UPDATED: Allow admin and pimpinan
     */
    public function getUsersByRole(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'pimpinan'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $role = $request->get('role');
        
        $users = User::where('is_active', true);
        
        if ($role && in_array($role, ['admin', 'staff', 'pimpinan'])) {
            $users->where('role', $role);
        }
        
        $users = $users->select('id', 'name', 'username', 'unit', 'role', 'email')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Get user statistics (API endpoint)
     * ✅ UPDATED: Allow admin and pimpinan
     */
    public function getUserStats($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'pimpinan'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        $stats = [
            'archives' => method_exists($user, 'archives') ? $user->archives()->count() : 0,
            'dispositions_sent' => method_exists($user, 'sentDispositions') ? $user->sentDispositions()->count() : 0,
            'dispositions_received' => method_exists($user, 'receivedDispositions') ? $user->receivedDispositions()->count() : 0,
            'incoming_letters' => method_exists($user, 'incomingLetters') ? $user->incomingLetters()->count() : 0,
            'outgoing_letters' => method_exists($user, 'outgoingLetters') ? $user->outgoingLetters()->count() : 0,
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Bulk update users (activate/deactivate multiple)
     * ✅ ADMIN ONLY
     */
    public function bulkUpdate(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'action' => 'required|in:activate,deactivate,delete'
        ]);

        $userIds = $validated['user_ids'];
        
        // Prevent affecting own account
        $userIds = array_diff($userIds, [Auth::id()]);

        $count = 0;

        switch ($validated['action']) {
            case 'activate':
                $count = User::whereIn('id', $userIds)->update(['is_active' => true]);
                $message = "$count user berhasil diaktifkan!";
                break;
                
            case 'deactivate':
                $count = User::whereIn('id', $userIds)->update(['is_active' => false]);
                $message = "$count user berhasil dinonaktifkan!";
                break;
                
            case 'delete':
                $count = User::whereIn('id', $userIds)->delete();
                $message = "$count user berhasil dihapus!";
                break;
        }

        return back()->with('success', $message);
    }

    /**
     * ✅ Export users data (Excel/PDF)
     * Admin and Pimpinan access
     */
    public function export(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'pimpinan'])) {
            abort(403, 'Unauthorized - Admin and Pimpinan only');
        }

        $format = $request->get('format', 'excel');
        
        // Get filtered users
        $users = User::orderBy('name')->get();

        // TODO: Implement actual export logic
        // For now, return JSON
        return response()->json([
            'success' => true,
            'message' => 'Export feature coming soon',
            'format' => $format,
            'count' => $users->count()
        ]);
    }
}