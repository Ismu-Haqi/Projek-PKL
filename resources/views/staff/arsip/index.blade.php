@extends('staff.layouts.app')

@section('title', 'Arsip Digital')

@section('content')
<div class="p-6">

    {{-- Header dan Tombol Unggah --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Arsip Digital</h1>
            <p class="text-sm text-gray-500">Kelola dan akses arsip digital dengan mudah</p>
        </div>
        
        {{-- Tombol Unggah Arsip (Memanggil Modal) --}}
        <button type="button" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-md flex items-center"
                data-modal-target="uploadModal" data-modal-toggle="uploadModal">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path></svg>
            Upload Arsip
        </button>
    </div>

    {{-- Filter dan Search Bar --}}
    <div class="bg-white p-4 rounded-lg shadow-lg mb-6 flex space-x-3 items-center">
        {{-- Search Input --}}
        <div class="relative flex-grow border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">🔍</span>
            <input type="text" placeholder="Cari arsip berdasarkan judul atau kategori..." class="w-full py-2 pl-10 pr-4 rounded-lg focus:outline-none text-sm">
        </div>
        
        {{-- Ikon Filter --}}
        <button class="text-gray-500 hover:text-blue-600 p-2 rounded-lg border border-gray-300">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10h18M3 16h18M3 22h18"></path></svg>
        </button>

        {{-- Select Kategori --}}
        <select class="py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <option selected disabled>Semua Kategori</option>
            <option>Surat Keputusan</option>
            <option>Laporan Keuangan</option>
            <option>Dokumentasi</option>
        </select>
        
        {{-- Select Unit --}}
        <select class="py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <option selected disabled>Semua Unit</option>
            <option>Diskominfo</option>
            <option>Sekretariat</option>
        </select>
    </div>

    {{-- List Arsip (Card View) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- PERBAIKAN DI SINI: Menggunakan $archives --}}
        @foreach($archives as $archive)
        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-blue-600"> 
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold text-gray-800 line-clamp-2">{{ $archive->judul }}</h3>
                
                {{-- Logika Star/Favorite --}}
                <span class="ml-3">
                    @if(isset($archive->favorite) && $archive->favorite)
                        <svg class="w-6 h-6 fill-current text-yellow-500" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 15l-5.878 3.09 1.123-6.545L.487 7.098l6.561-.955L10 1l2.952 5.143 6.561.955-4.758 4.647 1.123 6.545L10 15z"/></svg>
                    @else
                        <svg class="w-6 h-6 text-gray-300 hover:text-yellow-500 transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.62-.921 1.92 0l3.07 9.488a1 1 0 00.95.691h9.919a1 1 0 01.95.691l-3.07 9.488a1 1 0 00.95.691h-9.919a1 1 0 01.95.691z"></path></svg>
                    @endif
                </span>
            </div>
            
            <p class="text-xs text-gray-600 mb-2">ID: <span class="font-mono text-red-600">{{ $archive->id }}</span></p>
            <div class="flex space-x-2 text-xs mb-3">
                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-medium">{{ $archive->kategori }}</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded font-medium">{{ $archive->unit }}</span>
            </div>

            <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 border-t pt-3">
                <div class="flex flex-col">
                    <span class="text-xs">Tipe:</span> 
                    <span class="font-medium text-gray-800">{{ $archive->tipe }}</span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-xs">Ukuran:</span> 
                    <span class="font-medium text-gray-800">{{ $archive->ukuran }}</span>
                </div>
                <div class="col-span-2 flex flex-col">
                    <span class="text-xs">Tanggal:</span> 
                    <span class="font-medium text-gray-800">{{ $archive->tanggal }}</span>
                </div>
            </div>

            <div class="mt-4 flex justify-between space-x-2 border-t pt-3">
                <button class="flex-1 flex items-center justify-center p-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Preview
                </button>
                <button class="flex-1 flex items-center justify-center p-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download
                </button>
                <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </button>
            </div>
        </div>
        @endforeach
        {{-- Catatan: Jika Anda ingin menangani kasus kosong, gunakan @forelse/@empty --}}
    </div>

</div>
{{-- Include Upload Modal --}}
@include('admin.arsip.upload-modal')
@endsection