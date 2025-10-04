@extends('admin.layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="p-6">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan</h1>
            <p class="text-sm text-gray-500">Kelola dan hasilkan laporan aktivitas sistem dan arsip</p>
        </div>
        
        <div class="flex space-x-3">
            {{-- Tombol Tambah Laporan (Untuk membuat Laporan Baru) --}}
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-md flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                Buat Laporan Baru
            </button>
            
            {{-- Tombol Cetak Laporan (Untuk Laporan yang dipilih) --}}
            <button class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg shadow-md flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m0 0v1a2 2 0 002 2h4a2 2 0 002-2v-1m-4 0h-4"></path></svg>
                Cetak Laporan
            </button>
        </div>
    </div>

    {{-- Laporan Preview (Mirip Format Surat Resmi) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Preview Laporan 1: Laporan Arsip per Unit/Bidang --}}
        <div class="bg-white p-8 rounded-lg shadow-2xl border border-gray-100 transform hover:scale-[1.01] transition duration-300">
            <div class="text-center mb-6">
                <img src="https://ui-avatars.com/api/?name=Diskominfo&background=2563eb&color=fff&size=50" alt="Logo" class="mx-auto w-12 h-12 mb-2">
                <p class="text-sm font-semibold text-gray-800">DISKOMINFO BARITO KUALA</p>
                <p class="text-xs text-gray-500">Jl. Jend Sudirman No.74, Marabahan</p>
                <hr class="mt-4 border-blue-500">
            </div>

            <p class="text-sm font-medium mb-1">Perihal: <span class="font-normal">Laporan Arsip per Unit/Bidang Diskominfo</span></p>
            <p class="text-sm mb-4">Kepada Yth. Kepala Dinas / Sekretaris Diskominfo</p>

            {{-- Tabel Placeholder --}}
            <table class="min-w-full divide-y divide-gray-200 border mt-4">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-2 text-xs font-semibold">Unit/Bidang</th>
                        <th class="px-4 py-2 text-xs font-semibold">Jumlah Arsip</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-gray-700 text-sm">
                    <tr><td class="px-4 py-2">Bidang Infrastruktur TIK</td><td class="px-4 py-2 text-center">150</td></tr>
                    <tr><td class="px-4 py-2">Bidang Persandian</td><td class="px-4 py-2 text-center">300</td></tr>
                </tbody>
            </table>
        </div>

        {{-- Preview Laporan 2: Laporan Statistik Peminjaman Aset --}}
        <div class="bg-white p-8 rounded-lg shadow-2xl border border-gray-100 transform hover:scale-[1.01] transition duration-300">
            <div class="text-center mb-6">
                <img src="https://ui-avatars.com/api/?name=Diskominfo&background=2563eb&color=fff&size=50" alt="Logo" class="mx-auto w-12 h-12 mb-2">
                <p class="text-sm font-semibold text-gray-800">DISKOMINFO BARITO KUALA</p>
                <hr class="mt-4 border-blue-500">
            </div>

            <p class="text-sm font-medium mb-1">Perihal: <span class="font-normal">Laporan Statistik Peminjaman Aset</span></p>
            <p class="text-sm mb-4">Kepada Yth. Kepala Bidang</p>

            {{-- Tabel Placeholder --}}
            <table class="min-w-full divide-y divide-gray-200 border mt-4">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-3 py-2 text-xs font-semibold">Unit/Bidang</th>
                        <th class="px-3 py-2 text-xs font-semibold">Frekuensi</th>
                        <th class="px-3 py-2 text-xs font-semibold">Tingkat Kembali</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-gray-700 text-sm">
                    <tr><td class="px-3 py-2">Bidang Infrastruktur TIK</td><td class="px-3 py-2 text-center">40</td><td class="px-3 py-2 text-center">85%</td></tr>
                    <tr><td class="px-3 py-2">Bidang Persandian</td><td class="px-3 py-2 text-center">50</td><td class="px-3 py-2 text-center">90%</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection