<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Category;
use App\Models\User;
use App\Mail\ArchiveUploadedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the archives.
     */
    public function index(Request $request)
    {
        $query = Archive::query();

        // Jika tabel categories ada, gunakan relasi
        if (Schema::hasTable('categories')) {
            $query->with('category');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%");
                  
                if (Schema::hasColumn('archives', 'pengirim')) {
                    $q->orWhere('pengirim', 'like', "%{$search}%");
                }
            });
        }

        // Filter by Category
        if ($request->filled('category') && Schema::hasColumn('archives', 'category_id')) {
            $query->where('category_id', $request->category);
        }

        // Filter by Unit
        if ($request->filled('unit') && Schema::hasColumn('archives', 'unit')) {
            $query->where('unit', $request->unit);
        }

        // Filter by Year
        if ($request->filled('year')) {
            if (Schema::hasColumn('archives', 'tanggal_surat')) {
                $query->whereYear('tanggal_surat', $request->year);
            } else {
                $query->whereYear('tanggal_arsip', $request->year);
            }
        }

        // Sort by latest
        $query->orderBy('created_at', 'desc');

        // Paginate
        $archives = $query->paginate(15);

        // Statistics
        $totalArchives = Archive::count();
        
        $favoritesCount = 0;
        if (Schema::hasColumn('archives', 'is_favorite')) {
            $favoritesCount = Archive::where('is_favorite', true)->count();
        }
        
        $categoriesCount = 0;
        if (Schema::hasTable('categories')) {
            $categoriesCount = Category::count();
        }
        
        $thisMonthCount = Archive::whereMonth('created_at', Carbon::now()->month)
                                 ->whereYear('created_at', Carbon::now()->year)
                                 ->count();

        // Get all categories for filter
        $categories = [];
        if (Schema::hasTable('categories')) {
            $categories = Category::all();
        }

        // List Unit/Bidang Kominfo
        $units = [
            'Sekretariat',
            'IKP',
            'Aptika',
            'Komtel',
            'Statistik',
            'E-Gov'
        ];

        // ✅ UPDATED: Support pimpinan role
        $role = Auth::user()->role;
        $viewPrefix = match($role) {
            'admin' => 'admin',
            'pimpinan' => 'pimpinan',
            default => 'staff'
        };

        return view("{$viewPrefix}.arsip.index", compact(
            'archives',
            'categories',
            'units',
            'totalArchives',
            'favoritesCount',
            'categoriesCount',
            'thisMonthCount'
        ));
    }

    /**
     * Generate nomor surat otomatis
     */
    private function generateNomorSurat($unit)
    {
        $year = date('Y');
        $month = date('m');
        
        // Format: NNN/UNIT/BULAN/TAHUN
        // Contoh: 001/IKP/10/2025
        
        // Hitung jumlah arsip bulan ini untuk unit ini
        $count = Archive::where('unit', $unit)
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->count();
        
        $number = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        return "{$number}/{$unit}/{$month}/{$year}";
    }

    /**
     * Show the form for creating a new archive.
     * ✅ ADMIN ONLY
     */
    public function create()
    {
        // Only admin can create
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Only Admin can create archives');
        }

        $categories = [];
        if (Schema::hasTable('categories')) {
            $categories = Category::all();
        }

        // Generate nomor surat otomatis
        $nomorSurat = $this->generateNomorSurat(Auth::user()->unit ?? 'UMUM');

        return view('admin.arsip.create', compact('categories', 'nomorSurat'));
    }

    /**
     * Store a newly created archive (Multiple Files Support + Email Notification).
     * ✅ ADMIN ONLY
     */
    public function store(Request $request)
    {
        // Only admin can store
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Only Admin can create archives');
        }

        // Validation rules
        $rules = [
            'nomor_surat' => 'required|string|max:255|unique:archives,nomor_surat',
            'judul' => 'required|string|max:255',
            'files' => 'required|array|min:1',
            'files.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ];

        // Add conditional validation
        if (Schema::hasColumn('archives', 'tanggal_surat')) {
            $rules['tanggal_surat'] = 'required|date';
        }
        if (Schema::hasColumn('archives', 'pengirim')) {
            $rules['pengirim'] = 'required|string|max:255';
        }
        if (Schema::hasColumn('archives', 'unit')) {
            $rules['unit'] = 'required|string|max:100';
        }
        if (Schema::hasColumn('archives', 'category_id') && Schema::hasTable('categories')) {
            $rules['category_id'] = 'required|exists:categories,id';
        }

        $validated = $request->validate($rules, [
            'nomor_surat.required' => 'Nomor surat wajib diisi',
            'nomor_surat.unique' => 'Nomor surat sudah ada dalam database',
            'judul.required' => 'Judul surat wajib diisi',
            'files.required' => 'Minimal 1 file wajib diupload',
            'files.*.mimes' => 'Format file tidak didukung',
            'files.*.max' => 'Ukuran file maksimal 10MB'
        ]);

        try {
            $uploadedFiles = [];
            
            // Upload multiple files
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    $filePath = $file->storeAs('archives', $fileName, 'public');
                    
                    $uploadedFiles[] = [
                        'path' => $filePath,
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'type' => $file->getClientMimeType(),
                    ];
                }
            }

            // Simpan arsip pertama dengan file pertama
            $firstFile = $uploadedFiles[0];
            $validated['file_path'] = $firstFile['path'];
            
            if (Schema::hasColumn('archives', 'file_name')) {
                $validated['file_name'] = $firstFile['name'];
            }
            if (Schema::hasColumn('archives', 'file_size')) {
                $validated['file_size'] = $firstFile['size'];
            }
            if (Schema::hasColumn('archives', 'file_type')) {
                $validated['file_type'] = $firstFile['type'];
            }

            // Add required fields
            $validated['user_id'] = Auth::id();
            
            // Set tanggal_arsip
            if (isset($validated['tanggal_surat'])) {
                $validated['tanggal_arsip'] = $validated['tanggal_surat'];
            } else {
                $validated['tanggal_arsip'] = now();
            }
            
            // Get jenis_arsip from category
            if (isset($validated['category_id']) && Schema::hasTable('categories')) {
                $category = Category::find($validated['category_id']);
                $validated['jenis_arsip'] = $category ? $category->name : 'Lain-lain';
            } else {
                $validated['jenis_arsip'] = 'Umum';
            }
            
            // Set default priority
            if (Schema::hasColumn('archives', 'priority') && !isset($validated['priority'])) {
                $validated['priority'] = 'Biasa';
            }

            // Hapus key 'files' dari validated agar tidak error saat create
            unset($validated['files']);

            // Create archive
            $archive = Archive::create($validated);

            // Jika ada file tambahan (lebih dari 1), buat arsip terpisah dengan nomor surat yang sama + suffix
            if (count($uploadedFiles) > 1) {
                for ($i = 1; $i < count($uploadedFiles); $i++) {
                    $additionalData = $validated;
                    $additionalData['nomor_surat'] = $validated['nomor_surat'] . '-' . ($i + 1);
                    $additionalData['file_path'] = $uploadedFiles[$i]['path'];
                    
                    if (Schema::hasColumn('archives', 'file_name')) {
                        $additionalData['file_name'] = $uploadedFiles[$i]['name'];
                    }
                    if (Schema::hasColumn('archives', 'file_size')) {
                        $additionalData['file_size'] = $uploadedFiles[$i]['size'];
                    }
                    if (Schema::hasColumn('archives', 'file_type')) {
                        $additionalData['file_type'] = $uploadedFiles[$i]['type'];
                    }
                    
                    Archive::create($additionalData);
                }
            }

            // ✅ SEND EMAIL NOTIFICATION TO ALL ADMINS
            try {
                $admins = User::where('role', 'admin')
                             ->where('id', '!=', Auth::id()) // Exclude uploader
                             ->whereNotNull('email')
                             ->get();
                
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new ArchiveUploadedMail($archive, $admin->name));
                }
                
                Log::info('Archive upload email notifications sent', [
                    'archive_id' => $archive->id,
                    'recipients_count' => $admins->count()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send archive upload email', [
                    'archive_id' => $archive->id,
                    'error' => $e->getMessage()
                ]);
                
                // Email gagal, tapi arsip tetap tersimpan
            }

            // ✅ PESAN SUKSES DENGAN SWEETALERT2
            $fileCount = count($uploadedFiles);
            $successMessage = $fileCount > 1 
                ? "Data arsip berhasil disimpan dengan {$fileCount} file ke sistem Diskominfo Batola!" 
                : "Data arsip \"{$validated['judul']}\" berhasil disimpan ke sistem Diskominfo Batola!";

            return redirect()->route('admin.arsip.index')
                           ->with('success', $successMessage);
        } catch (\Exception $e) {
            // ✅ PESAN ERROR DENGAN SWEETALERT2
            return redirect()->back()
                           ->with('error', 'Gagal menyimpan arsip ke sistem. Error: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified archive (Halaman Detail).
     * ✅ UPDATED: Support pimpinan
     */
    public function show($id)
    {
        $archive = Archive::findOrFail($id);
        
        $role = Auth::user()->role;
        $viewPrefix = match($role) {
            'admin' => 'admin',
            'pimpinan' => 'pimpinan',
            default => 'staff'
        };
        
        return view("{$viewPrefix}.arsip.show", compact('archive'));
    }

    /**
     * Show the form for editing the specified resource.
     * ✅ ADMIN ONLY
     */
    public function edit($id)
    {
        // Only admin can edit
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Only Admin can edit archives');
        }

        $archive = Archive::findOrFail($id);
        
        $categories = [];
        if (Schema::hasTable('categories')) {
            $categories = Category::all();
        }

        return view('admin.arsip.edit', compact('archive', 'categories'));
    }

    /**
     * Update the specified archive (FIXED - File tidak terhapus jika tidak upload baru).
     * ✅ ADMIN ONLY
     */
    public function update(Request $request, $id)
    {
        // Only admin can update
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Only Admin can update archives');
        }

        $archive = Archive::findOrFail($id);

        $rules = [
            'nomor_surat' => 'required|string|max:255|unique:archives,nomor_surat,' . $id,
            'judul' => 'required|string|max:255',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ];

        // Add conditional validation
        if (Schema::hasColumn('archives', 'tanggal_surat')) {
            $rules['tanggal_surat'] = 'required|date';
        }
        if (Schema::hasColumn('archives', 'pengirim')) {
            $rules['pengirim'] = 'required|string|max:255';
        }
        if (Schema::hasColumn('archives', 'unit')) {
            $rules['unit'] = 'required|string|max:100';
        }
        if (Schema::hasColumn('archives', 'category_id')) {
            $rules['category_id'] = 'required|exists:categories,id';
        }

        $validated = $request->validate($rules);

        try {
            // Upload new files HANYA jika ada file baru yang diupload
            if ($request->hasFile('files') && count($request->file('files')) > 0) {
                // Delete old file
                if ($archive->file_path && Storage::disk('public')->exists($archive->file_path)) {
                    Storage::disk('public')->delete($archive->file_path);
                }

                // Upload first file to replace old one
                $file = $request->file('files')[0];
                $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $filePath = $file->storeAs('archives', $fileName, 'public');
                
                $validated['file_path'] = $filePath;
                
                if (Schema::hasColumn('archives', 'file_name')) {
                    $validated['file_name'] = $file->getClientOriginalName();
                }
                if (Schema::hasColumn('archives', 'file_size')) {
                    $validated['file_size'] = $file->getSize();
                }
                if (Schema::hasColumn('archives', 'file_type')) {
                    $validated['file_type'] = $file->getClientMimeType();
                }
            }

            // Update tanggal_arsip
            if (isset($validated['tanggal_surat'])) {
                $validated['tanggal_arsip'] = $validated['tanggal_surat'];
            }

            // Update jenis_arsip from category
            if (isset($validated['category_id'])) {
                $category = Category::find($validated['category_id']);
                $validated['jenis_arsip'] = $category ? $category->name : $archive->jenis_arsip;
            }

            // Hapus key 'files' dari validated agar tidak error saat update
            unset($validated['files']);

            // UPDATE archive
            $archive->update($validated);

            // ✅ PESAN SUKSES UPDATE
            return redirect()->route('admin.arsip.show', $archive->id)
                           ->with('success', 'Data arsip "' . $archive->judul . '" berhasil diperbarui dalam sistem!');
        } catch (\Exception $e) {
            // ✅ PESAN ERROR UPDATE
            return redirect()->back()
                           ->with('error', 'Gagal memperbarui data arsip. Error: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified archive.
     * ✅ ADMIN ONLY
     */
    public function destroy($id)
    {
        // Only admin can delete
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Only Admin can delete archives');
        }

        try {
            $archive = Archive::findOrFail($id);
            $judulArsip = $archive->judul;

            // Delete file from storage
            if ($archive->file_path && Storage::disk('public')->exists($archive->file_path)) {
                Storage::disk('public')->delete($archive->file_path);
            }

            $archive->delete();

            // ✅ PESAN SUKSES DELETE
            return redirect()->route('admin.arsip.index')
                           ->with('success', 'Arsip "' . $judulArsip . '" berhasil dihapus dari sistem Diskominfo Batola!');
        } catch (\Exception $e) {
            // ✅ PESAN ERROR DELETE
            return redirect()->back()
                           ->with('error', 'Gagal menghapus arsip dari sistem. Error: ' . $e->getMessage());
        }
    }

    /**
     * Toggle favorite status (FIXED - Return Redirect).
     * ✅ UPDATED: All roles can favorite
     */
    public function toggleFavorite($id)
    {
        try {
            $archive = Archive::findOrFail($id);
            
            if (Schema::hasColumn('archives', 'is_favorite')) {
                $archive->is_favorite = !$archive->is_favorite;
                $archive->save();

                // ✅ PESAN TOGGLE FAVORITE
                $message = $archive->is_favorite 
                    ? 'Arsip "' . $archive->judul . '" berhasil ditambahkan ke favorit!' 
                    : 'Arsip "' . $archive->judul . '" berhasil dihapus dari favorit!';

                return redirect()->back()->with('success', $message);
            }

            return redirect()->back()->with('warning', 'Fitur favorit tidak tersedia dalam sistem saat ini.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status favorit. Silakan coba lagi.');
        }
    }

    /**
     * Display favorite archives.
     * ✅ UPDATED: Support pimpinan
     */
    public function favorit(Request $request)
    {
        $query = Archive::query();
        
        if (Schema::hasColumn('archives', 'is_favorite')) {
            $query->where('is_favorite', true);
        }

        if (Schema::hasTable('categories')) {
            $query->with('category');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%");
            });
        }

        $archives = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $categories = [];
        if (Schema::hasTable('categories')) {
            $categories = Category::all();
        }

        $units = [
            'Sekretariat',
            'IKP',
            'Aptika',
            'Komtel',
            'Statistik',
            'E-Gov'
        ];

        $role = Auth::user()->role;
        $viewPrefix = match($role) {
            'admin' => 'admin',
            'pimpinan' => 'pimpinan',
            default => 'staff'
        };

        return view("{$viewPrefix}.arsip.favorit", compact('archives', 'categories', 'units'));
    }

    /**
     * Preview archive file in browser (for PDF/Images).
     * ✅ UPDATED: All roles can preview
     */
    public function preview($id)
    {
        try {
            $archive = Archive::findOrFail($id);

            if (!$archive->file_path) {
                return redirect()->back()->with('error', 'File arsip tidak ditemukan dalam sistem.');
            }

            $fullPath = storage_path('app/public/' . $archive->file_path);
            
            if (!file_exists($fullPath)) {
                return redirect()->back()->with('error', 'File arsip tidak ditemukan di server Diskominfo.');
            }

            $mimeType = mime_content_type($fullPath);
            $fileName = $archive->file_name ?? basename($archive->file_path);

            // Return file untuk preview di browser (inline)
            return response()->file($fullPath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'X-Content-Type-Options' => 'nosniff',
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuka file. Error: ' . $e->getMessage());
        }
    }

    /**
     * Download archive file (FIXED - Force download).
     * ✅ UPDATED: All roles can download
     */
    public function download($id)
    {
        try {
            $archive = Archive::findOrFail($id);

            if (!$archive->file_path) {
                return redirect()->back()->with('error', 'Path file tidak ditemukan dalam sistem.');
            }

            $fullPath = storage_path('app/public/' . $archive->file_path);
            
            if (!file_exists($fullPath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan di server Diskominfo.');
            }

            $downloadName = $archive->file_name ?? basename($archive->file_path);
            
            // Force download (attachment)
            return response()->download($fullPath, $downloadName);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh file. Error: ' . $e->getMessage());
        }
    }
}