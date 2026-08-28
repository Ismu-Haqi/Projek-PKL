<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetController extends Controller
{
    /**
     * ✅ FIXED: Display the specified asset
     * Perbaikan authorization untuk staff
     */
public function show($id)
{
    $role = Auth::user()->role;
    
    if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
        abort(403, 'Unauthorized');
    }
    
    $asset = Asset::findOrFail($id);
    
    // ✅ Regenerate QR Code jika belum ada atau file hilang
    if (!$asset->qr_code || !Storage::disk('public')->exists($asset->qr_code)) {
        $this->generateQrCode($asset);
        $asset->refresh(); // Reload asset data setelah generate QR
    }
    
    // Authorization logic
    $canEdit = false;
    $canDelete = false;
    
    if ($role === 'admin') {
        $canEdit = true;
        $canDelete = true;
    } elseif ($role === 'pimpinan') {
        $canEdit = true;
        $canDelete = false;
    } elseif ($role === 'staff') {
        $userUnit = Auth::user()->unit;
        
        if ($userUnit && $asset->unit && $asset->unit === $userUnit) {
            $canEdit = true;
            $canDelete = true;
        }
    }
    
    $viewPrefix = match($role) {
        'admin' => 'admin',
        'pimpinan' => 'pimpinan',
        default => 'staff'
    };
    
    return view("{$viewPrefix}.aset.show", compact('asset', 'canEdit', 'canDelete'));
}

    /**
     * ✅ FIXED: Display a listing of assets
     * Perbaikan filter untuk staff
     */
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin, staff, and pimpinan
        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }
        
        $query = Asset::query();
        
        // ✅ FIXED: Staff filter - Hanya apply jika user punya unit DAN unit tidak kosong
        if ($role === 'staff') {
            $userUnit = Auth::user()->unit;
            
            if ($userUnit) {
                // Filter aset: tampilkan yang unit-nya sesuai ATAU yang unit-nya kosong/null
                $query->where(function($q) use ($userUnit) {
                    $q->where('unit', $userUnit)
                      ->orWhereNull('unit')
                      ->orWhere('unit', '');
                });
            }
            // Jika user tidak punya unit, tampilkan semua
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
        
        // Filter Lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }
        
        $assets = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // ✅ FIXED: Statistics - Sesuai dengan filter yang sama
        $statsQuery = Asset::query();
        if ($role === 'staff') {
            $userUnit = Auth::user()->unit;
            if ($userUnit) {
                $statsQuery->where(function($q) use ($userUnit) {
                    $q->where('unit', $userUnit)
                      ->orWhereNull('unit')
                      ->orWhere('unit', '');
                });
            }
        }
        
        $stats = [
            'total' => $statsQuery->count(),
            'tersedia' => (clone $statsQuery)->where('status', 'tersedia')->count(),
            'digunakan' => (clone $statsQuery)->where('status', 'digunakan')->count(),
            'dipinjam' => (clone $statsQuery)->where('status', 'dipinjam')->count(),
            'maintenance' => (clone $statsQuery)->where('status', 'maintenance')->count(),
            'rusak' => (clone $statsQuery)->where('status', 'rusak')->count(),
        ];
        
        // Get unique categories for filter
        $categories = Asset::select('kategori')->distinct()->pluck('kategori');
        
        // Predefined options
        $lokasis = $this->getLokasis();
        $units = $this->getUnits();
        
        $viewPrefix = match($role) {
            'admin' => 'admin',
            'pimpinan' => 'pimpinan',
            default => 'staff'
        };
        
        return view("{$viewPrefix}.aset.index", compact('assets', 'stats', 'categories', 'units', 'lokasis'));
    }

    /**
     * ✅ FIXED: Show the form for editing the specified asset
     */
    public function edit($id)
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin and staff
        if (!in_array($role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized - Admin and Staff only');
        }
        
        $asset = Asset::findOrFail($id);
        
        // ✅ FIXED: Staff authorization dengan null check
        if ($role === 'staff') {
            $userUnit = Auth::user()->unit;
            
            if ($userUnit && $asset->unit && $asset->unit !== $userUnit) {
                abort(403, 'Unauthorized - Anda hanya bisa mengedit aset dari unit Anda');
            }
        }
        
        // Get existing categories for dropdown
        $categories = Asset::select('kategori')->distinct()->pluck('kategori');
        
        // Predefined options
        $lokasis = $this->getLokasis();
        $units = $this->getUnits();
        
        $viewPrefix = $role === 'admin' ? 'admin' : 'staff';
        
        return view("{$viewPrefix}.aset.edit", compact('asset', 'categories', 'units', 'lokasis'));
    }

    /**
     * ✅ FIXED: Update the specified asset
     */
    public function update(Request $request, $id)
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin and staff
        if (!in_array($role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized - Admin and Staff only');
        }
        
        $asset = Asset::findOrFail($id);
        
        // ✅ FIXED: Staff authorization dengan null check
        if ($role === 'staff') {
            $userUnit = Auth::user()->unit;
            
            if ($userUnit && $asset->unit && $asset->unit !== $userUnit) {
                abort(403, 'Unauthorized - Anda hanya bisa mengupdate aset dari unit Anda');
            }
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
            'harga_pembelian' => 'nullable|numeric|min:0',
            'nilai_residu' => 'nullable|numeric|min:0',      // <--- TAMBAHKAN INI
            'umur_ekonomis' => 'nullable|integer|min:0',     // <--- TAMBAHKAN INI
            'masa_garansi' => 'nullable|integer|min:0',
            'penanggung_jawab' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
            // Jadwal perawatan
            'jadwal_perawatan_selanjutnya' => 'nullable|date',
            'jenis_perawatan'              => 'nullable|string|max:255',
            'terakhir_dirawat'             => 'nullable|date',
            'interval_perawatan_hari'      => 'nullable|integer|min:1',
            'catatan_perawatan'            => 'nullable|string',
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
     * ✅ FIXED: Remove the specified asset
     */
    public function destroy($id)
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin and staff
        if (!in_array($role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized - Admin and Staff only');
        }
        
        $asset = Asset::findOrFail($id);
        
        // ✅ FIXED: Staff authorization dengan null check
        if ($role === 'staff') {
            $userUnit = Auth::user()->unit;
            
            if ($userUnit && $asset->unit && $asset->unit !== $userUnit) {
                abort(403, 'Unauthorized - Anda hanya bisa menghapus aset dari unit Anda');
            }
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

    // ===== METHODS LAINNYA TETAP SAMA =====
    
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

    public function create()
    {
        $role = Auth::user()->role;
        
        if (!in_array($role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized - Admin and Staff only');
        }
        
        $categories = $this->getKategoris();
        $lokasis = $this->getLokasis();
        $units = $this->getUnits();
        $penanggungJawab = Auth::user()->name;
        $userUnit = Auth::user()->unit;
        
        $viewPrefix = $role === 'admin' ? 'admin' : 'staff';
        
        return view("{$viewPrefix}.aset.create", compact('categories', 'units', 'lokasis', 'penanggungJawab', 'userUnit'));
    }

    public function store(Request $request)
    {
        $role = Auth::user()->role;
        
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
            'harga_pembelian' => 'nullable|numeric|min:0',
            'nilai_residu' => 'nullable|numeric|min:0',      // <--- TAMBAHKAN INI
            'umur_ekonomis' => 'nullable|integer|min:0',     // <--- TAMBAHKAN INI
            'masa_garansi' => 'nullable|integer|min:0',
            'penanggung_jawab' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ]);
        
        if ($role === 'staff') {
            $validated['unit'] = Auth::user()->unit;
        }
        
        if (empty($validated['penanggung_jawab'])) {
            $validated['penanggung_jawab'] = Auth::user()->name;
        }
        
        $validated['kode_asset'] = Asset::generateKodeAsset($validated['kategori']);
        
        if ($request->filled('tanggal_pembelian') && $request->filled('masa_garansi')) {
            $validated['tanggal_garansi_berakhir'] = \Carbon\Carbon::parse($request->tanggal_pembelian)
                ->addMonths($request->masa_garansi);
        }
        
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = 'asset_' . time() . '.' . $foto->getClientOriginalExtension();
            $path = $foto->storeAs('assets', $filename, 'public');
            $validated['foto'] = $path;
        }
        
        $asset = Asset::create($validated);
        $this->generateQrCode($asset);
        
        $routeName = $role === 'admin' ? 'admin.aset.index' : 'staff.aset.index';
        
        return redirect()->route($routeName)
            ->with('success', 'Aset berhasil ditambahkan!');
    }

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

    private function generateQrCode($asset)
    {
        $qrContent = route('aset.public.show', $asset->id);
        
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($qrContent);
        
        $safeKodeAsset = preg_replace('/[\/\\\:*?"<>|]/', '_', $asset->kode_asset);
        $filename = 'qr_' . $safeKodeAsset . '.svg';
        $path = 'qrcodes/' . $filename;
        
        Storage::disk('public')->put($path, $qrCode);
        
        $asset->update(['qr_code' => $path]);
    }

    public function publicShow($id)
    {
        $asset = Asset::findOrFail($id);
        return view('public.aset-detail', compact('asset'));
    }
    
    public function downloadQr($id)
    {
        $asset = Asset::findOrFail($id);
        
        if (!$asset->qr_code || !Storage::disk('public')->exists($asset->qr_code)) {
            $this->generateQrCode($asset);
            $asset->refresh();
        }
        
        $safeFilename = 'QR_' . preg_replace('/[\/\\\:*?"<>|]/', '_', $asset->kode_asset) . '.svg';
        
        return Storage::disk('public')->download($asset->qr_code, $safeFilename);
    }
    
    // ══════════════════════════════════════════════════════
    // ✅ TAMBAHAN BARU (Poin 6 - Scan QR Code pakai kamera HP)
    // ══════════════════════════════════════════════════════

    /**
     * Halaman scan QR Code aset memakai kamera HP langsung dari browser.
     */
    public function scanPage()
    {
        $role = Auth::user()->role;

        if (!in_array($role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }

        $viewPrefix = $role === 'admin' ? 'admin' : 'staff';

        return view("{$viewPrefix}.aset.scan");
    }

    /**
     * Dipanggil via AJAX setelah kamera berhasil membaca QR Code.
     * Menerima ID aset (diambil dari URL hasil scan) dan mengembalikan
     * data aset + status peminjaman aktif dalam bentuk JSON.
     */
    public function scanLookup(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['kode' => 'required|string']);

        // Hasil scan bisa berupa URL lengkap (mis. /aset/view/12) atau angka ID saja
        $kode = trim($request->kode);
        $id   = null;

        if (preg_match('/\\/aset\\/view\\/(\\d+)/', $kode, $m)) {
            $id = $m[1];
        } elseif (ctype_digit($kode)) {
            $id = $kode;
        }

        if (!$id) {
            return response()->json([
                'status'  => false,
                'message' => 'QR Code tidak dikenali. Pastikan yang di-scan adalah QR Code aset dari sistem GANDARIA.',
            ], 404);
        }

        $asset = Asset::with(['activeBorrow.borrower', 'latestCheck'])->find($id);

        if (!$asset) {
            return response()->json([
                'status'  => false,
                'message' => "Aset dengan ID {$id} tidak ditemukan.",
            ], 404);
        }

        $activeBorrow = $asset->activeBorrow;

        return response()->json([
            'status' => true,
            'asset'  => [
                'id'             => $asset->id,
                'kode_asset'     => $asset->kode_asset,
                'nama'           => $asset->nama,
                'kategori'       => $asset->kategori,
                'merk'           => $asset->merk,
                'tipe'           => $asset->tipe,
                'lokasi'         => $asset->lokasi,
                'unit'           => $asset->unit,
                'status'         => $asset->status,
                'kondisi'        => $asset->kondisi,
                'foto_url'       => $asset->foto ? asset('storage/' . $asset->foto) : null,
            ],
            'peminjaman' => $activeBorrow ? [
                'kode_peminjaman' => $activeBorrow->kode_peminjaman,
                'peminjam'        => $activeBorrow->borrower->name ?? '-',
                'unit'            => $activeBorrow->borrower_unit,
                'tanggal_pinjam'  => optional($activeBorrow->tanggal_pinjam)->format('d/m/Y'),
                'rencana_kembali' => optional($activeBorrow->tanggal_kembali_rencana)->format('d/m/Y'),
                'status'          => $activeBorrow->status,
            ] : null,
            'cek_terakhir' => $asset->latestCheck ? [
                'oleh'      => $asset->latestCheck->checker->name ?? '-',
                'tanggal'   => $asset->latestCheck->checked_at->format('d/m/Y H:i'),
                'kondisi'   => $asset->latestCheck->kondisi_saat_cek,
            ] : null,
        ]);
    }

    /**
     * Simpan hasil pengecekan fisik dari halaman scan QR.
     */
    public function scanSave(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'asset_id'         => 'required|exists:assets,id',
            'kondisi_saat_cek' => 'required|in:baik,cukup,kurang,rusak',
            'lokasi_saat_cek'  => 'nullable|string|max:255',
            'catatan'          => 'nullable|string',
        ]);

        $asset = Asset::findOrFail($validated['asset_id']);
        $kondisiBerubah = $asset->kondisi !== $validated['kondisi_saat_cek'];

        AssetCheck::create([
            'asset_id'         => $asset->id,
            'checked_by'       => Auth::id(),
            'kondisi_saat_cek' => $validated['kondisi_saat_cek'],
            'lokasi_saat_cek'  => $validated['lokasi_saat_cek'] ?? $asset->lokasi,
            'catatan'          => $validated['catatan'] ?? null,
            'kondisi_berubah'  => $kondisiBerubah,
            'checked_at'       => now(),
        ]);

        // Sinkronkan kondisi terbaru ke data aset
        $asset->update([
            'kondisi' => $validated['kondisi_saat_cek'],
            'status'  => $validated['kondisi_saat_cek'] === 'rusak' && $asset->status !== 'digunakan'
                         ? 'rusak' : $asset->status,
        ]);

        return response()->json([
            'status'  => true,
            'message' => $kondisiBerubah
                ? "✅ Cek fisik tersimpan. Kondisi diperbarui dari sebelumnya."
                : '✅ Cek fisik tersimpan. Kondisi sesuai catatan sebelumnya.',
        ]);
    }

    public function returnAsset(Request $request, $borrowId)
    {
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'kondisi_kembali' => 'required|in:baik,cukup,kurang,rusak',
            'keterangan_kembali' => 'nullable|string',
        ]);
        
        $borrow = DB::table('borrows')->where('id', $borrowId)->first();
        
        if (!$borrow) {
            return back()->with('error', 'Data peminjaman tidak ditemukan!');
        }
        
        $newAssetStatus = 'tersedia';

        if ($validated['kondisi_kembali'] === 'rusak') {
            $newAssetStatus = 'rusak';
        } elseif ($validated['kondisi_kembali'] === 'kurang') {
            $newAssetStatus = 'maintenance';
        }

        DB::table('assets')
            ->where('id', $borrow->asset_id)
            ->update([
                'status' => $newAssetStatus,
                'kondisi' => $validated['kondisi_kembali'],
                'updated_at' => now()
            ]);
        
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