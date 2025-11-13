<?php

namespace App\Http\Controllers;

use App\Models\Disposition;
use App\Models\Archive;
use App\Models\Asset;
use App\Models\User;
use App\Models\Notification;
use App\Mail\DispositionCreatedMail;
use App\Mail\DispositionCompletedMail;
use App\Mail\DispositionForwardedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DispositionController extends Controller
{
    /**
     * Display a listing of dispositions
     */
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $user = Auth::user();
        
        $query = Disposition::with(['disposable', 'fromUser', 'toUser', 'finalRecipient', 'forwardedFrom', 'forwardedTo'])
            ->orderBy('created_at', 'desc');
        
        // Filter berdasarkan role
        if ($role === 'staff' || $role === 'pimpinan') {
            $query->where(function($q) use ($user) {
                $q->where('to_user_id', $user->id)
                  ->orWhere('from_user_id', $user->id)
                  ->orWhere('final_recipient_id', $user->id);
            });
        }
        
        // Filter berdasarkan tipe item
        if ($request->filled('item_type')) {
            $query->itemType($request->item_type);
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->status($request->status);
        }
        
        // Filter berdasarkan priority
        if ($request->filled('priority')) {
            $query->priority($request->priority);
        }
        
        // Filter berdasarkan forwarding status
        if ($request->filled('forwarding_status')) {
            $query->where('forwarding_status', $request->forwarding_status);
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
        
        // Statistik
        if (in_array($role, ['admin', 'pimpinan'])) {
            $stats = [
                'total' => Disposition::count(),
                'pending' => Disposition::status('pending')->count(),
                'in_progress' => Disposition::status('in_progress')->count(),
                'completed' => Disposition::status('completed')->count(),
                'pending_forward' => Disposition::where('forwarding_status', 'pending_forward')->count(),
            ];
        } else {
            $stats = [
                'total' => Disposition::where(function($q) use ($user) {
                    $q->where('to_user_id', $user->id)->orWhere('from_user_id', $user->id);
                })->count(),
                'pending' => Disposition::where(function($q) use ($user) {
                    $q->where('to_user_id', $user->id)->orWhere('from_user_id', $user->id);
                })->status('pending')->count(),
                'in_progress' => Disposition::where(function($q) use ($user) {
                    $q->where('to_user_id', $user->id)->orWhere('from_user_id', $user->id);
                })->status('in_progress')->count(),
                'completed' => Disposition::where(function($q) use ($user) {
                    $q->where('to_user_id', $user->id)->orWhere('from_user_id', $user->id);
                })->status('completed')->count(),
                'pending_forward' => Disposition::where('from_user_id', $user->id)
                    ->where('forwarding_status', 'pending_forward')->count(),
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
        
        $archives = Archive::orderBy('created_at', 'desc')->get();
        $assets = Asset::orderBy('created_at', 'desc')->get();
        
        // Tentukan users berdasarkan role
        if ($role === 'admin') {
            // Admin bisa kirim ke staff dan pimpinan
            $users = User::whereIn('role', ['staff', 'pimpinan'])->get();
        } elseif ($role === 'staff') {
            // Staff hanya bisa kirim ke admin
            $users = User::where('role', 'admin')->get();
        } elseif ($role === 'pimpinan') {
            // Pimpinan hanya bisa kirim ke admin
            $users = User::where('role', 'admin')->get();
        }
        
        return view("{$role}.disposisi.create", compact('archives', 'assets', 'users'));
    }

    /**
     * Store a newly created disposition
     */
    public function store(Request $request)
    {
        $role = Auth::user()->role;
        
        // Validation
        $validated = $request->validate([
            'item_type' => 'required|in:arsip,aset',
            'item_id' => 'required|integer',
            'to_user_id' => 'required|exists:users,id',
            'final_recipient_id' => 'nullable|exists:users,id', // Untuk forwarding
            'subject' => 'required|string|max:255',
            'instruction' => 'required|string',
            'priority' => 'required|in:urgent,high,normal,low',
            'deadline' => 'nullable|date|after:today',
            'forwarding_note' => 'nullable|string', // Catatan untuk penerusan
        ]);
        
        // Validasi item
        if ($validated['item_type'] === 'arsip') {
            $item = Archive::findOrFail($validated['item_id']);
            $disposableType = Archive::class;
        } else {
            $item = Asset::findOrFail($validated['item_id']);
            $disposableType = Asset::class;
        }
        
        // Tentukan forwarding status
        $forwardingStatus = 'direct';
        $finalRecipientId = null;
        
        // Jika staff/pimpinan ingin kirim ke selain admin, set pending_forward
        if (($role === 'staff' || $role === 'pimpinan') && $request->filled('final_recipient_id')) {
            $forwardingStatus = 'pending_forward';
            $finalRecipientId = $validated['final_recipient_id'];
        }
        
        // Create disposition
        $disposition = Disposition::create([
            'nomor_disposisi' => Disposition::generateNomorDisposisi(),
            'disposable_type' => $disposableType,
            'disposable_id' => $validated['item_id'],
            'from_user_id' => Auth::id(),
            'to_user_id' => $validated['to_user_id'],
            'final_recipient_id' => $finalRecipientId,
            'subject' => $validated['subject'],
            'instruction' => $validated['instruction'],
            'priority' => $validated['priority'],
            'deadline' => $validated['deadline'] ?? null,
            'forwarding_note' => $validated['forwarding_note'] ?? null,
            'status' => 'pending',
            'forwarding_status' => $forwardingStatus,
        ]);
        
        // Create notification
        Notification::createDispositionNotification($disposition);
        
        // Send email
        try {
            $recipient = User::find($validated['to_user_id']);
            if ($recipient && $recipient->email) {
                Mail::to($recipient->email)->send(new DispositionCreatedMail($disposition));
                Log::info('Disposition email sent', ['disposition_id' => $disposition->id]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send disposition email', ['error' => $e->getMessage()]);
        }
        
        $message = $forwardingStatus === 'pending_forward' 
            ? "Disposisi berhasil dibuat dan menunggu penerusan oleh Admin!" 
            : "Disposisi berhasil dibuat dan dikirim!";
        
        return redirect()->route("{$role}.disposisi.index")->with('success', $message);
    }

    /**
     * Display the specified disposition
     */
    public function show($id)
    {
        $role = Auth::user()->role;
        $user = Auth::user();
        
        $disposition = Disposition::with([
            'disposable', 
            'fromUser', 
            'toUser', 
            'finalRecipient',
            'forwardedFrom.fromUser',
            'forwardedTo.toUser'
        ])->findOrFail($id);
        
        // Check authorization
        if ($role === 'staff' || $role === 'pimpinan') {
            if ($disposition->to_user_id !== $user->id && 
                $disposition->from_user_id !== $user->id &&
                $disposition->final_recipient_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
        }
        
        // Mark as read
        if ($disposition->to_user_id === $user->id && !$disposition->isRead()) {
            $disposition->update(['read_at' => now()]);
            
            Notification::forUser($user->id)
                ->where('type', 'disposition')
                ->whereJsonContains('data->disposition_id', $id)
                ->unread()
                ->update(['read_at' => now()]);
        }
        
        return view("{$role}.disposisi.show", compact('disposition'));
    }

    /**
     * Show the form for editing
     */
    public function edit($id)
    {
        $role = Auth::user()->role;
        $user = Auth::user();
        
        $disposition = Disposition::with('disposable')->findOrFail($id);
        
        // Check authorization
        if (($role === 'staff' || $role === 'pimpinan') && $disposition->from_user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        $archives = Archive::orderBy('created_at', 'desc')->get();
        $assets = Asset::orderBy('created_at', 'desc')->get();
        
        if ($role === 'admin') {
            $users = User::whereIn('role', ['staff', 'pimpinan'])->get();
        } else {
            $users = User::where('role', 'admin')->get();
        }
        
        return view("{$role}.disposisi.edit", compact('disposition', 'archives', 'assets', 'users'));
    }

    /**
     * Update the specified disposition
     */
    public function update(Request $request, $id)
    {
        $role = Auth::user()->role;
        $user = Auth::user();
        
        $disposition = Disposition::findOrFail($id);
        
        // Check authorization
        if (($role === 'staff' || $role === 'pimpinan') && $disposition->from_user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'item_type' => 'required|in:arsip,aset',
            'item_id' => 'required|integer',
            'to_user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'instruction' => 'required|string',
            'priority' => 'required|in:urgent,high,normal,low',
            'deadline' => 'nullable|date',
        ]);
        
        if ($validated['item_type'] === 'arsip') {
            $item = Archive::findOrFail($validated['item_id']);
            $disposableType = Archive::class;
        } else {
            $item = Asset::findOrFail($validated['item_id']);
            $disposableType = Asset::class;
        }
        
        $disposition->update([
            'disposable_type' => $disposableType,
            'disposable_id' => $validated['item_id'],
            'to_user_id' => $validated['to_user_id'],
            'subject' => $validated['subject'],
            'instruction' => $validated['instruction'],
            'priority' => $validated['priority'],
            'deadline' => $validated['deadline'] ?? null,
        ]);
        
        return redirect()->route("{$role}.disposisi.index")
            ->with('success', 'Disposisi berhasil diperbarui!');
    }

    /**
     * Remove the specified disposition
     */
    public function destroy($id)
    {
        $role = Auth::user()->role;
        $user = Auth::user();
        
        $disposition = Disposition::findOrFail($id);
        
        // Check authorization
        if (($role === 'staff' || $role === 'pimpinan') && $disposition->from_user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        $disposition->delete();
        
        return redirect()->route("{$role}.disposisi.index")
            ->with('success', 'Disposisi berhasil dihapus!');
    }

    /**
     * Update status disposisi dengan upload bukti penyelesaian
     */
    public function updateStatus(Request $request, $id)
    {
        $role = Auth::user()->role;
        $user = Auth::user();
        
        $disposition = Disposition::findOrFail($id);
        
        // Hanya penerima yang bisa update status
        if ($disposition->to_user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:in_progress,completed,rejected',
            'notes' => 'nullable|string',
            'completion_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:10240', // Max 10MB
            'completion_description' => 'nullable|string',
        ]);
        
        // Jika status completed, wajib ada bukti (file atau deskripsi)
        if ($validated['status'] === 'completed') {
            if (!$request->hasFile('completion_file') && empty($validated['completion_description'])) {
                return back()->withErrors([
                    'completion_proof' => 'Harap upload file bukti penyelesaian atau berikan deskripsi hasil pekerjaan!'
                ])->withInput();
            }
        }
        
        $data = [
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'completion_description' => $validated['completion_description'],
        ];
        
        // Handle file upload
        if ($request->hasFile('completion_file')) {
            // Hapus file lama jika ada
            if ($disposition->completion_file && Storage::disk('public')->exists($disposition->completion_file)) {
                Storage::disk('public')->delete($disposition->completion_file);
            }
            
            $file = $request->file('completion_file');
            $fileName = 'completion_' . $disposition->nomor_disposisi . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('dispositions/completions', $fileName, 'public');
            $data['completion_file'] = $filePath;
        }
        
        if ($validated['status'] === 'completed') {
            $data['completed_at'] = now();
            
            // Create notification untuk pemberi disposisi
            Notification::createDispositionCompletedNotification($disposition);
            
            // Send email
            try {
                $sender = $disposition->fromUser;
                if ($sender && $sender->email) {
                    Mail::to($sender->email)->send(new DispositionCompletedMail($disposition));
                    Log::info('Disposition completed email sent', ['disposition_id' => $disposition->id]);
                }
                
                // Jika ada forwarding, notify juga ultimate sender
                if ($disposition->forwardedFrom) {
                    $ultimateSender = $disposition->ultimateSender;
                    if ($ultimateSender && $ultimateSender->email) {
                        Mail::to($ultimateSender->email)->send(new DispositionCompletedMail($disposition));
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to send completion email', ['error' => $e->getMessage()]);
            }
        }
        
        $disposition->update($data);
        
        $statusText = [
            'in_progress' => 'sedang dikerjakan',
            'completed' => 'selesai dikerjakan',
            'rejected' => 'ditolak'
        ];
        
        return back()->with('success', 'Status disposisi berhasil diubah menjadi ' . $statusText[$validated['status']] . '!');
    }

    /**
     * Forward disposition (Admin only)
     */
    public function forwardDisposition(Request $request, $id)
    {
        $user = Auth::user();
        
        // Hanya admin yang bisa forward
        if ($user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat meneruskan disposisi');
        }
        
        $disposition = Disposition::findOrFail($id);
        
        // Validasi disposisi harus pending_forward
        if ($disposition->forwarding_status !== 'pending_forward') {
            return back()->withErrors(['error' => 'Disposisi ini tidak memerlukan penerusan']);
        }
        
        $validated = $request->validate([
            'forwarding_note' => 'nullable|string',
        ]);
        
        // Create disposisi baru untuk final recipient
        $forwardedDisposition = Disposition::create([
            'nomor_disposisi' => Disposition::generateNomorDisposisi(),
            'disposable_type' => $disposition->disposable_type,
            'disposable_id' => $disposition->disposable_id,
            'from_user_id' => $user->id, // Admin sebagai pengirim
            'to_user_id' => $disposition->final_recipient_id,
            'subject' => $disposition->subject,
            'instruction' => $disposition->instruction,
            'priority' => $disposition->priority,
            'deadline' => $disposition->deadline,
            'status' => 'pending',
            'forwarding_status' => 'direct',
            'forwarded_from_id' => $disposition->id,
            'forwarding_note' => $validated['forwarding_note'] ?? $disposition->forwarding_note,
        ]);
        
        // Update disposisi asli
        $disposition->update([
            'forwarding_status' => 'forwarded',
            'forwarded_at' => now(),
            'forwarded_to_id' => $forwardedDisposition->id,
        ]);
        
        // Create notification
        Notification::createDispositionNotification($forwardedDisposition);
        
        // Send email
        try {
            $finalRecipient = $forwardedDisposition->toUser;
            if ($finalRecipient && $finalRecipient->email) {
                Mail::to($finalRecipient->email)->send(new DispositionForwardedMail($forwardedDisposition));
            }
            
            // Notify original sender
            $originalSender = $disposition->fromUser;
            if ($originalSender && $originalSender->email) {
                // Send notification that disposition has been forwarded
                Mail::to($originalSender->email)->send(new DispositionForwardedMail($forwardedDisposition));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send forwarding email', ['error' => $e->getMessage()]);
        }
        
        return redirect()->route('admin.disposisi.show', $disposition->id)
            ->with('success', 'Disposisi berhasil diteruskan ke ' . $forwardedDisposition->toUser->name);
    }

    /**
     * Download completion file
     */
public function downloadCompletionFile($id)
{
    $disposition = Disposition::findOrFail($id);
    
    if (!$disposition->completion_file || !Storage::disk('public')->exists($disposition->completion_file)) {
        abort(404, 'File tidak ditemukan');
    }
    
    // ✅ FIX: Replace karakter "/" dengan "_" untuk nama file yang aman
    $safeFileName = str_replace('/', '_', $disposition->nomor_disposisi);
    $extension = pathinfo($disposition->completion_file, PATHINFO_EXTENSION);
    
    return Storage::disk('public')->download(
        $disposition->completion_file,
        'Bukti_' . $safeFileName . '.' . $extension
    );
}

    /**
     * Get dispositions that need forwarding (Admin only)
     */
    public function needsForwarding()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $dispositions = Disposition::with(['disposable', 'fromUser', 'finalRecipient'])
            ->where('forwarding_status', 'pending_forward')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.disposisi.needs-forwarding', compact('dispositions'));
    }
}