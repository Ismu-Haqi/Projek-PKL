<?php

namespace App\Http\Controllers;

use App\Models\Disposition;
use App\Models\Archive;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DispositionController extends Controller
{
    /**
     * Display a listing of dispositions
     */
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $user = Auth::user();
        
        $query = Disposition::with(['archive', 'fromUser', 'toUser'])
            ->orderBy('created_at', 'desc');
        
        // Filter berdasarkan role
        if ($role === 'staff') {
            // Staff hanya lihat disposisi yang diterima
            $query->receivedBy($user->id);
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->status($request->status);
        }
        
        // Filter berdasarkan priority
        if ($request->filled('priority')) {
            $query->priority($request->priority);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_disposisi', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('instruction', 'like', "%{$search}%");
            });
        }
        
        $dispositions = $query->paginate(15);
        
        // Statistik berdasarkan role
        if ($role === 'admin') {
            $stats = [
                'total' => Disposition::count(),
                'pending' => Disposition::status('pending')->count(),
                'in_progress' => Disposition::status('in_progress')->count(),
                'completed' => Disposition::status('completed')->count(),
            ];
        } else {
            // Staff hanya lihat statistik disposisi yang diterimanya
            $stats = [
                'total' => Disposition::receivedBy($user->id)->count(),
                'pending' => Disposition::receivedBy($user->id)->status('pending')->count(),
                'in_progress' => Disposition::receivedBy($user->id)->status('in_progress')->count(),
                'completed' => Disposition::receivedBy($user->id)->status('completed')->count(),
            ];
        }
        
        return view("{$role}.disposisi.index", compact('dispositions', 'stats'));
    }

    /**
     * Show the form for creating a new disposition
     */
    public function create()
    {
        $role = Auth::user()->role;
        
        // Only admin can create disposition
        if ($role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $archives = Archive::orderBy('created_at', 'desc')->get();
        $users = User::where('role', 'staff')->get();
        
        return view("{$role}.disposisi.create", compact('archives', 'users'));
    }

    /**
     * Store a newly created disposition
     */
    public function store(Request $request)
    {
        $role = Auth::user()->role;
        
        // Only admin can create disposition
        if ($role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'archive_id' => 'required|exists:archives,id',
            'to_user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'instruction' => 'required|string',
            'priority' => 'required|in:urgent,high,normal,low',
            'deadline' => 'nullable|date|after:today',
        ]);
        
        $validated['nomor_disposisi'] = Disposition::generateNomorDisposisi();
        $validated['from_user_id'] = Auth::id();
        $validated['status'] = 'pending';
        
        $disposition = Disposition::create($validated);
        
        // TODO: Send notification to recipient
        
        return redirect()->route('admin.disposisi.index')
            ->with('success', 'Disposisi berhasil dibuat dan dikirim!');
    }

    /**
     * Display the specified disposition
     */
    public function show($id)
    {
        $role = Auth::user()->role;
        $user = Auth::user();
        
        $disposition = Disposition::with(['archive', 'fromUser', 'toUser'])->findOrFail($id);
        
        // Check authorization
        if ($role === 'staff' && $disposition->to_user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        // Mark as read if staff and not read yet
        if ($role === 'staff' && !$disposition->isRead()) {
            $disposition->update(['read_at' => now()]);
        }
        
        return view("{$role}.disposisi.show", compact('disposition'));
    }

    /**
     * Show the form for editing the specified disposition
     */
    public function edit($id)
    {
        $role = Auth::user()->role;
        
        // Only admin can edit
        if ($role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $disposition = Disposition::findOrFail($id);
        $archives = Archive::orderBy('created_at', 'desc')->get();
        $users = User::where('role', 'staff')->get();
        
        return view("{$role}.disposisi.edit", compact('disposition', 'archives', 'users'));
    }

    /**
     * Update the specified disposition
     */
    public function update(Request $request, $id)
    {
        $role = Auth::user()->role;
        
        // Only admin can update
        if ($role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $disposition = Disposition::findOrFail($id);
        
        $validated = $request->validate([
            'archive_id' => 'required|exists:archives,id',
            'to_user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'instruction' => 'required|string',
            'priority' => 'required|in:urgent,high,normal,low',
            'deadline' => 'nullable|date',
        ]);
        
        $disposition->update($validated);
        
        return redirect()->route('admin.disposisi.index')
            ->with('success', 'Disposisi berhasil diperbarui!');
    }

    /**
     * Remove the specified disposition
     */
    public function destroy($id)
    {
        $role = Auth::user()->role;
        
        // Only admin can delete
        if ($role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $disposition = Disposition::findOrFail($id);
        $disposition->delete();
        
        return redirect()->route('admin.disposisi.index')
            ->with('success', 'Disposisi berhasil dihapus!');
    }

    /**
     * Update status disposisi (for staff)
     */
    public function updateStatus(Request $request, $id)
    {
        $role = Auth::user()->role;
        $user = Auth::user();
        
        $disposition = Disposition::findOrFail($id);
        
        // Check authorization
        if ($role === 'staff' && $disposition->to_user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:in_progress,completed,rejected',
            'notes' => 'nullable|string',
        ]);
        
        $data = $validated;
        
        if ($validated['status'] === 'completed') {
            $data['completed_at'] = now();
        }
        
        $disposition->update($data);
        
        return back()->with('success', 'Status disposisi berhasil diperbarui!');
    }
}