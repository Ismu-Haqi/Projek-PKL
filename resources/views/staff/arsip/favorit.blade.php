@extends('staff.layouts.app')

@section('title', 'Arsip Favorit')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="bg-yellow-100 p-3 rounded-full mr-4">
                <svg class="w-8 h-8 text-yellow-600 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">⭐ Arsip Favorit</h1>
                <p class="text-gray-500 mt-1">Dokumen yang Anda tandai sebagai favorit</p>
            </div>
        </div>
        <a href="{{ route('admin.arsip.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    {{-- Search --}}
    <form action="{{ route('admin.arsip.favorit') }}" method="GET" class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="flex gap-3">
            <div class="flex-1 relative">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari di arsip favorit..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
            </div>
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg transition">
                Cari
            </button>
            <a href="{{ route('admin.arsip.favorit') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg transition">
                Reset
            </a>
        </div>
    </form>

    {{-- Grid Cards --}}
    @if($archives->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($archives as $archive)
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden transition duration-300 hover:shadow-xl">
            {{-- Header Card --}}
            <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 p-4">
                <div class="flex justify-between items-start">
                    <span class="bg-white text-yellow-600 text-xs font-bold px-3 py-1 rounded-full">
                        {{ $archive->nomor_surat }}
                    </span>
                    <button onclick="toggleFavorite({{ $archive->id }})" class="text-white hover:text-yellow-200 transition">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Body Card --}}
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 hover:text-blue-600 cursor-pointer" onclick="viewArchive({{ $archive->id }})">
                    {{ $archive->judul }}
                </h2>

                @if($archive->keterangan)
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $archive->keterangan }}</p>
                @endif

                {{-- Tags --}}
                <div class="flex flex-wrap gap-2 mb-4">
                    @if($archive->category)
                    <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $archive->category->name }}
                    </span>
                    @endif
                    @if($archive->unit)
                    <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $archive->unit }}
                    </span>
                    @endif
                </div>

                {{-- Info --}}
                <div class="space-y-2 text-sm text-gray-600 border-t pt-4">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($archive->tanggal_arsip)->format('d M Y') }}</span>
                    </div>
                    
                    @if($archive->pengirim)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>{{ $archive->pengirim }}</span>
                    </div>
                    @endif
                    
                    @if($archive->file_name)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="truncate">{{ $archive->file_name }}</span>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 mt-4">
                    <button onclick="viewArchive({{ $archive->id }})" 
                            class="flex-1 bg-blue-50 text-blue-600 hover:bg-blue-100 py-2 px-4 rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat
                    </button>
                    <a href="{{ route('admin.arsip.download', $archive->id) }}" 
                       class="flex-1 bg-green-50 text-green-600 hover:bg-green-100 py-2 px-4 rounded-lg text-sm font-medium transition text-center">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($archives->hasPages())
    <div class="mt-6">
        {{ $archives->links() }}
    </div>
    @endif

    @else
    {{-- Empty State --}}
    <div class="bg-white rounded-xl shadow p-12 text-center">
        <div class="inline-block bg-yellow-100 p-6 rounded-full mb-4">
            <svg class="w-16 h-16 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Arsip Favorit</h3>
        <p class="text-gray-500 mb-6">Tandai arsip sebagai favorit dari menu Arsip Digital</p>
        <a href="{{ route('admin.arsip.index') }}" class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-medium transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Browse Arsip Digital
        </a>
    </div>
    @endif
</div>

{{-- View Modal --}}
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-yellow-500 text-white">
                <h5 class="modal-title">📄 Detail Arsip Favorit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-yellow-500" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function viewArchive(id) {
    $('#viewModal').modal('show');
    fetch(`/admin/arsip/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('viewContent').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Surat</label>
                            <p class="mt-1 text-sm text-gray-900">${data.nomor_surat}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <p class="mt-1 text-sm text-gray-900">${data.tanggal_surat}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Judul</label>
                        <p class="mt-1 text-sm text-gray-900">${data.judul}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pengirim</label>
                            <p class="mt-1 text-sm text-gray-900">${data.pengirim}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit</label>
                            <p class="mt-1 text-sm text-gray-900">${data.unit}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <p class="mt-1 text-sm text-gray-900">${data.keterangan}</p>
                    </div>
                    <div class="pt-4 border-t">
                        <a href="${data.file_url}" target="_blank" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download File
                        </a>
                    </div>
                </div>
            `;
        });
}

function toggleFavorite(id) {
    if (confirm('Hapus dari favorit?')) {
        fetch(`/admin/arsip/${id}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endpush

@endsection