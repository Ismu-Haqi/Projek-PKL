@extends('pimpinan.layouts.app')

@section('title', 'Arsip Favorit')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header Section with Gradient --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-yellow-400 via-amber-500 to-orange-500 rounded-2xl shadow-2xl mb-8 p-8">
        <div class="absolute inset-0 bg-black opacity-5"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-white/20 backdrop-blur-lg p-4 rounded-2xl">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <div class="text-white">
                        <h1 class="text-4xl font-bold mb-1">Arsip Favorit</h1>
                        <p class="text-white/90 text-lg">{{ $archives->total() }} dokumen tersimpan</p>
                    </div>
                </div>
                <a href="{{ route('pimpinan.arsip.index') }}" 
                   class="bg-white/20 backdrop-blur-lg hover:bg-white/30 text-white px-6 py-3 rounded-xl transition-all duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="font-semibold">Kembali</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <form action="{{ route('pimpinan.arsip.favorit') }}" method="GET" class="mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                {{-- Search Input --}}
                <div class="md:col-span-8">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari berdasarkan nomor surat, judul, atau pengirim..." 
                               class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all">
                    </div>
                </div>
                
                {{-- Action Buttons --}}
                <div class="md:col-span-4 flex gap-3">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('pimpinan.arsip.favorit') }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-300 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Archives Grid --}}
    @if($archives->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach ($archives as $archive)
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-2">
            {{-- Card Header with Gradient --}}
            <div class="relative bg-gradient-to-br from-yellow-400 via-amber-500 to-orange-500 p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="inline-flex items-center bg-white/25 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-bold mb-2">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            {{ $archive->nomor_surat }}
                        </div>
                        @if($archive->category)
                        <span class="inline-flex items-center bg-blue-500/90 backdrop-blur-sm text-white px-3 py-1 rounded-lg text-xs font-semibold">
                            {{ $archive->category->name }}
                        </span>
                        @endif
                    </div>
                    
                    {{-- Favorite Star (Filled) --}}
                  <form action="{{ route('pimpinan.arsip.favorite', $archive->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="button" 
                            onclick="confirmToggleFavorite(this, {{ $archive->is_favorite ? 'true' : 'false' }})"
                            class="btn btn-warning">
                        <i class="fas fa-star"></i>
                    </button>
                </form>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="p-6">
                {{-- Document Icon & Title --}}
                <div class="flex items-start mb-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center shadow-lg mr-3">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold text-gray-800 mb-1 line-clamp-2 group-hover:text-yellow-600 transition-colors cursor-pointer" 
                            onclick="window.location='{{ route('pimpinan.arsip.show', $archive->id) }}'">
                            {{ $archive->judul }}
                        </h3>
                    </div>
                </div>

                {{-- Metadata --}}
                <div class="space-y-3 mb-5">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($archive->tanggal_arsip)->format('d M Y') }}</span>
                    </div>
                    
                    @if($archive->pengirim)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="truncate">{{ $archive->pengirim }}</span>
                    </div>
                    @endif
                    
                    @if($archive->unit)
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="inline-flex items-center bg-purple-100 text-purple-700 px-2.5 py-1 rounded-lg text-xs font-semibold">
                            {{ $archive->unit }}
                        </span>
                    </div>
                    @endif
                    
                    @if($archive->file_name)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="truncate text-xs">{{ $archive->file_name }}</span>
                    </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('pimpinan.arsip.show', $archive->id) }}" 
                       class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>Detail</span>
                    </a>
                    <a href="{{ route('pimpinan.arsip.download', $archive->id) }}" 
                       class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center justify-center space-x-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        <span>Download</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($archives->hasPages())
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Menampilkan <span class="font-semibold text-gray-800">{{ $archives->firstItem() }}</span> - 
                <span class="font-semibold text-gray-800">{{ $archives->lastItem() }}</span> dari 
                <span class="font-semibold text-gray-800">{{ $archives->total() }}</span> arsip favorit
            </div>
            <div>
                {{ $archives->links() }}
            </div>
        </div>
    </div>
    @endif

    @else
    {{-- Empty State - Attractive Design --}}
    <div class="bg-white rounded-2xl shadow-xl p-16 text-center border border-gray-100">
        <div class="max-w-md mx-auto">
            {{-- Animated Icon --}}
            <div class="relative inline-block mb-6">
                <div class="absolute inset-0 bg-yellow-200 rounded-full blur-2xl opacity-50 animate-pulse"></div>
                <div class="relative bg-gradient-to-br from-yellow-100 to-amber-100 p-8 rounded-full">
                    <svg class="w-20 h-20 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Arsip Favorit</h3>
            <p class="text-gray-500 mb-8 text-lg">Tandai dokumen penting sebagai favorit agar mudah diakses kembali</p>
            
            <a href="{{ route('pimpinan.arsip.index') }}" 
               class="inline-flex items-center bg-gradient-to-r from-yellow-400 via-amber-500 to-orange-500 hover:from-yellow-500 hover:via-amber-600 hover:to-orange-600 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Jelajahi Arsip Digital</span>
            </a>
        </div>
    </div>
    @endif

</div>

@push('styles')
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.group:hover .line-clamp-2 {
    -webkit-line-clamp: unset;
}

/* Smooth page transitions */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
@endpush

@push('scripts')
<script>
// Add animation on scroll
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.group');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    cards.forEach(card => {
        card.style.opacity = '0';
        observer.observe(card);
    });
});

/**
 * Confirm Toggle Favorite Function
 * @param {HTMLElement} button - Button element yang diklik
 * @param {boolean} isFavorite - Status favorit saat ini
 * @param {string} title - Judul arsip (optional)
 */
function confirmToggleFavorite(button, isFavorite, title = 'arsip ini') {
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
            
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
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
            
            console.log('📥 Starting download:', downloadUrl);
            setTimeout(() => {
                window.location.href = downloadUrl;
                
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