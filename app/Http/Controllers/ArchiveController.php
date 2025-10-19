<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
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

        // Determine view prefix based on role
        $viewPrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';

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
     */
    public function create()
    {
        $categories = [];
        if (Schema::hasTable('categories')) {
            $categories = Category::all();
        }

        // Generate nomor surat otomatis
        $nomorSurat = $this->generateNomorSurat(Auth::user()->unit ?? 'UMUM');

        return view('admin.arsip.create', compact('categories', 'nomorSurat'));
    }

    /**
     * Store a newly created archive (Multiple Files Support).
     */
    public function store(Request $request)
    {
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

            $routePrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';

            return redirect()->route("{$routePrefix}.arsip.index")
                           ->with('success', count($uploadedFiles) . ' arsip berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menyimpan arsip: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified archive (Halaman Detail).
     */
    public function show($id)
    {
        $archive = Archive::findOrFail($id);
        
        $viewPrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';
        return view("{$viewPrefix}.arsip.show", compact('archive'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $archive = Archive::findOrFail($id);
        
        $categories = [];
        if (Schema::hasTable('categories')) {
            $categories = Category::all();
        }

        return view('admin.arsip.edit', compact('archive', 'categories'));
    }

    /**
     * Update the specified archive (FIXED - File tidak terhapus jika tidak upload baru).
     */
    public function update(Request $request, $id)
    {
        $archive = Archive::findOrFail($id);

        $rules = [
            'nomor_surat' => 'required|string|max:255|unique:archives,nomor_surat,' . $id,
            'judul' => 'required|string|max:255',
            'files' => 'nullable|array', // ← NULLABLE (tidak wajib)
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
            // JIKA TIDAK ADA FILE BARU, FILE LAMA TETAP ADA (tidak ada perubahan)

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

            // UPDATE archive (tidak delete!)
            $archive->update($validated);

            $routePrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';

            return redirect()->route("{$routePrefix}.arsip.show", $archive->id)
                           ->with('success', 'Arsip berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal memperbarui arsip: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified archive.
     */
    public function destroy($id)
    {
        try {
            $archive = Archive::findOrFail($id);

            // Delete file from storage
            if ($archive->file_path && Storage::disk('public')->exists($archive->file_path)) {
                Storage::disk('public')->delete($archive->file_path);
            }

            $archive->delete();

            $routePrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';

            return redirect()->route("{$routePrefix}.arsip.index")
                           ->with('success', 'Arsip berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menghapus arsip: ' . $e->getMessage());
        }
    }

    /**
     * Toggle favorite status (FIXED - Return Redirect).
     */
    public function toggleFavorite($id)
    {
        try {
            $archive = Archive::findOrFail($id);
            
            if (Schema::hasColumn('archives', 'is_favorite')) {
                $archive->is_favorite = !$archive->is_favorite;
                $archive->save();

                $message = $archive->is_favorite 
                    ? 'Arsip ditambahkan ke favorit!' 
                    : 'Arsip dihapus dari favorit!';

                return redirect()->back()->with('success', $message);
            }

            return redirect()->back()->with('error', 'Fitur favorit tidak tersedia');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui favorit');
        }
    }

    /**
     * Display favorite archives.
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

        $viewPrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';

        return view("{$viewPrefix}.arsip.favorit", compact('archives', 'categories', 'units'));
    }

    /**
     * Preview archive file in browser (for PDF/Images).
     */
    public function preview($id)
    {
        try {
            $archive = Archive::findOrFail($id);

            if (!$archive->file_path) {
                return redirect()->back()->with('error', 'File tidak ditemukan');
            }

            $fullPath = storage_path('app/public/' . $archive->file_path);
            
            if (!file_exists($fullPath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan di server');
            }

            // Return file untuk preview di browser
            return response()->file($fullPath, [
                'Content-Type' => mime_content_type($fullPath),
                'Content-Disposition' => 'inline; filename="' . ($archive->file_name ?? basename($archive->file_path)) . '"'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuka file: ' . $e->getMessage());
        }
    }

    /**
     * Download archive file (FIXED - Force download).
     */
    public function download($id)
    {
        try {
            $archive = Archive::findOrFail($id);

            if (!$archive->file_path) {
                return redirect()->back()->with('error', 'Path file tidak ditemukan');
            }

            $fullPath = storage_path('app/public/' . $archive->file_path);
            
            if (!file_exists($fullPath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan di server');
            }

            $downloadName = $archive->file_name ?? basename($archive->file_path);
            
            // Force download
            return response()->download($fullPath, $downloadName);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }
}