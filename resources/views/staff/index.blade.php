@extends('staff.layouts.app')

{{-- Jika file layout utama (layouts.app) Anda tidak ada, Anda HARUS menggunakan Solusi 1 (HTML Lengkap) --}}

@section('content')
<div class="p-6">

    {{-- Header dan Tombol Unggah --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Arsip Digital</h1>
            <p class="text-sm text-gray-500">Kelola dan akses arsip digital dengan mudah</p>
        </div>
        
        {{-- Tombol Unggah Arsip --}}
        <button type="button" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-md flex items-center"
                data-modal-target="uploadModal" data-modal-toggle="uploadModal">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path></svg>
            Upload Arsip
        </button>
    </div>

    {{-- Filter dan Search Bar --}}
    <div class="bg-white p-4 rounded-lg shadow-lg mb-6 flex space-x-4 items-center">
        <div class="relative flex-grow">
            <input type="text" placeholder="🔍 Cari arsip berdasarkan judul atau kategori..." class="w-full py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <select class="py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option>Semua Kategori</option>
            <option>Surat Keputusan</option>
            <option>Laporan Keuangan</option>
        </select>
        
        <select class="py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option>Semua Unit</option>
            <option>Diskominfo</option>
            <option>Sekretariat</option>
        </select>
        <button class="text-gray-500 hover:text-blue-600 p-2 rounded-lg">
             {{-- Ikon Filter --}}
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10h18M3 16h18M3 22h18"></path></svg>
        </button>
    </div>

    {{-- List Arsip (Card View) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($archives as $archive)
        {{-- Menggunakan border-blue-600 sebagai default --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-blue-600"> 
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold text-gray-800 line-clamp-2">{{ $archive->judul }}</h3>
                
                {{-- Logika Star/Favorite DIBUAT SEDERHANA --}}
                <span class="ml-3">
                    @if(isset($archive->favorite) && $archive->favorite)
                        {{-- Bintang Terisi (Favorite) --}}
                        <svg class="w-6 h-6 fill-current text-yellow-500" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 15l-5.878 3.09 1.123-6.545L.487 7.098l6.561-.955L10 1l2.952 5.143 6.561.955-4.758 4.647 1.123 6.545L10 15z"/></svg>
                    @else
                        {{-- Bintang Outline (Non-Favorite) --}}
                        <svg class="w-6 h-6 text-gray-300 hover:text-yellow-500 transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.62-.921 1.92 0l3.07 9.488a1 1 0 00.95.691h9.919a1 1 0 01.95.691l-3.07 9.488a1 1 0 00.95.691h-9.919a1 1 0 01.95.691z"></path></svg>
                    @endif
                </span>
            </div>
            
            <p class="text-xs text-gray-600 mb-2">ID: <span class="font-mono text-blue-600">{{ $archive->id }}</span></p>
            <div class="flex space-x-2 text-xs mb-3">
                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded">{{ $archive->kategori }}</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded">{{ $archive->unit }}</span>
            </div>

            <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 border-t pt-3">
                <div>Tipe: <span class="font-medium">{{ $archive->tipe }}</span></div>
                <div class="text-right">Ukuran: <span class="font-medium">{{ $archive->ukuran }}</span></div>
                <div class="col-span-2">Tanggal: <span class="font-medium">{{ $archive->tanggal }}</span></div>
            </div>

            <div class="mt-4 flex justify-between space-x-2 border-t pt-3">
                <button class="flex-1 flex items-center justify-center p-2 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Preview
                </button>
                <button class="flex-1 flex items-center justify-center p-2 text-green-600 border border-green-600 rounded-lg hover:bg-green-50 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download
                </button>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection

{{-- PENTING: Panggil modal dan script di luar @section('content') --}}

@include('admin.arsip.upload-modal') 

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>