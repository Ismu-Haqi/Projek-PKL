@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-5xl">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route(Auth::user()->role . '.aset.index') }}" class="hover:text-blue-600">Manajemen Aset</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-800 font-medium">Detail Aset</span>
        </div>
        
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $asset->nama }}</h1>
                <p class="text-gray-600 mt-2">{{ $asset->kode_asset }}</p>
            </div>
            
            @if(Auth::user()->role === 'admin')
            <div class="flex gap-2">
                <a href="{{ route('admin.aset.edit', $asset->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column - Photo & QR -->
        <div class="lg:col-span-1">
            <!-- Foto Aset -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📸 Foto Aset</h3>
                @if($asset->foto)
                    <img src="{{ asset('storage/' . $asset->foto) }}" alt="{{ $asset->nama }}" 
                         class="w-full rounded-lg shadow-md">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-center text-gray-500 mt-4">Tidak ada foto</p>
                @endif
            </div>

            <!-- QR Code -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">🔲 QR Code</h3>
                @if($asset->qr_code && Storage::disk('public')->exists($asset->qr_code))
                    <img src="{{ asset('storage/' . $asset->qr_code) }}" alt="QR Code" 
                         class="w-full max-w-xs mx-auto rounded-lg shadow-md">
                    <a href="{{ route('admin.aset.downloadQr', $asset->id) }}" 
                       class="block mt-4 text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Download QR Code
                    </a>
                @else
                    <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                        <p class="text-gray-500">QR Code belum dibuat</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Status & Kondisi -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">🔧 Status & Kondisi</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium bg-{{ $asset->status_badge['color'] }}-100 text-{{ $asset->status_badge['color'] }}-700">
                            {{ $asset->status_badge['text'] }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kondisi</p>
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium bg-{{ $asset->kondisi_badge['color'] }}-100 text-{{ $asset->kondisi_badge['color'] }}-700">
                            {{ $asset->kondisi_badge['text'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informasi Dasar -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Informasi Dasar</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Kategori</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $asset->kategori }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Merk</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $asset->merk ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tipe/Model</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $asset->tipe ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Serial Number</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $asset->serial_number ?? '-' }}</p>
                    </div>
                    @if($asset->spesifikasi)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Spesifikasi</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $asset->spesifikasi }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Lokasi & Penempatan -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📍 Lokasi & Penempatan</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Lokasi</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $asset->lokasi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Unit Kerja</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $asset->unit ?? '-' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Penanggung Jawab</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $asset->penanggung_jawab ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Pembelian & Garansi -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">💰 Pembelian & Garansi</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pembelian</p>
                        <p class="font-medium text-gray-800 mt-1">
                            {{ $asset->tanggal_pembelian ? $asset->tanggal_pembelian->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Harga Pembelian</p>
                        <p class="font-medium text-gray-800 mt-1">
                            {{ $asset->harga_pembelian ? 'Rp ' . number_format($asset->harga_pembelian, 0, ',', '.') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Masa Garansi</p>
                        <p class="font-medium text-gray-800 mt-1">
                            {{ $asset->masa_garansi ? $asset->masa_garansi . ' bulan' : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Sisa Garansi</p>
                        <p class="font-medium text-gray-800 mt-1">
                            @if($asset->isGaransiBerlaku())
                                <span class="text-green-600">{{ $asset->sisa_garansi }}</span>
                            @else
                                <span class="text-red-600">{{ $asset->sisa_garansi }}</span>
                            @endif
                        </p>
                    </div>
                    @if($asset->tanggal_pembelian)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Umur Aset</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $asset->umur_asset }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Keterangan -->
            @if($asset->keterangan)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📝 Keterangan</h3>
                <p class="text-gray-700">{{ $asset->keterangan }}</p>
            </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">🕒 Timeline</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></div>
                        <div>
                            <p class="text-sm text-gray-600">Ditambahkan</p>
                            <p class="font-medium text-gray-800">{{ $asset->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                        <div>
                            <p class="text-sm text-gray-600">Terakhir Diupdate</p>
                            <p class="font-medium text-gray-800">{{ $asset->updated_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route(Auth::user()->role . '.aset.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>

        @if(Auth::user()->role === 'admin')
        <div class="flex gap-2">
            <a href="{{ route('admin.aset.edit', $asset->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Aset
            </a>
            
            <form action="{{ route('admin.aset.destroy', $asset->id) }}" method="POST" class="inline"
                  onsubmit="return confirm('Yakin ingin menghapus aset ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
        @endif
    </div>

</div>
@endsection