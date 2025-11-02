@extends('admin.layouts.app')

@section('title', 'Manajemen Aset')

@section('content')
<div class="p-6">

    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📦 Manajemen Aset</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola dan monitor aset organisasi Anda</p>
        </div>
        
        @if(Auth::user()->role === 'admin')
        {{-- Tombol Tambah Aset --}}
        <a href="{{ route('admin.aset.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg flex items-center transition duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Aset
        </a>
        @endif
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Aset</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Tersedia</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['tersedia'] }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Digunakan</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['digunakan'] }}</h3>
                </div>
                <div class="bg-blue-50 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Diperbaiki</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['diperbaiki'] }}</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Rusak</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['rusak'] }}</h3>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <form action="{{ route('admin.aset.index') }}" method="GET" class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
            {{-- Search --}}
            <div class="md:col-span-2">
                <div class="relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama, kode, atau serial number..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            {{-- Kategori --}}
            <div>
                <select name="kategori" class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div>
                <select name="status" class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="digunakan" {{ request('status') == 'digunakan' ? 'selected' : '' }}>Digunakan</option>
                    <option value="diperbaiki" {{ request('status') == 'diperbaiki' ? 'selected' : '' }}>Diperbaiki</option>
                    <option value="rusak" {{ request('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>

            {{-- Unit --}}
            <div>
                <select name="unit" class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>
                            {{ $unit }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </button>
                <a href="{{ route('admin.aset.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </a>
            </div>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                @endif
                
                {{-- Badge Status --}}
                <div class="absolute top-3 right-3">
                    @php
                        $statusColors = [
                            'tersedia' => 'bg-green-500',
                            'digunakan' => 'bg-blue-500',
                            'diperbaiki' => 'bg-yellow-500',
                            'rusak' => 'bg-red-500'
                        ];
                        $statusLabels = [
                            'tersedia' => 'Tersedia',
                            'digunakan' => 'Digunakan',
                            'diperbaiki' => 'Diperbaiki',
                            'rusak' => 'Rusak'
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-white text-xs font-semibold shadow-lg {{ $statusColors[$asset->status] ?? 'bg-gray-500' }}">
                        {{ $statusLabels[$asset->status] ?? ucfirst($asset->status) }}
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
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Merk:</span>
                        <span class="font-medium text-gray-800">{{ $asset->merk ?? '-' }}</span>
                    </div>

                    {{-- Lokasi --}}
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Lokasi:</span>
                        <span class="font-medium text-gray-800 truncate max-w-[150px]" title="{{ $asset->lokasi }}">
                            {{ $asset->lokasi ?? '-' }}
                        </span>
                    </div>

                    {{-- Unit --}}
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Unit:</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
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
                    {{-- Button Detail (Full Width) --}}
                    <a href="{{ route('admin.aset.show', $asset->id) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>Lihat Detail</span>
                    </a>

                    @if(Auth::user()->role === 'admin')
                    {{-- Button Edit & Hapus (1 Baris) --}}
                    <div class="flex gap-2">
                        <a href="{{ route('admin.aset.edit', $asset->id) }}" 
                           class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors duration-200 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>Edit</span>
                        </a>

                        <form action="{{ route('admin.aset.destroy', $asset->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    onclick="confirmDelete(this, 'Aset <strong>{{ $asset->nama }}</strong> akan dihapus permanen!')"
                                    class="w-full flex items-center justify-center gap-1.5 px-3 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                    @endif
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Aset</h3>
        <p class="text-gray-500 mb-6">Mulai tambahkan aset organisasi Anda dengan klik tombol di atas</p>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.aset.create') }}" 
           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Aset Pertama
        </a>
        @endif
    </div>
    @endif

</div>

{{-- Include SweetAlert --}}
@include('partials.sweetalert')

@endsection