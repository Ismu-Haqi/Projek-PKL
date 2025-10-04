@extends('admin.layouts.app')

@section('title', 'Arsip Favorit')

@section('content')

<div class="p-6">
    {{-- Header dan Judul --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-yellow-500 fill-current flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Arsip Favorit</h1>
                <p class="text-gray-500">Dokumen yang Anda tandai sebagai favorit</p>
            </div>
        </div>
    </div>

    {{-- Daftar Arsip Favorit (Grid Card View) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        
        @forelse ($favoriteArchives as $archive)
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 transition duration-300 hover:shadow-xl">
            <div class="flex justify-between items-start mb-3">
                <h2 class="text-lg font-semibold text-gray-800 leading-snug hover:text-blue-600">
                    {{ $archive->judul }} 
                </h2>
                <button class="text-yellow-500 hover:text-yellow-600 focus:outline-none ml-2" title="Tandai sebagai Favorit">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                </button>
            </div>

            {{-- ID Dokumen --}}
            <p class="text-sm font-medium text-red-600 mb-2">ID: {{ $archive->nomor_dokumen }}</p>
            
            {{-- Deskripsi Singkat --}}
            <p class="text-sm text-gray-600 mb-4 h-12 overflow-hidden">{{ $archive->deskripsi_singkat }}</p>

            {{-- Kategori (Tag) --}}
            <div class="mb-4">
                <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ $archive->kategori }}
                </span>
                <span class="inline-block bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ $archive->tipe }}
                </span>
            </div>

            <div class="space-y-2 text-sm text-gray-600 border-t pt-4">
                
                {{-- Tanggal --}}
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>{{ $archive->tanggal_dokumen }}</span>
                </div>
                
                {{-- Pengunggah/Unit --}}
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>{{ $archive->pengunggah }} • {{ $archive->unit }}</span>
                </div>
                
                {{-- Nama File & Ukuran --}}
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>{{ $archive->file_name }} ({{ $archive->file_size }})</span>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex space-x-3 mt-6">
                {{-- Link Lihat/Show --}}
                <a href="{{ route('admin.arsip.lihat', $archive->id) }}" class="flex-1 flex items-center justify-center py-2 px-4 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Lihat
                </a>
                {{-- Link Unduh/Download --}}
                <a href="{{ route('admin.arsip.unduh', $archive->id) }}" class="flex-1 flex items-center justify-center py-2 px-4 border border-blue-600 rounded-lg text-sm font-medium text-blue-600 hover:bg-blue-50 transition">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Unduh
                </a>
                {{-- Tombol Unfavorite/Hapus dari Favorit --}}
                <button class="p-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-600 transition" title="Hapus dari Favorit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-gray-100 rounded-xl">
            <p class="text-gray-500">Anda belum menandai arsip apapun sebagai favorit.</p>
            <p class="text-sm text-gray-400 mt-2">Cari arsip di menu Arsip Digital dan klik ikon bintang untuk menambahkannya ke sini.</p>
        </div>
        @endforelse

    </div>
</div>

@endsection