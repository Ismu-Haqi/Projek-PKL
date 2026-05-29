@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Laporan Arsip Digital')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center">
            <a href="{{ route(Auth::user()->role . '.laporan.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Laporan Arsip Digital</h1>
                <p class="text-gray-600 mt-1">Rekap arsip berdasarkan kategori, periode, dan unit kerja</p>
            </div>
        </div>
        
        
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route(Auth::user()->role . '.laporan.arsip') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                <select name="category_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Kerja</label>
                <input type="text" name="unit" value="{{ request('unit') }}" placeholder="Nama unit..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg font-medium">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Tombol Untuk Print dan Download -->
<div class="flex gap-2 mb-4">
    <!-- PRINT - Preview PDF -->
    <a href="{{ route(auth()->user()->role . '.laporan.print-pdf', ['type' => 'arsip'] + request()->query()) }}" 
       target="_blank"
       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print PDF
    </a>
    
    <!-- DOWNLOAD - Download PDF -->
    <a href="{{ route(auth()->user()->role . '.laporan.export-pdf', ['type' => 'arsip'] + request()->query()) }}" 
       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Download PDF
    </a>
</div>
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 font-medium mb-1">Total Arsip</p>
                    <p class="text-3xl font-bold text-blue-700">{{ $archives->total() }}</p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-blue-500 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-xl border border-green-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-600 font-medium mb-1">Arsip Favorit</p>
                    <p class="text-3xl font-bold text-green-700">{{ $archives->where('is_favorite', true)->count() }}</p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-green-500 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-600 font-medium mb-1">Total Ukuran</p>
                    <p class="text-3xl font-bold text-purple-700">{{ number_format($archives->sum('file_size') / 1024 / 1024, 2) }} MB</p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-purple-500 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Archives Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Nomor Surat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Unit</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Ukuran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($archives as $index => $archive)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $archives->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">{{ $archive->nomor_surat }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ Str::limit($archive->judul, 50) }}</p>
                            @if($archive->is_favorite)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                ⭐ Favorit
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                {{ $archive->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $archive->unit ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $archive->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($archive->file_size / 1024, 2) }} KB</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">Tidak ada data arsip</p>
                                <p class="text-gray-400 text-sm mt-1">Coba ubah filter pencarian</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($archives->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $archives->links() }}
        </div>
        @endif
    </div>
</div>
{{-- Print Styles --}}
<style media="print">
    /* Hide elements when printing */
    @media print {
        /* Hide navigation, sidebar, buttons */
        nav, .sidebar, aside, header, footer,
        button, .no-print, .print-hide,
        a[href^="http"]:not(.print-show),
        .bg-gradient-to-r.from-purple-500,
        .bg-gradient-to-r.from-orange-500,
        svg.w-6.h-6.text-gray-600,
        .flex.items-center > a[href*="laporan.index"] {
            display: none !important;
        }

        /* Reset page margins */
        @page {
            margin: 1cm;
            size: A4;
        }

        body {
            margin: 0;
            padding: 0;
            background: white !important;
        }

        /* Make tables fit on page */
        table {
            page-break-inside: auto;
            width: 100%;
            border-collapse: collapse;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        /* Add page header for print */
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #333;
        }

        .print-header h1 {
            font-size: 18px;
            margin: 0;
            color: #333;
        }

        .print-header p {
            font-size: 12px;
            margin: 5px 0;
            color: #666;
        }

        /* Remove colors for black & white printing */
        * {
            color: #000 !important;
            background: white !important;
            box-shadow: none !important;
        }

        /* Keep badges visible */
        .badge, span[class*="bg-"] {
            border: 1px solid #333 !important;
            padding: 2px 6px !important;
            color: #000 !important;
        }

        /* Chart containers */
        canvas {
            max-height: 400px !important;
        }

        /* Remove shadows and gradients */
        .shadow-sm, .shadow-lg, .shadow-xl {
            box-shadow: none !important;
        }

        /* Adjust spacing */
        .space-y-6 > * + * {
            margin-top: 20px !important;
        }

        /* Footer for print */
        .print-footer {
            display: block !important;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
    }
</style>

{{-- Print Header (hidden on screen) --}}
<div class="print-header" style="display: none;">
    <h1>LAPORAN [NAMA LAPORAN]</h1>
    <p>GANDARIA - Pengelolaan arsip dan data aset terpadu, informatif dan akuntabel</p>
    <p>Dinas Komunikasi dan Informatika Barito Kuala</p>
    <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
</div>

{{-- Print Footer (hidden on screen) --}}
<div class="print-footer" style="display: none;">
    <p>GANDARIA  | Halaman [Page] dari [Total Pages]</p>
</div>
@endsection