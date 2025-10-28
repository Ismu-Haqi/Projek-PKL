@extends('staff.layouts.app')

@section('title', 'Detail Arsip')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('staff.arsip.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold mb-4 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Arsip
        </a>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">📄 Detail Arsip</h1>
                <p class="text-gray-600">Informasi lengkap dokumen arsip digital</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Document Info Card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Informasi Dokumen</h2>
                        <p class="text-sm text-gray-500">Detail lengkap arsip</p>
                    </div>
                </div>
                
                <div class="space-y-5">
                    {{-- Nomor Surat --}}
                    <div class="flex items-start">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Nomor Surat</p>
                        </div>
                        <div class="w-2/3">
                            <p class="text-sm font-mono bg-blue-50 text-blue-800 px-4 py-2 rounded-lg inline-block font-semibold">
                                {{ $archive->nomor_surat }}
                            </p>
                        </div>
                    </div>

                    {{-- Tanggal Surat --}}
                    <div class="flex items-start">
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
                    <div class="flex items-start">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Judul/Perihal</p>
                        </div>
                        <div class="w-2/3">
                            <p class="text-sm text-gray-900 font-medium leading-relaxed">{{ $archive->judul }}</p>
                        </div>
                    </div>

                    {{-- Pengirim --}}
                    @if($archive->pengirim)
                    <div class="flex items-start">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Pengirim</p>
                        </div>
                        <div class="w-2/3">
                            <p class="text-sm text-gray-900">{{ $archive->pengirim }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Unit --}}
                    @if($archive->unit)
                    <div class="flex items-start">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Unit/Bidang</p>
                        </div>
                        <div class="w-2/3">
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-purple-100 text-purple-800">
                                {{ $archive->unit }}
                            </span>
                        </div>
                    </div>
                    @endif

                    {{-- Kategori --}}
                    @if($archive->category)
                    <div class="flex items-start">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Kategori</p>
                        </div>
                        <div class="w-2/3">
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-blue-100 text-blue-800">
                                {{ $archive->category->name }}
                            </span>
                        </div>
                    </div>
                    @endif

                    {{-- Prioritas --}}
                    @if($archive->priority)
                    <div class="flex items-start">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Prioritas</p>
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
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold {{ $colorClass }}">
                                {{ $archive->priority }}
                            </span>
                        </div>
                    </div>
                    @endif

                    {{-- Keterangan --}}
                    @if($archive->keterangan)
                    <div class="flex items-start pt-4 border-t border-gray-200">
                        <div class="w-1/3">
                            <p class="text-sm font-semibold text-gray-600">Keterangan</p>
                        </div>
                        <div class="w-2/3">
                            <p class="text-sm text-gray-900 leading-relaxed">{{ $archive->keterangan }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- File Preview Card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">File Dokumen</h2>
                        <p class="text-sm text-gray-500">Preview dan unduh file</p>
                    </div>
                </div>

                @if($archive->file_path && Storage::disk('public')->exists($archive->file_path))
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-200 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-base font-bold text-gray-900 truncate mb-1" title="{{ $archive->file_name ?? basename($archive->file_path) }}">
                                        {{ $archive->file_name ?? basename($archive->file_path) }}
                                    </p>
                                    @if($archive->file_size)
                                        <p class="text-sm text-gray-600">Ukuran: {{ number_format($archive->file_size / 1024, 2) }} KB</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">{{ strtoupper(pathinfo($archive->file_path, PATHINFO_EXTENSION)) }} Document</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <a href="{{ route('staff.arsip.download', $archive->id) }}" 
                               class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-4 rounded-xl transition-all duration-300 flex items-center justify-center font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download File
                            </a>
                            <a href="{{ route('staff.arsip.preview', $archive->id) }}" target="_blank"
                               class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-4 rounded-xl transition-all duration-300 flex items-center justify-center font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Buka di Tab Baru
                            </a>
                        </div>
                    </div>

                    {{-- PDF Preview (if PDF) --}}
                    @if(pathinfo($archive->file_path, PATHINFO_EXTENSION) === 'pdf')
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Preview Dokumen:</p>
                        <iframe src="{{ route('staff.arsip.preview', $archive->id) }}" 
                                class="w-full h-[600px] border-2 border-gray-300 rounded-xl shadow-inner"
                                frameborder="0">
                        </iframe>
                    </div>
                    @endif
                @else
                    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-8 text-center">
                        <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-base text-red-800 font-semibold">File tidak ditemukan</p>
                        <p class="text-sm text-red-600 mt-2">Dokumen mungkin telah dihapus atau dipindahkan</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Quick Actions --}}
            <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white">
                <h3 class="text-xl font-bold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Aksi Cepat
                </h3>
                <div class="space-y-3">
                    {{-- Favorite Toggle --}}
                    <form action="{{ route('staff.arsip.favorite', $archive->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-white/20 hover:bg-white/30 backdrop-blur-lg p-4 rounded-xl transition-all transform hover:scale-105 flex items-center justify-center">
                            <svg class="w-6 h-6 mr-3 {{ $archive->is_favorite ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            <span class="font-semibold">
                                {{ $archive->is_favorite ? 'Hapus dari Favorit' : 'Tambah ke Favorit' }}
                            </span>
                        </button>
                    </form>
                    
                    {{-- Download --}}
                    <a href="{{ route('staff.arsip.download', $archive->id) }}" class="block w-full bg-white/20 hover:bg-white/30 backdrop-blur-lg p-4 rounded-xl transition-all transform hover:scale-105">
                        <div class="flex items-center justify-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            <span class="font-semibold">Download File</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Status & Info --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Tambahan
                </h3>
                
                <div class="space-y-4">
                    {{-- Favorite Status --}}
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Status Favorit</span>
                        <span class="flex items-center">
                            @if($archive->is_favorite)
                                <svg class="w-5 h-5 text-yellow-500 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            @endif
                        </span>
                    </div>

                    {{-- Upload Info --}}
                    <div class="pb-3 border-b border-gray-100">
                        <p class="text-xs text-gray-500 mb-2">Diunggah oleh</p>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xs mr-2">
                                {{ strtoupper(substr($archive->uploader->name ?? 'A', 0, 2)) }}
                            </div>
                            <p class="text-sm font-medium text-gray-900">{{ $archive->uploader->name ?? 'Admin' }}</p>
                        </div>
                    </div>

                    {{-- Created At --}}
                    <div class="pb-3 border-b border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">Tanggal Upload</p>
                        <p class="text-sm font-medium text-gray-900">{{ $archive->created_at->format('d F Y, H:i') }} WIB</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $archive->created_at->diffForHumans() }}</p>
                    </div>

                    {{-- Updated At --}}
                    @if($archive->updated_at && $archive->updated_at != $archive->created_at)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Terakhir Diupdate</p>
                        <p class="text-sm font-medium text-gray-900">{{ $archive->updated_at->format('d F Y, H:i') }} WIB</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $archive->updated_at->diffForHumans() }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Related Actions --}}
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Navigasi Cepat
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('staff.arsip.index') }}" class="block w-full bg-white hover:bg-gray-50 text-gray-700 px-4 py-3 rounded-xl transition-colors text-sm font-medium flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Semua Arsip
                    </a>
                    <a href="{{ route('staff.arsip.favorit') }}" class="block w-full bg-white hover:bg-gray-50 text-gray-700 px-4 py-3 rounded-xl transition-colors text-sm font-medium flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        Arsip Favorit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection