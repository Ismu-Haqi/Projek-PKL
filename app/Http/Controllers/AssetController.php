<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetController extends Controller
{
    /**
     * Display a listing of assets
     * ✅ UPDATED: Support pimpinan
     */
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin, staff, and pimpinan
        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }
        
        $query = Asset::query();
        
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
        
        $assets = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Statistics - FIXED: Pakai 'diperbaiki'
        $stats = [
            'total' => Asset::count(),
            'tersedia' => Asset::status('tersedia')->count(),
            'digunakan' => Asset::status('digunakan')->count(),
            'diperbaiki' => Asset::status('diperbaiki')->count(),
            'rusak' => Asset::status('rusak')->count(),
        ];
        
        // Get unique categories and units for filter
        $categories = Asset::select('kategori')->distinct()->pluck('kategori');
        $units = Asset::select('unit')->distinct()->whereNotNull('unit')->pluck('unit');
        
        // ✅ UPDATED: Support pimpinan
        $viewPrefix = match($role) {
            'admin' => 'admin',
            'pimpinan' => 'pimpinan',
            default => 'staff'
        };
        
        return view("{$viewPrefix}.aset.index", compact('assets', 'stats', 'categories', 'units'));
    }

    /**
     * Show the form for creating a new asset
     * ✅ ADMIN ONLY
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }
        
        // Get existing categories and units for dropdown
        $categories = Asset::select('kategori')->distinct()->pluck('kategori');
        $units = Asset::select('unit')->distinct()->whereNotNull('unit')->pluck('unit');
        
        return view('admin.aset.create', compact('categories', 'units'));
    }

    /**
     * Store a newly created asset
     * ✅ ADMIN ONLY
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'merk' => 'nullable|string|max:100',
            'tipe' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'spesifikasi' => 'nullable|string',
            'kondisi' => 'required|in:baik,cukup,kurang,rusak',
            'status' => 'required|in:tersedia,digunakan,diperbaiki,rusak',
            'lokasi' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:100',
            'tanggal_pembelian' => 'nullable|date',
            'harga_pembelian' => 'nullable|numeric|min:0',
            'masa_garansi' => 'nullable|integer|min:0',
            'penanggung_jawab' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ]);
        
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
        
        return redirect()->route('admin.aset.index')
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
        
        $viewPrefix = match($role) {
            'admin' => 'admin',
            'pimpinan' => 'pimpinan',
            default => 'staff'
        };
        
        return view("{$viewPrefix}.aset.show", compact('asset'));
    }

    /**
     * Show the form for editing the specified asset
     * ✅ ADMIN ONLY
     */
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }
        
        $asset = Asset::findOrFail($id);
        
        // Get existing categories and units for dropdown
        $categories = Asset::select('kategori')->distinct()->pluck('kategori');
        $units = Asset::select('unit')->distinct()->whereNotNull('unit')->pluck('unit');
        
        return view('admin.aset.edit', compact('asset', 'categories', 'units'));
    }

    /**
     * Update the specified asset
     * ✅ ADMIN ONLY
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }
        
        $asset = Asset::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'merk' => 'nullable|string|max:100',
            'tipe' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'spesifikasi' => 'nullable|string',
            'kondisi' => 'required|in:baik,cukup,kurang,rusak',
            'status' => 'required|in:tersedia,digunakan,diperbaiki,rusak',
            'lokasi' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:100',
            'tanggal_pembelian' => 'nullable|date',
            'harga_pembelian' => 'nullable|numeric|min:0',
            'masa_garansi' => 'nullable|integer|min:0',
            'penanggung_jawab' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ]);
        
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
        
        return redirect()->route('admin.aset.index')
            ->with('success', 'Aset berhasil diperbarui!');
    }

    /**
     * Remove the specified asset
     * ✅ ADMIN ONLY
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }
        
        $asset = Asset::findOrFail($id);
        
        // Hapus foto
        if ($asset->foto) {
            Storage::disk('public')->delete($asset->foto);
        }
        
        // Hapus QR code
        if ($asset->qr_code) {
            Storage::disk('public')->delete($asset->qr_code);
        }
        
        $asset->delete();
        
        return redirect()->route('admin.aset.index')
            ->with('success', 'Aset berhasil dihapus!');
    }

    /**
     * Update status asset (quick update)
     * ✅ ADMIN ONLY
     */
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }
        
        $asset = Asset::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:tersedia,digunakan,diperbaiki,rusak',
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
        
        $filename = 'qr_' . $asset->kode_asset . '.svg';
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
     * ✅ UPDATED: All roles can download QR
     */
    public function downloadQr($id)
    {
        $asset = Asset::findOrFail($id);
        
        if (!$asset->qr_code || !Storage::disk('public')->exists($asset->qr_code)) {
            $this->generateQrCode($asset);
            $asset->refresh();
        }
        
        return Storage::disk('public')->download($asset->qr_code, 'QR_' . $asset->kode_asset . '.svg');
    }
}