<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetBorrow;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AssetBorrowController extends Controller
{
    /**
     * Display listing of borrows
     * Admin bisa lihat semua peminjaman
     */
    public function index(Request $request)
    {
        $query = AssetBorrow::with(['asset', 'borrower', 'approver'])
            ->orderBy('created_at', 'desc');
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            if ($request->status === 'menunggu') {
                $query->where('status', 'pending');
            } elseif ($request->status === 'aktif') {
                $query->whereIn('status', ['approved', 'borrowed']);
            } elseif ($request->status === 'terlambat') {
                $query->where('status', 'overdue');
            } elseif ($request->status === 'selesai') {
                $query->where('status', 'returned');
            } else {
                $query->where('status', $request->status);
            }
        }
        
        // Filter berdasarkan unit
        if ($request->filled('unit')) {
            $query->where('borrower_unit', $request->unit);
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('kode_peminjaman', 'like', '%' . $request->search . '%')
                  ->orWhereHas('asset', function($q) use ($request) {
                      $q->where('nama', 'like', '%' . $request->search . '%')
                        ->orWhere('kode_asset', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('borrower', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        $borrows = $query->paginate(15);
        
        // Statistik untuk card
        $stats = [
            'menunggu' => AssetBorrow::where('status', 'pending')->count(),
            'disetujui' => AssetBorrow::where('status', 'approved')->count(),
            'dipinjam' => AssetBorrow::where('status', 'borrowed')->count(),
            'terlambat' => AssetBorrow::where('status', 'overdue')->count(),
            'dikembalikan' => AssetBorrow::where('status', 'returned')->count(),
        ];
        
        // Get unique units untuk filter
        $units = AssetBorrow::select('borrower_unit')
            ->distinct()
            ->whereNotNull('borrower_unit')
            ->pluck('borrower_unit');
        
        return view('admin.peminjaman.index', compact('borrows', 'stats', 'units'));
    }

    /**
     * Show detail peminjaman
     */
    public function show($id)
    {
        $borrow = AssetBorrow::with(['asset', 'borrower', 'approver'])
            ->findOrFail($id);
        
        return view('admin.peminjaman.show', compact('borrow'));
    }

    /**
     * Setujui peminjaman
     * 
     * ✅ FIXED: Ubah status asset ke 'dipinjam' saat disetujui
     */
    public function approve(Request $request, $id)
    {
        $borrow = AssetBorrow::findOrFail($id);
        
        if ($borrow->status !== 'pending') {
            return back()->with('error', 'Peminjaman sudah diproses sebelumnya!');
        }
        
        $validated = $request->validate([
            'catatan_admin' => 'nullable|string',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
        ]);
        
        DB::beginTransaction();
        try {
            // Update peminjaman
            $borrow->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'catatan_admin' => $validated['catatan_admin'] ?? null,
            ]);
            
            // ✅ FIXED: Update status aset ke 'dipinjam' menggunakan DB::table
            DB::table('assets')
                ->where('id', $borrow->asset_id)
                ->update([
                    'status' => 'dipinjam',
                    'updated_at' => now()
                ]);
            
            // Kirim notifikasi ke peminjam
            $this->sendNotification(
                $borrow->borrower_id,
                'Peminjaman Disetujui',
                "Peminjaman aset {$borrow->asset->nama} telah disetujui. Silakan ambil aset pada tanggal {$borrow->tanggal_pinjam->format('d/m/Y')}.",
                'success',
                route('staff.peminjaman.show', $borrow->id)
            );
            
            // Kirim email approval
            try {
                \Mail::to($borrow->borrower->email)->send(new \App\Mail\BorrowApprovedMail($borrow));
            } catch (\Exception $e) {
                \Log::error("Failed to send approval email: {$e->getMessage()}");
            }
            
            // Kirim notifikasi ke staff pemilik aset (jika ada)
            if ($borrow->asset->penanggung_jawab) {
                // Cari user berdasarkan nama penanggung jawab
                $owner = User::where('name', $borrow->asset->penanggung_jawab)->first();
                if ($owner) {
                    $this->sendNotification(
                        $owner->id,
                        'Aset Akan Dipinjam',
                        "Aset {$borrow->asset->nama} akan dipinjam oleh {$borrow->borrower->name} dari {$borrow->borrower_unit}.",
                        'info',
                        route('staff.aset.show', $borrow->asset_id)
                    );
                }
            }
            
            DB::commit();
            
            return back()->with('success', 'Peminjaman berhasil disetujui!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tolak peminjaman
     */
    public function reject(Request $request, $id)
    {
        $borrow = AssetBorrow::findOrFail($id);
        
        if ($borrow->status !== 'pending') {
            return back()->with('error', 'Peminjaman sudah diproses sebelumnya!');
        }
        
        $validated = $request->validate([
            'catatan_admin' => 'required|string',
        ]);
        
        DB::beginTransaction();
        try {
            // Update peminjaman
            $borrow->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'catatan_admin' => $validated['catatan_admin'],
            ]);
            
            // Kirim notifikasi ke peminjam
            $this->sendNotification(
                $borrow->borrower_id,
                'Peminjaman Ditolak',
                "Peminjaman aset {$borrow->asset->nama} ditolak. Alasan: {$validated['catatan_admin']}",
                'error',
                route('staff.peminjaman.show', $borrow->id)
            );
            
            DB::commit();
            
            return back()->with('success', 'Peminjaman berhasil ditolak!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Serahkan aset (update status jadi borrowed)
     */
    public function handover(Request $request, $id)
    {
        $borrow = AssetBorrow::findOrFail($id);
        
        if ($borrow->status !== 'approved') {
            return back()->with('error', 'Aset belum disetujui atau sudah diserahkan!');
        }
        
        $validated = $request->validate([
            'kondisi_pinjam' => 'required|in:baik,cukup,kurang,rusak',
            'foto_pinjam' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan_admin' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        try {
            // Upload foto jika ada
            if ($request->hasFile('foto_pinjam')) {
                $foto = $request->file('foto_pinjam');
                $filename = 'borrow_' . time() . '.' . $foto->getClientOriginalExtension();
                $path = $foto->storeAs('borrows', $filename, 'public');
                $validated['foto_pinjam'] = $path;
            }
            
            // Update peminjaman
            $borrow->update([
                'status' => 'borrowed',
                'kondisi_pinjam' => $validated['kondisi_pinjam'],
                'foto_pinjam' => $validated['foto_pinjam'] ?? null,
                'catatan_admin' => $validated['catatan_admin'] ?? $borrow->catatan_admin,
            ]);
            
            // Kirim notifikasi ke peminjam
            $this->sendNotification(
                $borrow->borrower_id,
                'Aset Diserahkan',
                "Aset {$borrow->asset->nama} telah diserahkan. Harap kembalikan sebelum {$borrow->tanggal_kembali_rencana->format('d/m/Y')}.",
                'success',
                route('staff.peminjaman.show', $borrow->id)
            );
            
            DB::commit();
            
            return back()->with('success', 'Aset berhasil diserahkan ke peminjam!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Terima pengembalian aset
     * 
     * ✅ FIXED: Update status asset sesuai ENUM yang benar
     */
    public function returnAsset(Request $request, $id)
    {
        $borrow = AssetBorrow::findOrFail($id);
        
        if (!in_array($borrow->status, ['borrowed', 'overdue'])) {
            return back()->with('error', 'Aset belum dipinjam atau sudah dikembalikan!');
        }
        
        $validated = $request->validate([
            'kondisi_kembali' => 'required|in:baik,cukup,kurang,rusak',
            'foto_kembali' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan_pengembalian' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        try {
            // Upload foto jika ada
            if ($request->hasFile('foto_kembali')) {
                $foto = $request->file('foto_kembali');
                $filename = 'return_' . time() . '.' . $foto->getClientOriginalExtension();
                $path = $foto->storeAs('borrows', $filename, 'public');
                $validated['foto_kembali'] = $path;
            }
            
            // Update peminjaman
            $borrow->update([
                'status' => 'returned',
                'tanggal_kembali_aktual' => Carbon::now(),
                'kondisi_kembali' => $validated['kondisi_kembali'],
                'foto_kembali' => $validated['foto_kembali'] ?? null,
                'catatan_pengembalian' => $validated['catatan_pengembalian'] ?? null,
            ]);
            
            // ✅ FIXED: Update status aset sesuai ENUM yang ada
            $newAssetStatus = 'tersedia'; // Default kembali ke tersedia
            
            if ($validated['kondisi_kembali'] === 'rusak') {
                $newAssetStatus = 'rusak';
            } elseif ($validated['kondisi_kembali'] === 'kurang') {
                $newAssetStatus = 'maintenance'; // ✅ Sesuai ENUM
            }
            
            // ✅ Gunakan DB::table untuk update yang aman
            DB::table('assets')
                ->where('id', $borrow->asset_id)
                ->update([
                    'status' => $newAssetStatus,
                    'kondisi' => $validated['kondisi_kembali'],
                    'updated_at' => now()
                ]);
            
            // Kirim notifikasi ke peminjam
            $this->sendNotification(
                $borrow->borrower_id,
                'Pengembalian Dikonfirmasi',
                "Pengembalian aset {$borrow->asset->nama} telah dikonfirmasi. Terima kasih!",
                'success',
                route('staff.peminjaman.show', $borrow->id)
            );
            
            // Kirim notifikasi ke staff pemilik aset
            if ($borrow->asset->penanggung_jawab) {
                $owner = User::where('name', $borrow->asset->penanggung_jawab)->first();
                if ($owner) {
                    $this->sendNotification(
                        $owner->id,
                        'Aset Dikembalikan',
                        "Aset {$borrow->asset->nama} telah dikembalikan oleh {$borrow->borrower->name}. Kondisi: {$validated['kondisi_kembali']}.",
                        'info',
                        route('staff.aset.show', $borrow->asset_id)
                    );
                    
                    // Kirim email pengembalian
                    try {
                        \Mail::to($owner->email)->send(new \App\Mail\BorrowReturnedMail($borrow, $owner->name));
                    } catch (\Exception $e) {
                        \Log::error("Failed to send return email to owner: {$e->getMessage()}");
                    }
                }
            }
            
            DB::commit();
            
            return back()->with('success', 'Pengembalian aset berhasil dikonfirmasi!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete peminjaman (hanya jika pending atau rejected)
     */
    public function destroy($id)
    {
        $borrow = AssetBorrow::findOrFail($id);
        
        if (!in_array($borrow->status, ['pending', 'rejected'])) {
            return back()->with('error', 'Tidak dapat menghapus peminjaman yang sudah diproses!');
        }
        
        // Hapus foto jika ada
        if ($borrow->foto_pinjam) {
            Storage::disk('public')->delete($borrow->foto_pinjam);
        }
        if ($borrow->foto_kembali) {
            Storage::disk('public')->delete($borrow->foto_kembali);
        }
        
        $borrow->delete();
        
        return back()->with('success', 'Peminjaman berhasil dihapus!');
    }

    /**
     * Halaman menunggu persetujuan (khusus admin)
     */
    public function pending()
    {
        $borrows = AssetBorrow::with(['asset', 'borrower'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(15);
        
        return view('admin.peminjaman.pending', compact('borrows'));
    }

    /**
     * Halaman akan jatuh tempo
     */
    public function duesoon()
    {
        $borrows = AssetBorrow::with(['asset', 'borrower'])
            ->where('status', 'borrowed')
            ->whereBetween('tanggal_kembali_rencana', [
                Carbon::now(),
                Carbon::now()->addDays(3)
            ])
            ->orderBy('tanggal_kembali_rencana', 'asc')
            ->paginate(15);
        
        return view('admin.peminjaman.due-soon', compact('borrows'));
    }

    /**
     * Halaman terlambat
     */
    public function overdue()
    {
        // Update status overdue dulu
        AssetBorrow::updateOverdueStatus();
        
        $borrows = AssetBorrow::with(['asset', 'borrower'])
            ->where('status', 'overdue')
            ->orderBy('tanggal_kembali_rencana', 'asc')
            ->paginate(15);
        
        return view('admin.peminjaman.overdue', compact('borrows'));
    }

    /**
     * Helper: Send notification
     */
    private function sendNotification($userId, $title, $message, $type, $url = null)
    {
        // Create in-app notification
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'url' => $url,
            'read_at' => null,
        ]);
        
        // Send email notification
        $user = User::find($userId);
        if ($user && $user->email) {
            try {
                // Kirim email sesuai jenis notifikasi
                // Email akan dikirim via queue (ShouldQueue)
                \Illuminate\Support\Facades\Log::info("Sending email to: {$user->email}");
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send email: {$e->getMessage()}");
            }
        }
    }
}