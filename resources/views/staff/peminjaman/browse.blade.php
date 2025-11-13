@extends('admin.layouts.app')

@section('title', 'Cari & Pinjam Aset')

@section('content')
<div class="p-6">

    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🔍 Cari & Pinjam Aset</h1>
        <p class="text-sm text-gray-500 mt-1">Browse aset dari unit lain yang bisa dipinjam</p>
    </div>

    {{-- Filter Section --}}
    <form action="{{ route('staff.peminjaman.browse') }}" method="GET" class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            {{-- Search --}}
            <div class="md:col-span-2">
                <div class="relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama aset, kode, atau merk..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            {{-- Kategori --}}
            <div>
                <select name="kategori" class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Unit --}}
            <div>
                <select name="unit" class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>
                            {{ $unit }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="flex gap-2 mt-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                🔍 Cari
            </button>
            <a href="{{ route('staff.peminjaman.browse') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Reset
            </a>
        </div>
    </form>

    {{-- Assets Grid --}}
    @if($assets->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
        @foreach($assets as $asset)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
            {{-- Header Card dengan Foto --}}
            <div class="relative h-48 bg-gray-100">
                @if($asset->foto)
                    <img src="{{ asset('storage/' . $asset->foto) }}" 
                         alt="{{ $asset->nama }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-gray-100">
                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                @endif
                
                {{-- Badge Tersedia --}}
                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 rounded-full text-white text-xs font-semibold shadow-lg bg-green-500">
                        Tersedia
                    </span>
                </div>
            </div>

            {{-- Body Card --}}
            <div class="p-4">
                {{-- Nama & Kode Aset --}}
                <h3 class="text-lg font-bold text-gray-800 mb-1 truncate" title="{{ $asset->nama }}">
                    {{ $asset->nama }}
                </h3>
                <p class="text-sm text-gray-500 mb-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 font-mono">
                        {{ $asset->kode_asset }}
                    </span>
                </p>

                {{-- Info Grid --}}
                <div class="space-y-2 mb-4 text-sm">
                    {{-- Kategori --}}
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Kategori:</span>
                        <span class="font-medium text-gray-800">{{ $asset->kategori }}</span>
                    </div>
                    
                    {{-- Merk --}}
                    @if($asset->merk)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Merk:</span>
                        <span class="font-medium text-gray-800">{{ $asset->merk }}</span>
                    </div>
                    @endif

                    {{-- Unit Pemilik --}}
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Unit Pemilik:</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $asset->unit ?? '-' }}
                        </span>
                    </div>

                    {{-- Kondisi --}}
                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                        <span class="text-gray-600">Kondisi:</span>
                        @php
                            $kondisiColors = [
                                'baik' => 'bg-green-100 text-green-800',
                                'cukup' => 'bg-blue-100 text-blue-800',
                                'kurang' => 'bg-yellow-100 text-yellow-800',
                                'rusak' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $kondisiColors[$asset->kondisi] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($asset->kondisi) }}
                        </span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-3 border-t border-gray-100 space-y-2">
                    {{-- Button Pinjam (Full Width) --}}
                    <a href="{{ route('staff.peminjaman.create', ['asset_id' => $asset->id]) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Ajukan Peminjaman</span>
                    </a>

                    {{-- Button Detail --}}
                    <a href="{{ route('staff.aset.show', $asset->id) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>Lihat Detail</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($assets->hasPages())
    <div class="bg-white rounded-lg shadow px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan <span class="font-medium">{{ $assets->firstItem() }}</span> sampai 
                <span class="font-medium">{{ $assets->lastItem() }}</span> dari 
                <span class="font-medium">{{ $assets->total() }}</span> aset
            </div>
            <div>
                {{ $assets->links() }}
            </div>
        </div>
    </div>
    @endif

    @else
    {{-- Empty State --}}
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Aset Tersedia</h3>
        <p class="text-gray-500 mb-6">Tidak ada aset yang bisa dipinjam saat ini atau coba ubah filter pencarian</p>
        <a href="{{ route('staff.peminjaman.browse') }}" 
           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            Reset Filter
        </a>
    </div>
    @endif

</div>
@endsection