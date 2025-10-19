@extends('admin.layouts.app')

@section('title', 'Detail Arsip')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📄 Detail Arsip Digital</h1>
            <p class="text-sm text-gray-500 mt-1">Informasi lengkap arsip digital</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.arsip.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.arsip.edit', $archive->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Informasi Utama --}}
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Arsip
                </h2>
                
                <div class="space-y-4">
                    {{-- Nomor Surat --}}
                    <div class="flex items-start border-b border-gray-100 pb-4">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Nomor Surat</p>
                        </div>
                        <div class="w-2/3">
                            <p class="text-sm text-gray-900 font-mono bg-blue-50 px-3 py-1 rounded inline-block">
                                {{ $archive->nomor_surat }}
                            </p>
                        </div>
                    </div>

                    {{-- Tanggal Surat --}}
                    <div class="flex items-start border-b border-gray-100 pb-4">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Tanggal Surat</p>
                        </div>
                        <div class="w-2/3">
                            <p class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($archive->tanggal_surat ?? $archive->tanggal_arsip)->format('d F Y') }}
                            </p>
                        </div>
                    </div>

                    {{-- Judul --}}
                    <div class="flex items-start border-b border-gray-100 pb-4">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Judul/Perihal</p>
                        </div>
                        <div class="w-2/3">
                            <p class="text-sm text-gray-900 font-medium">{{ $archive->judul }}</p>
                        </div>
                    </div>

                    {{-- Pengirim --}}
                    <div class="flex items-start border-b border-gray-100 pb-4">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Pengirim</p>
                        </div>
                        <div class="w-2/3">
                            <p class="text-sm text-gray-900">{{ $archive->pengirim ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Unit --}}
                    <div class="flex items-start border-b border-gray-100 pb-4">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Unit/Bidang</p>
                        </div>
                        <div class="w-2/3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $archive->unit ?? '-' }}
                            </span>
                        </div>
                    </div>

                    {{-- Kategori --}}
                    @if($archive->category)
                    <div class="flex items-start border-b border-gray-100 pb-4">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Kategori</p>
                        </div>
                        <div class="w-2/3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $archive->category->name }}
                            </span>
                        </div>
                    </div>
                    @endif

                    {{-- Prioritas --}}
                    @if($archive->priority)
                    <div class="flex items-start border-b border-gray-100 pb-4">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Tingkat Kepentingan</p>
                        </div>
                        <div class="w-2/3">
                            @php
                                $priorityColors = [
                                    'Biasa' => 'bg-gray-100 text-gray-800',
                                    'Penting' => 'bg-yellow-100 text-yellow-800',
                                    'Sangat Penting' => 'bg-orange-100 text-orange-800',
                                    'Segera' => 'bg-red-100 text-red-800'
                                ];
                                $colorClass = $priorityColors[$archive->priority] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                {{ $archive->priority }}
                            </span>
                        </div>
                    </div>
                    @endif

                    {{-- Keterangan --}}
                    @if($archive->keterangan)
                    <div class="flex items-start">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Keterangan</p>
                        </div>
                        <div class="w-2/3">
                            <p class="text-sm text-gray-900">{{ $archive->keterangan }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Tags --}}
                    @if($archive->tags)
                    <div class="flex items-start pt-4 border-t border-gray-100">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Tags</p>
                        </div>
                        <div class="w-2/3">
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $archive->tags) as $tag)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        #{{ trim($tag) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- File Preview --}}
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    File Arsip
                </h2>

                @if($archive->file_path && Storage::disk('public')->exists($archive->file_path))
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-200 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center min-w-0 flex-1">
                                <svg class="w-12 h-12 text-red-500 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-gray-900 truncate" title="{{ $archive->file_name ?? basename($archive->file_path) }}">
                                        {{ $archive->file_name ?? basename($archive->file_path) }}
                                    </p>
                                    @if($archive->file_size)
                                        <p class="text-xs text-gray-600">{{ number_format($archive->file_size / 1024, 2) }} KB</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <a href="{{ route('admin.arsip.download', $archive->id) }}" 
                               class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition flex items-center justify-center font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download File
                            </a>
                            <a href="{{ route('admin.arsip.preview', $archive->id) }}" target="_blank"
                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition flex items-center justify-center font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Buka di Tab Baru
                            </a>
                        </div>
                    </div>

                    {{-- PDF Preview (if PDF) --}}
                    @if(pathinfo($archive->file_path, PATHINFO_EXTENSION) === 'pdf')
                    <div class="mt-4">
                        <iframe src="{{ route('admin.arsip.preview', $archive->id) }}" 
                                class="w-full h-96 border-2 border-gray-200 rounded-lg"
                                frameborder="0">
                        </iframe>
                    </div>
                    @endif
                @else
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                        <svg class="w-12 h-12 text-red-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-red-800 font-medium">File tidak ditemukan</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Status Card --}}
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Status</h3>
                
                <div class="space-y-3">
                    {{-- Favorit --}}
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Favorit</span>
                        <form action="{{ route('admin.arsip.favorite', $archive->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-yellow-600 hover:text-yellow-700">
                                <svg class="w-6 h-6 {{ $archive->is_favorite ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- Uploaded By --}}
                    <div class="pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">Diunggah oleh</p>
                        <p class="text-sm font-medium text-gray-900">{{ $archive->uploader->name ?? 'Admin' }}</p>
                    </div>

                    {{-- Created At --}}
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Upload</p>
                        <p class="text-sm text-gray-900">{{ $archive->created_at->format('d F Y, H:i') }}</p>
                    </div>

                    {{-- Updated At --}}
                    @if($archive->updated_at && $archive->updated_at != $archive->created_at)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Terakhir Diupdate</p>
                        <p class="text-sm text-gray-900">{{ $archive->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Actions Card --}}
            @if(Auth::user()->role === 'admin')
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.arsip.edit', $archive->id) }}" 
                       class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Arsip
                    </a>
                    
                    <form action="{{ route('admin.arsip.destroy', $archive->id) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus arsip ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Arsip
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection