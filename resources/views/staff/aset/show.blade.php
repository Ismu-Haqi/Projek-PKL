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
            <p class="text-gray-600 mt-2 font-mono text-sm bg-gray-100 inline-block px-3 py-1 rounded">{{ $asset->kode_asset }}</p>
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
                             class="w-full rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer"
                             onclick="window.open(this.src, '_blank')">
                    @else
                        <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-24 h-24 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-500 text-sm">Tidak ada foto</p>
                            </div>
                        </div>
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
                        <div class="bg-white p-4 rounded-lg border-2 border-gray-200 hover:border-blue-400 transition-colors">
                            <img src="{{ asset('storage/' . $asset->qr_code) }}" 
                                 alt="QR Code {{ $asset->kode_asset }}" 
                                 class="w-full max-w-[250px] mx-auto">
                        </div>
                        <p class="text-xs text-center text-gray-500 mt-3 mb-4">Scan untuk info detail aset</p>
                        <a href="{{ route('staff.aset.downloadQr', $asset->id) }}" 
                           class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all font-medium shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download QR Code
                        </a>
                    @else
                        <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                                <p class="text-sm text-gray-500 font-medium mb-2">QR Code belum tersedia</p>
                                <p class="text-xs text-gray-400">Refresh halaman atau hubungi admin</p>
                            </div>
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
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status & Kondisi
                        </h3>
                        
                        {{-- Action Buttons --}}
                        @if($canEdit ?? false)
                        <div class="flex gap-2">
                            <a href="{{ route('staff.aset.edit', $asset->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            
                            @if($canDelete ?? false)
                            <form action="{{ route('staff.aset.destroy', $asset->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus aset {{ $asset->nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                        @else
                        {{-- Info untuk aset dari unit lain --}}
                        <div class="flex items-center text-xs text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Aset dari unit lain (Read-only)</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-2">Status</p>
                            @php
                                $statusConfig = [
                                    'tersedia' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-300', 'label' => 'Tersedia'],
                                    'digunakan' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-300', 'label' => 'Digunakan'],
                                    'maintenance' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-300', 'label' => 'Maintenance'],
                                    'dipinjam' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border' => 'border-orange-300', 'label' => 'Dipinjam'],
                                    'rusak' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-300', 'label' => 'Rusak']
                                ];
                                $status = $statusConfig[$asset->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-300', 'label' => ucfirst($asset->status)];
                            @endphp
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold border-2 {{ $status['bg'] }} {{ $status['text'] }} {{ $status['border'] }}">
                                {{ $status['label'] }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-2">Kondisi</p>
                            @php
                                $kondisiConfig = [
                                    'baik' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-300'],
                                    'cukup' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-300'],
                                    'kurang' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-300'],
                                    'rusak' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-300']
                                ];
                                $kondisi = $kondisiConfig[$asset->kondisi] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-300'];
                            @endphp
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold border-2 {{ $kondisi['bg'] }} {{ $kondisi['text'] }} {{ $kondisi['border'] }}">
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
                            <p class="text-sm text-gray-600 font-medium mb-1">Kategori</p>
                            <p class="font-semibold text-gray-800">{{ $asset->kategori }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-1">Merk</p>
                            <p class="font-semibold text-gray-800">{{ $asset->merk ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-1">Tipe/Model</p>
                            <p class="font-semibold text-gray-800">{{ $asset->tipe ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-1">Serial Number</p>
                            <p class="font-semibold text-gray-800 font-mono text-sm bg-gray-100 inline-block px-2 py-1 rounded">{{ $asset->serial_number ?? '-' }}</p>
                        </div>
                        @if($asset->spesifikasi)
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600 font-medium mb-1">Spesifikasi</p>
                            <p class="text-gray-800 bg-gray-50 p-3 rounded-lg leading-relaxed">{{ $asset->spesifikasi }}</p>
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
                            <p class="text-sm text-gray-600 font-medium mb-1">Lokasi</p>
                            <p class="font-semibold text-gray-800">{{ $asset->lokasi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-1">Unit Kerja</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-purple-100 text-purple-700 border border-purple-200">
                                {{ $asset->unit ?? '-' }}
                            </span>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600 font-medium mb-1">Penanggung Jawab</p>
                            <p class="font-semibold text-gray-800">{{ $asset->penanggung_jawab ?? '-' }}</p>
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
                            <p class="text-sm text-gray-600 font-medium mb-1">Tanggal Pembelian</p>
                            <p class="font-semibold text-gray-800">
                                {{ $asset->tanggal_pembelian ? $asset->tanggal_pembelian->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-1">Harga Pembelian</p>
                            <p class="font-semibold text-gray-800">
                                {{ $asset->harga_pembelian ? 'Rp ' . number_format($asset->harga_pembelian, 0, ',', '.') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-1">Masa Garansi</p>
                            <p class="font-semibold text-gray-800">
                                {{ $asset->masa_garansi ? $asset->masa_garansi . ' bulan' : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-1">Status Garansi</p>
                            @if($asset->sisa_garansi)
                                @if($asset->isGaransiBerlaku())
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-green-100 text-green-700 border border-green-200">
                                        ✓ {{ $asset->sisa_garansi }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-red-100 text-red-700 border border-red-200">
                                        ✗ {{ $asset->sisa_garansi }}
                                    </span>
                                @endif
                            @else
                                <p class="font-semibold text-gray-800">-</p>
                            @endif
                        </div>
                        @if($asset->tanggal_pembelian)
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600 font-medium mb-1">Umur Aset</p>
                            <p class="font-semibold text-gray-800">{{ $asset->umur_asset }}</p>
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
                    <p class="text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg">{{ $asset->keterangan }}</p>
                </div>
            </div>
            @endif

            {{-- Timeline --}}
            <div class="bg-white rounded-xl
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
                        <div class="absolute left-5 top-6 bottom-6 w-0.5 bg-gradient-to-b from-blue-300 to-gray-200"></div>

                        <!-- Aset Ditambahkan -->
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mr-4 relative z-10 shadow-md">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                </svg>
                            </div>
                            <div class="flex-1 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-100">
                                <p class="font-semibold text-gray-800 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Aset Ditambahkan
                                </p>
                                <p class="text-sm text-gray-600 mt-1">{{ $asset->created_at->format('d F Y, H:i') }} WIB</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $asset->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Terakhir Diupdate -->
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center mr-4 relative z-10 shadow-md">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"/>
                                </svg>
                            </div>
                            <div class="flex-1 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border border-green-100">
                                <p class="font-semibold text-gray-800 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Terakhir Diupdate
                                </p>
                                <p class="text-sm text-gray-600 mt-1">{{ $asset->updated_at->format('d F Y, H:i') }} WIB</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $asset->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection