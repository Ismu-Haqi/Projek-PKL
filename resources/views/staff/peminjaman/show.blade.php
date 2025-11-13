@extends('staff.layouts.app')

@section('title', 'Detail Peminjaman - ' . $borrow->kode_peminjaman)

@section('content')
<div class="container mx-auto px-4 py-6 max-w-6xl">
    
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('staff.peminjaman.index') }}" class="hover:text-blue-600">Peminjaman Saya</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-blue-600 font-medium">{{ $borrow->kode_peminjaman }}</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Detail Peminjaman</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap peminjaman aset</p>
            </div>
            
            {{-- Status Badge --}}
            @php
                $badge = $borrow->status_badge;
            @endphp
            <span class="px-4 py-2 text-sm font-semibold rounded-full bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-800 border-2 border-{{ $badge['color'] }}-200">
                {{ $badge['text'] }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Informasi Peminjaman --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Informasi Aset --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Informasi Aset
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex gap-4">
                        {{-- Foto Aset --}}
                        <div class="flex-shrink-0">
                            @if($borrow->asset->foto)
                                <img src="{{ asset('storage/' . $borrow->asset->foto) }}" 
                                     alt="{{ $borrow->asset->nama }}" 
                                     class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                            @else
                                <div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-gray-200">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Detail Aset --}}
                        <div class="flex-1 space-y-3">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">{{ $borrow->asset->nama }}</h3>
                                <p class="text-sm text-gray-600 font-mono bg-gray-100 inline-block px-2 py-1 rounded mt-1">
                                    {{ $borrow->asset->kode_asset }}
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-500 block">Kategori</span>
                                    <span class="font-medium text-gray-800">{{ $borrow->asset->kategori }}</span>
                                </div>
                                @if($borrow->asset->merk)
                                <div>
                                    <span class="text-gray-500 block">Merk</span>
                                    <span class="font-medium text-gray-800">{{ $borrow->asset->merk }}</span>
                                </div>
                                @endif
                                <div>
                                    <span class="text-gray-500 block">Unit Pemilik</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $borrow->asset->unit }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block">Kondisi</span>
                                    @php
                                        $kondisiColors = [
                                            'baik' => 'bg-green-100 text-green-800',
                                            'cukup' => 'bg-blue-100 text-blue-800',
                                            'kurang' => 'bg-yellow-100 text-yellow-800',
                                            'rusak' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $kondisiColors[$borrow->asset->kondisi] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($borrow->asset->kondisi) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Peminjaman --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Detail Peminjaman
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500 block mb-1">Tanggal Pengajuan</span>
                            <span class="font-medium text-gray-800">{{ $borrow->tanggal_pengajuan->format('d M Y, H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block mb-1">Tanggal Rencana Kembali</span>
                            <span class="font-medium text-gray-800">{{ $borrow->tanggal_kembali_rencana->format('d M Y') }}</span>
                        </div>

                        @if($borrow->tanggal_persetujuan)
                        <div>
                            <span class="text-sm text-gray-500 block mb-1">Tanggal Persetujuan</span>
                            <span class="font-medium text-green-600">{{ $borrow->tanggal_persetujuan->format('d M Y, H:i') }}</span>
                        </div>
                        @endif

                        @if($borrow->tanggal_peminjaman)
                        <div>
                            <span class="text-sm text-gray-500 block mb-1">Tanggal Peminjaman</span>
                            <span class="font-medium text-blue-600">{{ $borrow->tanggal_peminjaman->format('d M Y, H:i') }}</span>
                        </div>
                        @endif

                        @if($borrow->tanggal_kembali_aktual)
                        <div>
                            <span class="text-sm text-gray-500 block mb-1">Tanggal Pengembalian</span>
                            <span class="font-medium text-purple-600">{{ $borrow->tanggal_kembali_aktual->format('d M Y, H:i') }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Keperluan --}}
                    <div class="pt-4 border-t border-gray-200">
                        <span class="text-sm text-gray-500 block mb-2">Keperluan Peminjaman</span>
                        <p class="text-gray-800 bg-gray-50 p-3 rounded-lg">{{ $borrow->keperluan }}</p>
                    </div>

                    {{-- Catatan Peminjam --}}
                    @if($borrow->catatan_peminjam)
                    <div>
                        <span class="text-sm text-gray-500 block mb-2">Catatan Peminjam</span>
                        <p class="text-gray-800 bg-blue-50 p-3 rounded-lg">{{ $borrow->catatan_peminjam }}</p>
                    </div>
                    @endif

                    {{-- Catatan Persetujuan --}}
                    @if($borrow->catatan_persetujuan)
                    <div>
                        <span class="text-sm text-gray-500 block mb-2">Catatan Admin</span>
                        <p class="text-gray-800 bg-yellow-50 p-3 rounded-lg border-l-4 border-yellow-400">
                            {{ $borrow->catatan_persetujuan }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Foto Kondisi (jika ada) --}}
            @if($borrow->foto_kondisi_pinjam || $borrow->foto_kondisi_kembali)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Dokumentasi Kondisi
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($borrow->foto_kondisi_pinjam)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Kondisi Saat Dipinjam</h3>
                            <img src="{{ asset('storage/' . $borrow->foto_kondisi_pinjam) }}" 
                                 alt="Kondisi Pinjam" 
                                 class="w-full h-48 object-cover rounded-lg border-2 border-gray-200 cursor-pointer hover:opacity-90"
                                 onclick="window.open(this.src, '_blank')">
                        </div>
                        @endif

                        @if($borrow->foto_kondisi_kembali)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Kondisi Saat Dikembalikan</h3>
                            <img src="{{ asset('storage/' . $borrow->foto_kondisi_kembali) }}" 
                                 alt="Kondisi Kembali" 
                                 class="w-full h-48 object-cover rounded-lg border-2 border-gray-200 cursor-pointer hover:opacity-90"
                                 onclick="window.open(this.src, '_blank')">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Right Column: Timeline & Actions --}}
        <div class="space-y-6">
            
            {{-- Info Card --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800">Informasi</h2>
                </div>
                <div class="p-6 space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Kode Peminjaman</span>
                        <span class="font-mono font-bold text-blue-600">{{ $borrow->kode_peminjaman }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t">
                        <span class="text-gray-600">Peminjam</span>
                        <span class="font-medium text-gray-800">{{ $borrow->borrower->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Unit</span>
                        <span class="font-medium text-gray-800">{{ $borrow->borrower_unit }}</span>
                    </div>
                    @if($borrow->approver)
                    <div class="flex justify-between items-center pt-3 border-t">
                        <span class="text-gray-600">Disetujui oleh</span>
                        <span class="font-medium text-gray-800">{{ $borrow->approver->name }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            @if(in_array($borrow->status, ['pending', 'rejected']))
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-red-50 to-orange-50 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800">Aksi</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('staff.peminjaman.destroy', $borrow->id) }}" method="POST" id="cancelForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                onclick="confirmDelete(document.getElementById('cancelForm').querySelector('button'), 'Batalkan peminjaman {{ $borrow->kode_peminjaman }}?')"
                                class="w-full bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batalkan Pengajuan
                        </button>
                    </form>
                    <p class="text-xs text-gray-500 mt-2 text-center">
                        Pembatalan hanya bisa dilakukan saat status pending atau ditolak
                    </p>
                </div>
            </div>
            @endif

            {{-- Timeline Status --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800">Timeline</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        {{-- Pengajuan --}}
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">Pengajuan Dibuat</p>
                                <p class="text-sm text-gray-500">{{ $borrow->tanggal_pengajuan->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        {{-- Persetujuan --}}
                        @if($borrow->tanggal_persetujuan)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">Disetujui</p>
                                <p class="text-sm text-gray-500">{{ $borrow->tanggal_persetujuan->format('d M Y, H:i') }}</p>
                                @if($borrow->approver)
                                <p class="text-xs text-gray-400">oleh {{ $borrow->approver->name }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Peminjaman --}}
                        @if($borrow->tanggal_peminjaman)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">Aset Dipinjam</p>
                                <p class="text-sm text-gray-500">{{ $borrow->tanggal_peminjaman->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        {{-- Pengembalian --}}
                        @if($borrow->tanggal_kembali_aktual)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">Dikembalikan</p>
                                <p class="text-sm text-gray-500">{{ $borrow->tanggal_kembali_aktual->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        {{-- Status Rejected --}}
                        @if($borrow->status === 'rejected')
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">Ditolak</p>
                                @if($borrow->tanggal_persetujuan)
                                <p class="text-sm text-gray-500">{{ $borrow->tanggal_persetujuan->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Back Button --}}
            <a href="{{ route('staff.peminjaman.index') }}" 
               class="block w-full bg-gray-600 text-white px-4 py-3 rounded-lg hover:bg-gray-700 transition text-center font-medium">
                ← Kembali ke Daftar
            </a>

        </div>
    </div>
</div>

@include('partials.sweetalert')

@endsection