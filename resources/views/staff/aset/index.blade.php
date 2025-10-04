@extends('staff.layouts.app')

@section('title', 'Manajemen Aset')

@section('content')
<div class="p-6">
    
    {{-- Header dan Tombol Tambah Aset --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Aset</h1>
        
        <div class="flex space-x-3">
            {{-- Tombol Tambah Aset --}}
            <button class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg shadow-md flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Aset
            </button>
            
            {{-- Tombol Pinjam Aset (Aksi Cepat) --}}
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-md flex items-center" 
                    data-modal-target="borrowModal" data-modal-toggle="borrowModal">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Pinjam Aset
            </button>
        </div>
    </div>
    
    <p class="text-sm text-gray-500 mb-6">Kelola inventaris dan aset organisasi</p>

    {{-- Filter dan Search Bar --}}
    <div class="bg-white p-4 rounded-lg shadow-lg mb-6 flex space-x-3 items-center">
        {{-- Search Input --}}
        <div class="relative flex-grow border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">🔍</span>
            <input type="text" placeholder="Cari aset berdasarkan nama, kode, atau serial..." class="w-full py-2 pl-10 pr-4 rounded-lg focus:outline-none text-sm">
        </div>
        
        {{-- Filter Kategori --}}
        <select class="py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <option selected disabled>Semua Kategori</option>
            <option>Komputer</option>
            <option>Printer</option>
            <option>Proyektor</option>
        </select>
        
        {{-- Filter Status --}}
        <select class="py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <option selected disabled>Semua Status</option>
            <option>Tersedia</option>
            <option>Digunakan</option>
            <option>Perawatan</option>
            <option>Rusak</option>
        </select>
        
        {{-- Filter Unit --}}
        <select class="py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <option selected disabled>Semua Unit</option>
            <option>Diskominfo</option>
            <option>Sekretariat</option>
        </select>
    </div>

    {{-- Statistik Aset (Stat Card) --}}
    <div class="grid grid-cols-4 gap-6 mb-8 text-center">
        {{-- Memastikan variabel $stats ada, jika tidak, tampilkan 0 --}}
        @php
            $statsData = $stats ?? [ 'Tersedia' => 0, 'Digunakan' => 0, 'Perawatan' => 0, 'Rusak' => 0 ];
            $statColors = ['Tersedia' => 'green', 'Digunakan' => 'blue', 'Perawatan' => 'yellow', 'Rusak' => 'red'];
        @endphp

        @foreach($statsData as $label => $count)
        <div class="bg-white p-5 rounded-lg shadow-md border-t-4 border-{{ $statColors[$label] }}-500">
            <p class="text-3xl font-bold text-{{ $statColors[$label] }}-600">{{ $count }}</p>
            <p class="text-sm text-gray-500">{{ $label }}</p>
        </div>
        @endforeach
    </div>

    {{-- List Aset (Card View) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($assets as $asset)
        <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-gray-200">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-lg font-semibold text-gray-800 leading-snug hover:text-blue-600">{{ $asset->nama }}</h2>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full ml-3 {{ 
                    $asset->status == 'available' ? 'bg-green-100 text-green-700' : 
                    ($asset->status == 'in-use' ? 'bg-blue-100 text-blue-700' : 
                    ($asset->status == 'maintenance' ? 'bg-yellow-100 text-yellow-700' : 
                    'bg-red-100 text-red-700')) 
                }}">
                    {{ $asset->status }}
                </span>
            </div>

            <p class="text-xs font-medium text-gray-500 mb-3">{{ $asset->kode }}</p>
            
            <div class="grid grid-cols-2 gap-y-2 text-sm text-gray-600 border-t pt-3">
                <div class="flex flex-col">
                    <span class="text-xs text-gray-500">Tipe:</span> <span class="font-medium text-gray-800">{{ $asset->tipe }}</span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-xs text-gray-500">Serial:</span> <span class="font-medium text-gray-800">{{ $asset->serial }}</span>
                </div>
                
                <div class="flex flex-col">
                    <span class="text-xs text-gray-500">Lokasi:</span> <span class="font-medium text-gray-800">{{ $asset->lokasi }}</span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-xs text-gray-500">Pembelian:</span> <span class="font-medium text-gray-800">{{ $asset->pembelian }}</span>
                </div>
                
                <div class="col-span-2">
                    <span class="text-xs text-gray-500">Spesifikasi:</span> <span class="font-medium text-gray-800">{{ $asset->spesifikasi }}</span>
                </div>
            </div>

            {{-- Tombol Aksi dan Status Ubah Cepat --}}
            <div class="mt-4 flex justify-between items-center border-t pt-3">
                <div class="flex space-x-1">
                    <button class="text-sm font-medium text-blue-600 hover:text-blue-800">Detail</button>
                    <button class="text-sm font-medium text-gray-600 hover:text-gray-800">Edit</button>
                    <button class="text-sm font-medium text-gray-600 hover:text-gray-800">QR</button>
                </div>

                <div class="flex space-x-1">
                    <span class="text-xs text-gray-500">Ubah Status:</span>
                    <button title="Tersedia" class="text-green-600 hover:text-green-800">✅</button>
                    <button title="Digunakan" class="text-blue-600 hover:text-blue-800">🔷</button>
                    <button title="Perawatan" class="text-yellow-600 hover:text-yellow-800">🔶</button>
                    <button title="Rusak" class="text-red-600 hover:text-red-800">❌</button>
                </div>
            </div>
        </div>
        @empty
        {{-- Jika tidak ada aset yang ditemukan --}}
        <div class="col-span-full text-center py-12 bg-gray-100 rounded-xl">
            <p class="text-gray-500">Tidak ada aset yang ditemukan.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection