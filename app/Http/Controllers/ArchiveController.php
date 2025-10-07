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

    // **TAMBAHAN INI - List Unit/Bidang Kominfo**
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
        'units',  // TAMBAHKAN INI
        'totalArchives',
        'favoritesCount',
        'categoriesCount',
        'thisMonthCount'
    ));
}

    /**
     * Store a newly created archive.
     */
    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'nomor_surat' => 'required|string|max:255|unique:archives,nomor_surat',
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
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
        if (Schema::hasColumn('archives', 'priority')) {
            $rules['priority'] = 'nullable|string|in:Biasa,Penting,Sangat Penting,Segera';
        }
        if (Schema::hasColumn('archives', 'keterangan')) {
            $rules['keterangan'] = 'nullable|string';
        }
        if (Schema::hasColumn('archives', 'tags')) {
            $rules['tags'] = 'nullable|string';
        }

        $validated = $request->validate($rules, [
            'nomor_surat.required' => 'Nomor surat wajib diisi',
            'nomor_surat.unique' => 'Nomor surat sudah ada dalam database',
            'judul.required' => 'Judul surat wajib diisi',
            'file.required' => 'File wajib diupload',
            'file.mimes' => 'Format file tidak didukung (gunakan PDF, DOC, DOCX, XLS, XLSX)',
            'file.max' => 'Ukuran file maksimal 10MB'
        ]);

        try {
            // Upload file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
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

            // Create archive
            $archive = Archive::create($validated);

            // Determine redirect route
            $routePrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';

            return redirect()->route("{$routePrefix}.arsip.index")
                           ->with('success', 'Arsip berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menyimpan arsip: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified archive (AJAX).
     */
    public function show($id)
    {
        try {
            $archive = Archive::findOrFail($id);
            
            $data = [
                'nomor_surat' => $archive->nomor_surat,
                'judul' => $archive->judul,
                'file_url' => Storage::disk('public')->exists($archive->file_path) 
                    ? asset('storage/' . $archive->file_path) 
                    : '#',
            ];

            // Add optional fields
            if (Schema::hasColumn('archives', 'tanggal_surat') && $archive->tanggal_surat) {
                $data['tanggal_surat'] = Carbon::parse($archive->tanggal_surat)->format('d/m/Y');
            } elseif ($archive->tanggal_arsip) {
                $data['tanggal_surat'] = Carbon::parse($archive->tanggal_arsip)->format('d/m/Y');
            }

            if (Schema::hasColumn('archives', 'pengirim')) {
                $data['pengirim'] = $archive->pengirim ?? '-';
            }

            if (Schema::hasColumn('archives', 'unit')) {
                $data['unit'] = $archive->unit ?? '-';
            }

            if (Schema::hasTable('categories') && $archive->category) {
                $data['category'] = $archive->category->name;
            } else {
                $data['category'] = $archive->jenis_arsip ?? '-';
            }

            if (Schema::hasColumn('archives', 'priority')) {
                $data['priority'] = $archive->priority ?? 'Biasa';
            }

            if (Schema::hasColumn('archives', 'keterangan')) {
                $data['keterangan'] = $archive->keterangan ?? '-';
            }

            if (Schema::hasColumn('archives', 'tags')) {
                $data['tags'] = $archive->tags ?? '-';
            }

            if (Schema::hasColumn('archives', 'file_name')) {
                $data['file_name'] = $archive->file_name ?? basename($archive->file_path);
            }

            if (Schema::hasColumn('archives', 'file_size') && $archive->file_size) {
                $data['file_size'] = number_format($archive->file_size / 1024, 2) . ' KB';
            }

            $data['uploaded_at'] = Carbon::parse($archive->created_at)->format('d/m/Y H:i');
            
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Arsip tidak ditemukan'], 404);
        }
    }

    /**
     * Show the form for editing (AJAX).
     */
    public function edit($id)
    {
        try {
            $archive = Archive::findOrFail($id);
            
            $categories = [];
            if (Schema::hasTable('categories')) {
                $categories = Category::all();
            }
            
            $html = view('admin.arsip.edit-form', compact('archive', 'categories'))->render();
            
            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Arsip tidak ditemukan'], 404);
        }
    }

    /**
     * Update the specified archive.
     */
    public function update(Request $request, $id)
    {
        $archive = Archive::findOrFail($id);

        $rules = [
            'nomor_surat' => 'required|string|max:255|unique:archives,nomor_surat,' . $id,
            'judul' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
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
            // Upload new file if exists
            if ($request->hasFile('file')) {
                // Delete old file
                if ($archive->file_path && Storage::disk('public')->exists($archive->file_path)) {
                    Storage::disk('public')->delete($archive->file_path);
                }

                $file = $request->file('file');
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

            $archive->update($validated);

            $routePrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';

            return redirect()->route("{$routePrefix}.arsip.index")
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
     * Toggle favorite status.
     */
    public function toggleFavorite($id)
    {
        try {
            $archive = Archive::findOrFail($id);
            
            if (Schema::hasColumn('archives', 'is_favorite')) {
                $archive->is_favorite = !$archive->is_favorite;
                $archive->save();

                return response()->json([
                    'success' => true,
                    'is_favorite' => $archive->is_favorite,
                    'message' => $archive->is_favorite ? 'Ditambahkan ke favorit' : 'Dihapus dari favorit'
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Fitur favorit tidak tersedia'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui favorit'], 500);
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

    // **TAMBAHAN INI**
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
     * Download archive file.
     */
    public function download($id)
    {
        try {
            $archive = Archive::findOrFail($id);

            // Cek apakah file ada
            if (!$archive->file_path) {
                return redirect()->back()->with('error', 'Path file tidak ditemukan');
            }

            // Path lengkap file
            $fullPath = storage_path('app/public/' . $archive->file_path);
            
            // Cek apakah file fisik ada
            if (!file_exists($fullPath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan di server');
            }

            // Tentukan nama file download
            $downloadName = $archive->file_name ?? basename($archive->file_path);

            // Download file menggunakan response()->download()
            return response()->download($fullPath, $downloadName);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Arsip tidak ditemukan di database');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }
}