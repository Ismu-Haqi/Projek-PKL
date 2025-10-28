@extends('staff.layouts.app')

@section('title', 'Detail Aset')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    {{-- Header with Breadcrumb --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('staff.aset.index') }}" class="hover:text-orange-600 transition-colors">Manajemen Aset</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-800 font-medium">Detail Aset</span>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-800">{{ $asset->nama }}</h1>
            <p class="text-gray-600 mt-2 font-mono text-sm">{{ $asset->kode_asset }}</p>
        </div>
        
        <a href="{{ route('staff.aset.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column - Photo & QR --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Asset Photo --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Foto Aset
                    </h3>
                </div>
                <div class="p-6">
                    @if($asset->foto)
                        <img src="{{ asset('storage/' . $asset->foto) }}" alt="{{ $asset->nama }}" 
                             class="w-full rounded-lg shadow-md">
                    @else
                        <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-center text-gray-500 mt-4 text-sm">Tidak ada foto</p>
                    @endif
                </div>
            </div>

            {{-- QR Code --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        QR Code
                    </h3>
                </div>
                <div class="p-6">
                    @if($asset->qr_code && Storage::disk('public')->exists($asset->qr_code))
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <img src="{{ asset('storage/' . $asset->qr_code) }}" alt="QR Code" 
                                 class="w-full max-w-xs mx-auto rounded-lg">
                        </div>
                        <a href="{{ route('staff.aset.downloadQr', $asset->id) }}" 
                           class="block mt-4 text-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all font-medium">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download QR Code
                        </a>
                    @else
                        <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                            <p class="text-gray-500 text-sm">QR Code belum dibuat</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column - Details --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Status & Kondisi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status & Kondisi
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-2">Status</p>
                            @php
                                $statusConfig = [
                                    'tersedia' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200', 'label' => 'Tersedia'],
                                    'digunakan' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'label' => 'Digunakan'],
                                    'diperbaiki' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'label' => 'Diperbaiki'],
                                    'rusak' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200', 'label' => 'Rusak']
                                ];
                                $status = $statusConfig[$asset->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'label' => ucfirst($asset->status)];
                            @endphp
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold border {{ $status['bg'] }} {{ $status['text'] }} {{ $status['border'] }}">
                                {{ $status['label'] }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-2">Kondisi</p>
                            @php
                                $kondisiConfig = [
                                    'baik' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200'],
                                    'cukup' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                                    'kurang' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200'],
                                    'rusak' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200']
                                ];
                                $kondisi = $kondisiConfig[$asset->kondisi] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200'];
                            @endphp
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold border {{ $kondisi['bg'] }} {{ $kondisi['text'] }} {{ $kondisi['border'] }}">
                                {{ ucfirst($asset->kondisi) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Dasar --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Informasi Dasar
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Kategori</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ $asset->kategori }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Merk</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ $asset->merk ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Tipe/Model</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ $asset->tipe ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Serial Number</p>
                            <p class="font-semibold text-gray-800 mt-1 font-mono text-sm">{{ $asset->serial_number ?? '-' }}</p>
                        </div>
                        @if($asset->spesifikasi)
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600 font-medium">Spesifikasi</p>
                            <p class="font-medium text-gray-800 mt-1">{{ $asset->spesifikasi }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Lokasi & Penempatan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Lokasi & Penempatan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Lokasi</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ $asset->lokasi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Unit Kerja</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ $asset->unit ?? '-' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600 font-medium">Penanggung Jawab</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ $asset->penanggung_jawab ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pembelian & Garansi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pembelian & Garansi
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Tanggal Pembelian</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $asset->tanggal_pembelian ? $asset->tanggal_pembelian->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Harga Pembelian</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $asset->harga_pembelian ? 'Rp ' . number_format($asset->harga_pembelian, 0, ',', '.') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Masa Garansi</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $asset->masa_garansi ? $asset->masa_garansi . ' bulan' : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Sisa Garansi</p>
                            @if($asset->sisa_garansi)
                                @if($asset->isGaransiBerlaku())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700 mt-1">
                                        ✓ {{ $asset->sisa_garansi }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700 mt-1">
                                        ✗ {{ $asset->sisa_garansi }}
                                    </span>
                                @endif
                            @else
                                <p class="font-semibold text-gray-800 mt-1">-</p>
                            @endif
                        </div>
                        @if($asset->tanggal_pembelian)
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600 font-medium">Umur Aset</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ $asset->umur_asset }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Keterangan --}}
            @if($asset->keterangan)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        Keterangan
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 leading-relaxed">{{ $asset->keterangan }}</p>
                </div>
            </div>
            @endif

            {{-- Timeline --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Timeline
                    </h3>
                </div>
                <div class="p-6">
                    <div class="relative space-y-4">
                        <!-- Vertical Line -->
                        <div class="absolute left-5 top-6 bottom-6 w-0.5 bg-gray-200"></div>

                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-4 relative z-10">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                </svg>
                            </div>
                            <div class="flex-1 bg-gray-50 rounded-lg p-4">
                                <p class="font-semibold text-gray-800">Aset Ditambahkan</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $asset->created_at->format('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>

                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-4 relative z-10">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"/>
                                </svg>
                            </div>
                            <div class="flex-1 bg-gray-50 rounded-lg p-4">
                                <p class="font-semibold text-gray-800">Terakhir Diupdate</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $asset->updated_at->format('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection