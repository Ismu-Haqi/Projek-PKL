<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetBorrow;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssetBorrowController extends Controller
{
    /**
     * Display listing peminjaman saya
     */
    public function index(Request $request)
    {
        $query = AssetBorrow::with(['asset', 'approver'])
            ->where('borrower_id', Auth::id())
            ->orderBy('created_at', 'desc');
        
        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $borrows = $query->paginate(15);
        
        // Statistik untuk card - FIXED: Lengkap semua key
        $stats = [
            'pending' => AssetBorrow::where('borrower_id', Auth::id())->where('status', 'pending')->count(),
            'approved' => AssetBorrow::where('borrower_id', Auth::id())->where('status', 'approved')->count(),
            'borrowed' => AssetBorrow::where('borrower_id', Auth::id())->where('status', 'borrowed')->count(),
            'overdue' => AssetBorrow::where('borrower_id', Auth::id())->where('status', 'overdue')->count(),
            'returned' => AssetBorrow::where('borrower_id', Auth::id())->where('status', 'returned')->count(),
            'rejected' => AssetBorrow::where('borrower_id', Auth::id())->where('status', 'rejected')->count(),
        ];
        
        return view('staff.peminjaman.index', compact('borrows', 'stats'));
    }

    /**
     * Show form pengajuan peminjaman
     */
    public function create(Request $request)
    {
        // Bisa dari parameter asset_id
        $assetId = $request->get('asset_id');
        $asset = null;
        
        if ($assetId) {
            $asset = Asset::findOrFail($assetId);
            
            // Validasi: Tidak bisa pinjam aset sendiri
            if ($asset->unit === Auth::user()->unit) {
                return redirect()->route('staff.peminjaman.browse')
                    ->with('error', 'Tidak dapat meminjam aset dari unit sendiri!');
            }
            
            // Validasi: Aset harus bisa dipinjam
            if (!$asset->canBeBorrowed()) {
                return redirect()->route('staff.peminjaman.browse')
                    ->with('error', 'Aset tidak tersedia untuk dipinjam!');
            }
        }
        
        // Get daftar aset yang bisa dipinjam (bukan dari unit sendiri)
        $assets = Asset::availableForBorrow()
            ->where('unit', '!=', Auth::user()->unit)
            ->get();
        
        return view('staff.peminjaman.create', compact('assets', 'asset'));
    }

    /**
     * Store pengajuan peminjaman
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'tanggal_kembali_rencana' => 'required|date|after:today',
            'keperluan' => 'required|string|max:500',
            'catatan_peminjam' => 'nullable|string|max:500',
        ]);
        
        $asset = Asset::findOrFail($validated['asset_id']);
        
        // Validasi: Tidak bisa pinjam aset sendiri
        if ($asset->unit === Auth::user()->unit) {
            return back()->with('error', 'Tidak dapat meminjam aset dari unit sendiri!');
        }
        
        // Validasi: Aset harus bisa dipinjam
        if (!$asset->canBeBorrowed()) {
            return back()->with('error', 'Aset tidak tersedia untuk dipinjam!');
        }
        
        DB::beginTransaction();
        try {
            // Buat peminjaman
            $borrow = AssetBorrow::create([
                'kode_peminjaman' => AssetBorrow::generateKodePeminjaman(),
                'asset_id' => $validated['asset_id'],
                'borrower_id' => Auth::id(),
                'borrower_unit' => Auth::user()->unit,
                'tanggal_pengajuan' => Carbon::now(),
                'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
                'keperluan' => $validated['keperluan'],
                'catatan_peminjam' => $validated['catatan_peminjam'] ?? null,
                'status' => 'pending',
            ]);
            
            // Kirim notifikasi ke admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $this->sendNotification(
                    $admin->id,
                    'Pengajuan Peminjaman Baru',
                    Auth::user()->name . " mengajukan peminjaman aset {$asset->nama} dari unit {$asset->unit}.",
                    'info',
                    route('admin.peminjaman.show', $borrow->id)
                );
                
                // Kirim email
                try {
                    \Mail::to($admin->email)->send(new \App\Mail\BorrowRequestedMail($borrow, $admin->name));
                } catch (\Exception $e) {
                    \Log::error("Failed to send email to admin: {$e->getMessage()}");
                }
            }
            
            // Kirim notifikasi ke staff pemilik aset (jika ada)
            if ($asset->penanggung_jawab) {
                $owner = User::where('name', $asset->penanggung_jawab)->first();
                if ($owner && $owner->id !== Auth::id()) {
                    $this->sendNotification(
                        $owner->id,
                        'Pengajuan Peminjaman Aset',
                        Auth::user()->name . " dari {$borrow->borrower_unit} mengajukan peminjaman aset {$asset->nama}.",
                        'info',
                        route('staff.aset.show', $asset->id)
                    );
                }
            }
            
            DB::commit();
            
            return redirect()->route('staff.peminjaman.index')
                ->with('success', 'Pengajuan peminjaman berhasil dikirim.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show detail peminjaman
     */
    public function show($id)
    {
        $borrow = AssetBorrow::with(['asset', 'approver'])
            ->where('borrower_id', Auth::id())
            ->findOrFail($id);
        
        return view('staff.peminjaman.show', compact('borrow'));
    }

    /**
     * Browse aset yang bisa dipinjam
     */
    public function browse(Request $request)
    {
        $query = Asset::availableForBorrow()
            ->where('unit', '!=', Auth::user()->unit);
        
        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Filter kategori
        if ($request->filled('kategori')) {
            $query->kategori($request->kategori);
        }
        
        // Filter unit
        if ($request->filled('unit')) {
            $query->unit($request->unit);
        }
        
        $assets = $query->paginate(12);
        
        // Get categories & units untuk filter
        $categories = Asset::select('kategori')->distinct()->pluck('kategori');
        $units = Asset::select('unit')
            ->distinct()
            ->whereNotNull('unit')
            ->where('unit', '!=', Auth::user()->unit)
            ->pluck('unit');
        
        return view('staff.peminjaman.browse', compact('assets', 'categories', 'units'));
    }

    /**
     * Cancel pengajuan (hanya jika pending)
     */
    public function destroy($id)
    {
        $borrow = AssetBorrow::where('borrower_id', Auth::id())
            ->findOrFail($id);
        
        if (!in_array($borrow->status, ['pending', 'rejected'])) {
            return back()->with('error', 'Pengajuan sudah diproses, tidak dapat dibatalkan!');
        }
        
        $borrow->delete();
        
        return redirect()->route('staff.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }

    /**
     * Helper: Send notification
     */
    private function sendNotification($userId, $title, $message, $type, $url = null)
    {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'url' => $url,
            'read' => false,
        ]);
    }
}