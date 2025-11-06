@extends('pimpinan.layouts.app') 

@section('title', 'Arsip Digital')

@section('content')
<div class="p-6">

    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg animate-fade-in">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg animate-fade-in">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📁 Arsip Digital</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola dan organisir dokumen arsip digital Anda</p>
        </div>
        
        {{-- Tombol Upload - REDIRECT KE HALAMAN CREATE --}}
        @if(Auth::user()->role === 'pimpinan')
        <a href="{{ route('pimpinan.arsip.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg flex items-center transition duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            Unggah Arsip
        </a>
        @endif
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Arsip</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalArchives ?? 0 }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Favorit</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $favoritesCount ?? 0 }}</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Kategori</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $categoriesCount ?? 0 }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $thisMonthCount ?? 0 }}</h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <form action="{{ route('pimpinan.arsip.index') }}" method="GET" class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            {{-- Search --}}
            <div class="md:col-span-2">
                <div class="relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nomor surat, judul, atau pengirim..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            {{-- Kategori --}}
            <div>
                <select name="category" class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Unit --}}
            <div>
                <select name="unit" class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Unit</option>
                    @foreach($units ?? [] as $unit)
                        <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>
                            {{ $unit }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" title="Filter">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </button>
                <a href="{{ route('pimpinan.arsip.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition" title="Reset">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </a>
            </div>
        </div>
    </form>

    {{-- Archives List --}}
    @if($archives->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nomor Surat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($archives as $index => $archive)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $archives->firstItem() + $index }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $archive->nomor_surat }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ Str::limit($archive->judul, 50) }}</p>
                                    <p class="text-xs text-gray-500">{{ $archive->pengirim ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $archive->unit ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($archive->tanggal_arsip)->format('d/m/Y') }}
                        </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">
                            {{-- View - Lihat Detail --}}
                            <a href="{{ route('pimpinan.arsip.show', $archive->id) }}" 
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded transition" 
                            title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>

                            {{-- Download - dengan SweetAlert2 Confirmation --}}
                            <a href="javascript:void(0)" 
                            onclick="confirmDownload('{{ route('pimpinan.arsip.download', $archive->id) }}', '{{ $archive->file_name ?? $archive->nomor_surat }}')"
                            class="p-2 text-green-600 hover:bg-green-50 rounded transition" 
                            title="Download">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </a>

                            {{-- Favorite - dengan SweetAlert2 Confirmation --}}
                            <form action="{{ route('pimpinan.arsip.favorite', $archive->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="button" 
                                        onclick="confirmToggleFavorite(this, {{ $archive->is_favorite ? 'true' : 'false' }}, '{{ Str::limit($archive->judul, 30) }}')"
                                        class="p-2 text-yellow-600 hover:bg-yellow-50 rounded transition" 
                                        title="{{ $archive->is_favorite ? 'Hapus dari Favorit' : 'Tambahkan ke Favorit' }}">
                                    <svg class="w-5 h-5 {{ $archive->is_favorite ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </button>
                            </form>

                            @if(Auth::user()->role === 'pimpinan')
                            {{-- Edit --}}
                            <a href="{{ route('pimpinan.arsip.edit', $archive->id) }}" 
                            class="p-2 text-orange-600 hover:bg-orange-50 rounded transition" 
                            title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            {{-- Delete - dengan SweetAlert2 --}}
                            <form action="{{ route('pimpinan.arsip.destroy', $archive->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmDelete(this, 'Arsip &quot;{{ addslashes(Str::limit($archive->judul, 40)) }}&quot; akan dihapus permanen!')" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded transition" 
                                        title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($archives->hasPages())
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $archives->firstItem() }}</span> sampai 
                    <span class="font-medium">{{ $archives->lastItem() }}</span> dari 
                    <span class="font-medium">{{ $archives->total() }}</span> arsip
                </div>
                <div>
                    {{ $archives->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
    @else
    {{-- Empty State --}}
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Arsip</h3>
        <p class="text-gray-500 mb-6">Mulai unggah arsip digital Anda dengan klik tombol di atas</p>
        @if(Auth::user()->role === 'pimpinan')
        <a href="{{ route('pimpinan.arsip.create') }}" 
           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Unggah Arsip Pertama
        </a>
        @endif
    </div>
    @endif

</div>

{{-- TIDAK ADA MODAL LAGI! Semua sudah pakai halaman terpisah --}}

@push('scripts')
<script>
/**
 * Confirm Toggle Favorite Function
 * @param {HTMLElement} button - Button element yang diklik
 * @param {boolean} isFavorite - Status favorit saat ini
 * @param {string} title - Judul arsip (optional)
 */
function confirmToggleFavorite(button, isFavorite, title = 'arsip ini') {
    // Cari form terdekat dari button yang diklik
    const form = button.closest('form');
    
    if (!form) {
        console.error('❌ Error: Form tidak ditemukan!');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error Sistem',
                text: 'Form tidak ditemukan. Silakan refresh halaman.',
                confirmButtonColor: '#dc2626'
            });
        } else {
            alert('Form tidak ditemukan!');
        }
        return false;
    }
    
    // Log untuk debugging
    console.log('✅ Form ditemukan:', form);
    console.log('📋 Action URL:', form.action);
    console.log('⭐ Is Favorite:', isFavorite);
    
    // Check if SweetAlert is available
    if (typeof Swal === 'undefined') {
        if (confirm(isFavorite ? 'Hapus dari favorit?' : 'Tambahkan ke favorit?')) {
            form.submit();
        }
        return;
    }
    
    // Tampilkan konfirmasi dengan SweetAlert2
    Swal.fire({
        title: isFavorite ? '⭐ Hapus dari Favorit?' : '⭐ Tambahkan ke Favorit?',
        html: `
            <div class="text-left">
                <p class="text-gray-700 mb-2">${isFavorite ? 'Arsip akan dihapus dari daftar favorit' : 'Arsip akan ditambahkan ke daftar favorit'}</p>
                <p class="text-sm text-gray-600 font-semibold">"${title}"</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: isFavorite ? '#dc2626' : '#eab308',
        cancelButtonColor: '#6b7280',
        confirmButtonText: isFavorite ? '<i class="fas fa-star mr-2"></i> Hapus dari Favorit' : '<i class="fas fa-star mr-2"></i> Tambahkan ke Favorit',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
        reverseButtons: true,
        focusCancel: true,
        allowOutsideClick: true,
        allowEscapeKey: true,
        customClass: {
            popup: 'animated-popup',
            confirmButton: 'btn-favorite-confirm',
            cancelButton: 'btn-cancel'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading
            Swal.fire({
                title: isFavorite ? 'Menghapus dari Favorit...' : 'Menambahkan ke Favorit...',
                html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-yellow-500 mb-3"></i><p>Mohon tunggu sebentar</p></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Disable button untuk mencegah double submit
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            // Submit form
            console.log('🚀 Submitting favorite form...');
            form.submit();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.close();
            console.log('❌ Favorite action cancelled');
        } else {
            Swal.close();
        }
    }).catch((error) => {
        console.error('SweetAlert Error:', error);
        Swal.close();
    });
    
    return false;
}

/**
 * Confirm Download Function
 * @param {string} downloadUrl - URL untuk download file
 * @param {string} fileName - Nama file yang akan didownload
 */
function confirmDownload(downloadUrl, fileName = 'file') {
    // Check if SweetAlert is available
    if (typeof Swal === 'undefined') {
        window.location.href = downloadUrl;
        return;
    }
    
    Swal.fire({
        title: '📥 Download File?',
        html: `
            <div class="text-left">
                <p class="text-gray-700 mb-2">Anda akan mengunduh file:</p>
                <p class="text-sm text-blue-600 font-semibold bg-blue-50 p-3 rounded">"${fileName}"</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-download mr-2"></i> Ya, Download',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
        reverseButtons: true,
        allowOutsideClick: true,
        allowEscapeKey: true,
        customClass: {
            popup: 'animated-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading
            Swal.fire({
                title: 'Memproses Download...',
                html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-green-500 mb-3"></i><p>File sedang diunduh</p></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                timer: 1500,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Start download
            console.log('📥 Starting download:', downloadUrl);
            setTimeout(() => {
                window.location.href = downloadUrl;
                
                // Show success message after download starts
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Download Dimulai!',
                        text: 'File sedang diunduh ke perangkat Anda',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }, 500);
            }, 500);
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.close();
            console.log('❌ Download cancelled');
        } else {
            Swal.close();
        }
    }).catch((error) => {
        console.error('SweetAlert Error:', error);
        Swal.close();
    });
}

// Auto-hide success/error message
setTimeout(function() {
    const alerts = document.querySelectorAll('.animate-fade-in');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>
@endpush

@endsection