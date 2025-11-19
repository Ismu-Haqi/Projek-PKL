<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetController extends Controller
{
    /**
     * ✅ Predefined Lokasi, Unit & Kategori
     */
    private function getLokasis()
    {
        return ['Ruang SP', 'Ruang E-Gov', 'Ruang Sekretariat', 'Ruang IKP'];
    }

    private function getUnits()
    {
        return ['SP', 'E-Government', 'Sekretariat', 'IKP'];
    }
    
    private function getKategoris()
    {
        return [
            'Peralatan Elektronik',
            'Perabot Kantor',
            'Kendaraan',
            'Infrastruktur Jaringan',
            'Dokumentasi & Multimedia',
            'Gedung & Bangunan',
            'Tanah / Lahan'
        ];
    }

    /**
     * Display a listing of assets
     * ✅ UPDATED: Support pimpinan + Filter Lokasi
     */
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin, staff, and pimpinan
        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }
        
        $query = Asset::query();
        
        // ✅ Staff hanya bisa lihat aset dari unitnya
        if ($role === 'staff' && Auth::user()->unit) {
            $query->where('unit', Auth::user()->unit);
        }
        
        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->kategori($request->kategori);
        }
        
        // Filter Status
        if ($request->filled('status')) {
            $query->status($request->status);
        }
        
        // Filter Unit
        if ($request->filled('unit')) {
            $query->unit($request->unit);
        }
        
        // Filter Kondisi
        if ($request->filled('kondisi')) {
            $query->kondisi($request->kondisi);
        }
        
        // ✅ Filter Lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }
        
        $assets = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Statistics - ✅ FIXED: Sesuai dengan ENUM baru
        $statsQuery = Asset::query();
        if ($role === 'staff' && Auth::user()->unit) {
            $statsQuery->where('unit', Auth::user()->unit);
        }
        
        $stats = [
            'total' => $statsQuery->count(),
            'tersedia' => (clone $statsQuery)->status('tersedia')->count(),
            'digunakan' => (clone $statsQuery)->status('digunakan')->count(),
            'dipinjam' => (clone $statsQuery)->status('dipinjam')->count(),
            'maintenance' => (clone $statsQuery)->status('maintenance')->count(),
            'rusak' => (clone $statsQuery)->status('rusak')->count(),
        ];
        
        // Get unique categories for filter
        $categories = Asset::select('kategori')->distinct()->pluck('kategori');
        
        // ✅ FIXED: Predefined options
        $lokasis = $this->getLokasis();
        $units = $this->getUnits();
        
        // ✅ UPDATED: Support pimpinan
        $viewPrefix = match($role) {
            'admin' => 'admin',
            'pimpinan' => 'pimpinan',
            default => 'staff'
        };
        
        return view("{$viewPrefix}.aset.index", compact('assets', 'stats', 'categories', 'units', 'lokasis'));
    }

    /**
     * Show the form for creating a new asset
     * ✅ UPDATED: Admin & Staff can create
     */
    public function create()
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin and staff
        if (!in_array($role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized - Admin and Staff only');
        }
        
        // ✅ PENTING: Gunakan method getKategoris()
        $categories = $this->getKategoris();
        
        // ✅ Predefined options
        $lokasis = $this->getLokasis();
        $units = $this->getUnits();
        
        // ✅ Auto-fill Penanggung Jawab dan Unit untuk Staff
        $penanggungJawab = Auth::user()->name;
        
        // ✅ FIXED: Untuk staff, unit harus dari user yang login
        $userUnit = Auth::user()->unit;
        
        $viewPrefix = $role === 'admin' ? 'admin' : 'staff';
        
        return view("{$viewPrefix}.aset.create", compact('categories', 'units', 'lokasis', 'penanggungJawab', 'userUnit'));
    }

    /**
     * Store a newly created asset
     * ✅ UPDATED: Admin & Staff can store
     */
    public function store(Request $request)
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin and staff
        if (!in_array($role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized - Admin and Staff only');
        }
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'merk' => 'nullable|string|max:100',
            'tipe' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'spesifikasi' => 'nullable|string',
            'kondisi' => 'required|in:baik,cukup,kurang,rusak',
            'status' => 'required|in:tersedia,digunakan,dipinjam,maintenance,rusak',
            'lokasi' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:100',
            'tanggal_pembelian' => 'nullable|date',
            'harga_pembelian' => 'nullable|numeric|min:0',
            'masa_garansi' => 'nullable|integer|min:0',
            'penanggung_jawab' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ]);
        
        // ✅ STAFF: Force unit to user's unit
        if ($role === 'staff') {
            $validated['unit'] = Auth::user()->unit;
        }
        
        // ✅ AUTO-FILL: Penanggung jawab
        if (empty($validated['penanggung_jawab'])) {
            $validated['penanggung_jawab'] = Auth::user()->name;
        }
        
        // Generate kode asset
        $validated['kode_asset'] = Asset::generateKodeAsset($validated['kategori']);
        
        // Calculate tanggal garansi berakhir
        if ($request->filled('tanggal_pembelian') && $request->filled('masa_garansi')) {
            $validated['tanggal_garansi_berakhir'] = \Carbon\Carbon::parse($request->tanggal_pembelian)
                ->addMonths($request->masa_garansi);
        }
        
        // Upload foto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = 'asset_' . time() . '.' . $foto->getClientOriginalExtension();
            $path = $foto->storeAs('assets', $filename, 'public');
            $validated['foto'] = $path;
        }
        
        $asset = Asset::create($validated);
        
        // Generate QR Code
        $this->generateQrCode($asset);
        
        $routeName = $role === 'admin' ? 'admin.aset.index' : 'staff.aset.index';
        
        return redirect()->route($routeName)
            ->with('success', 'Aset berhasil ditambahkan!');
    }

    /**
     * Display the specified asset
     * ✅ UPDATED: Support pimpinan
     */
    public function show($id)
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin, staff, and pimpinan
        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }
        
        $asset = Asset::findOrFail($id);
        
        // ✅ Staff hanya bisa lihat aset dari unitnya
        if ($role === 'staff' && Auth::user()->unit && $asset->unit !== Auth::user()->unit) {
            abort(403, 'Unauthorized - Anda hanya bisa melihat aset dari unit Anda');
        }
        
        $viewPrefix = match($role) {
            'admin' => 'admin',
            'pimpinan' => 'pimpinan',
            default => 'staff'
        };
        
        return view("{$viewPrefix}.aset.show", compact('asset'));
    }

    /**
     * Show the form for editing the specified asset
     * ✅ UPDATED: Admin & Staff can edit (staff only their unit)
     */
    public function edit($id)
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin and staff
        if (!in_array($role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized - Admin and Staff only');
        }
        
        $asset = Asset::findOrFail($id);
        
        // ✅ Staff hanya bisa edit aset dari unitnya
        if ($role === 'staff' && Auth::user()->unit && $asset->unit !== Auth::user()->unit) {
            abort(403, 'Unauthorized - Anda hanya bisa mengedit aset dari unit Anda');
        }
        
        // Get existing categories for dropdown
        $categories = Asset::select('kategori')->distinct()->pluck('kategori');
        
        // ✅ Predefined options
        $lokasis = $this->getLokasis();
        $units = $this->getUnits();
        
        $viewPrefix = $role === 'admin' ? 'admin' : 'staff';
        
        return view("{$viewPrefix}.aset.edit", compact('asset', 'categories', 'units', 'lokasis'));
    }

    /**
     * Update the specified asset
     * ✅ UPDATED: Admin & Staff can update
     */
    public function update(Request $request, $id)
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin and staff
        if (!in_array($role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized - Admin and Staff only');
        }
        
        $asset = Asset::findOrFail($id);
        
        // ✅ Staff hanya bisa update aset dari unitnya
        if ($role === 'staff' && Auth::user()->unit && $asset->unit !== Auth::user()->unit) {
            abort(403, 'Unauthorized - Anda hanya bisa mengupdate aset dari unit Anda');
        }
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'merk' => 'nullable|string|max:100',
            'tipe' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'spesifikasi' => 'nullable|string',
            'kondisi' => 'required|in:baik,cukup,kurang,rusak',
            'status' => 'required|in:tersedia,digunakan,dipinjam,maintenance,rusak',
            'lokasi' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:100',
            'tanggal_pembelian' => 'nullable|date',
            'harga_pembelian' => 'nullable|numeric|min:0',
            'masa_garansi' => 'nullable|integer|min:0',
            'penanggung_jawab' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ]);
        
        // ✅ STAFF: Cannot change unit
        if ($role === 'staff') {
            unset($validated['unit']);
        }
        
        // Calculate tanggal garansi berakhir
        if ($request->filled('tanggal_pembelian') && $request->filled('masa_garansi')) {
            $validated['tanggal_garansi_berakhir'] = \Carbon\Carbon::parse($request->tanggal_pembelian)
                ->addMonths($request->masa_garansi);
        }
        
        // Upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($asset->foto) {
                Storage::disk('public')->delete($asset->foto);
            }
            
            $foto = $request->file('foto');
            $filename = 'asset_' . time() . '.' . $foto->getClientOriginalExtension();
            $path = $foto->storeAs('assets', $filename, 'public');
            $validated['foto'] = $path;
        }
        
        $asset->update($validated);
        
        $routeName = $role === 'admin' ? 'admin.aset.index' : 'staff.aset.index';
        
        return redirect()->route($routeName)
            ->with('success', 'Aset berhasil diperbarui!');
    }

    /**
     * Remove the specified asset
     * ✅ UPDATED: Admin & Staff can delete
     */
    public function destroy($id)
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin and staff
        if (!in_array($role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized - Admin and Staff only');
        }
        
        $asset = Asset::findOrFail($id);
        
        // ✅ Staff hanya bisa hapus aset dari unitnya
        if ($role === 'staff' && Auth::user()->unit && $asset->unit !== Auth::user()->unit) {
            abort(403, 'Unauthorized - Anda hanya bisa menghapus aset dari unit Anda');
        }
        
        // Hapus foto
        if ($asset->foto) {
            Storage::disk('public')->delete($asset->foto);
        }
        
        // Hapus QR code
        if ($asset->qr_code) {
            Storage::disk('public')->delete($asset->qr_code);
        }
        
        $asset->delete();
        
        $routeName = $role === 'admin' ? 'admin.aset.index' : 'staff.aset.index';
        
        return redirect()->route($routeName)
            ->with('success', 'Aset berhasil dihapus!');
    }

    /**
     * Update status asset (quick update)
     * ✅ ADMIN ONLY - FIXED: Status ENUM updated
     */
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }
        
        $asset = Asset::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:tersedia,digunakan,dipinjam,maintenance,rusak',
        ]);
        
        $asset->update($validated);
        
        return back()->with('success', 'Status aset berhasil diperbarui!');
    }

    /**
     * Generate QR Code for asset
     */
    private function generateQrCode($asset)
    {
        // Ganti ke route public
        $qrContent = route('aset.public.show', $asset->id);
        
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($qrContent);
        
        // ✅ FIXED: Sanitize filename untuk QR code
        $safeKodeAsset = preg_replace('/[\/\\\:*?"<>|]/', '_', $asset->kode_asset);
        $filename = 'qr_' . $safeKodeAsset . '.svg';
        $path = 'qrcodes/' . $filename;
        
        Storage::disk('public')->put($path, $qrCode);
        
        $asset->update(['qr_code' => $path]);
    }

    /**
     * Public asset detail view (for QR code scanning)
     */
    public function publicShow($id)
    {
        $asset = Asset::findOrFail($id);
        return view('public.aset-detail', compact('asset'));
    }
    
    /**
     * Download QR Code
     * ✅ UPDATED: All roles can download QR + Fixed filename sanitization
     */
    public function downloadQr($id)
    {
        $asset = Asset::findOrFail($id);
        
        if (!$asset->qr_code || !Storage::disk('public')->exists($asset->qr_code)) {
            $this->generateQrCode($asset);
            $asset->refresh();
        }
        
        // ✅ FIXED: Sanitize filename - hapus karakter tidak valid (/, \, :, *, ?, ", <, >, |)
        $safeFilename = 'QR_' . preg_replace('/[\/\\\:*?"<>|]/', '_', $asset->kode_asset) . '.svg';
        
        return Storage::disk('public')->download($asset->qr_code, $safeFilename);
    }
    
    /**
     * ✅ NEW: Return borrowed asset
     * Update status aset saat dikembalikan
     */
    public function returnAsset(Request $request, $borrowId)
    {
        // Validasi role (admin/staff)
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'kondisi_kembali' => 'required|in:baik,cukup,kurang,rusak',
            'keterangan_kembali' => 'nullable|string',
        ]);
        
        // Ambil data peminjaman
        $borrow = DB::table('borrows')->where('id', $borrowId)->first();
        
        if (!$borrow) {
            return back()->with('error', 'Data peminjaman tidak ditemukan!');
        }
        
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
        
        // Update data peminjaman
        DB::table('borrows')
            ->where('id', $borrowId)
            ->update([
                'tanggal_kembali' => now(),
                'kondisi_kembali' => $validated['kondisi_kembali'],
                'keterangan_kembali' => $validated['keterangan_kembali'],
                'status' => 'dikembalikan',
                'updated_at' => now()
            ]);
        
        return back()->with('success', 'Aset berhasil dikembalikan!');
    }
}