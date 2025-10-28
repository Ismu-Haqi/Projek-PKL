@extends('admin.layouts.app')

@section('title', 'Manajemen Kategori Arsip')

@section('content')
<div class="p-6">
    
    {{-- Header dan Tombol Tambah --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Kategori Arsip</h1>
            <p class="text-sm text-gray-500">Kelola dan atur kategori untuk klasifikasi arsip digital</p>
        </div>
        
        {{-- Tombol Tambah Kategori (Placeholder Modal) --}}
        <button type="button" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-md flex items-center"
                data-modal-target="addCategoryModal" data-modal-toggle="addCategoryModal">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Kategori
        </button>
    </div>

    {{-- Filter dan Search Bar --}}
    <div class="bg-white p-4 rounded-lg shadow-lg mb-6 flex space-x-3 items-center">
        {{-- Search Input --}}
        <div class="relative flex-grow border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">🔍</span>
            <input type="text" placeholder="Cari kategori berdasarkan nama atau kode..." class="w-full py-2 pl-10 pr-4 rounded-lg focus:outline-none text-sm">
        </div>
        
        {{-- Filter Status --}}
        <select class="py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <option selected disabled>Semua Status</option>
            <option>Aktif</option>
            <option>Non-Aktif</option>
        </select>
        
        {{-- Tombol Filter --}}
        <button class="text-gray-500 hover:text-blue-600 p-2 rounded-lg border border-gray-300">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10h18M3 16h18M3 22h18"></path></svg>
        </button>
    </div>

    {{-- Tabel Kategori (Placeholder) --}}
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold mb-4">Daftar Kategori</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                
                @php
                    $categories = [
                        (object)['kode' => 'SKP', 'nama' => 'Surat Keputusan', 'desc' => 'Dokumen penetapan kebijakan.', 'status' => 'Aktif'],
                        (object)['kode' => 'LPR', 'nama' => 'Laporan Keuangan', 'desc' => 'Dokumen hasil rekonsiliasi dan audit.', 'status' => 'Aktif'],
                    ];
                @endphp
                
                @foreach ($categories as $category)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->kode }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->nama }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $category->desc }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection