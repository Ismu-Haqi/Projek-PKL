@extends('staff.layouts.app')

@section('title', 'Disposisi Digital')

@section('content')
<div class="p-6">
    
    {{-- Header dan Tombol Aksi --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Disposisi Digital</h1>
            <p class="text-sm text-gray-500">Kelola dan tindak lanjuti dokumen disposisi yang masuk</p>
        </div>
        
        {{-- Tombol Tambah Disposisi (Aksi Cepat) --}}
        <button type="button" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-md flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Disposisi
        </button>
    </div>

    {{-- Filter dan Search Bar --}}
    <div class="bg-white p-4 rounded-lg shadow-lg mb-6 flex space-x-3 items-center">
        {{-- Search Input --}}
        <div class="relative flex-grow border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">🔍</span>
            <input type="text" placeholder="Cari disposisi berdasarkan surat, pengirim, atau status..." class="w-full py-2 pl-10 pr-4 rounded-lg focus:outline-none text-sm">
        </div>
        
        {{-- Filter Status --}}
        <select class="py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <option selected disabled>Semua Status</option>
            <option>Baru</option>
            <option>Diproses</option>
            <option>Selesai</option>
        </select>
    </div>

    {{-- Tabel Disposisi Placeholder --}}
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold mb-4">Daftar Disposisi Masuk</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surat/Dokumen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengirim</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Diterima</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{-- Memastikan variabel $dispositions ada --}}
                @forelse ($dispositions ?? [] as $disposition)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $disposition->surat ?? 'Surat Penting' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $disposition->dari ?? 'Kepala Dinas' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">2025-10-01</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($disposition->status ?? 'Baru') == 'Baru' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $disposition->status ?? 'Baru' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Tindak Lanjut</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada disposisi yang masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection