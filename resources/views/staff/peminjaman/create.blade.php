@extends('staff.layouts.app')

@section('title', 'Ajukan Peminjaman Aset')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('staff.peminjaman.index') }}" class="hover:text-blue-600">Peminjaman Saya</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-blue-600 font-medium">Ajukan Peminjaman</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">📝 Form Pengajuan Peminjaman</h1>
        <p class="text-gray-600 mt-1">Isi formulir untuk mengajukan peminjaman aset</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <form action="{{ route('staff.peminjaman.store') }}" method="POST" id="borrowForm">
            @csrf

            {{-- Pilih Aset --}}
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Pilih Aset yang Akan Dipinjam
                </h2>

                <div class="space-y-4">
                    @if(isset($asset) && $asset)
                        {{-- Aset sudah dipilih dari browse --}}
                        <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                        
                        <div class="bg-white rounded-lg border-2 border-blue-500 p-4">
                            <div class="flex items-start gap-4">
                                {{-- Foto Aset --}}
                                <div class="flex-shrink-0">
                                    @if($asset->foto)
                                        <img src="{{ asset('storage/' . $asset->foto) }}" 
                                             alt="{{ $asset->nama }}" 
                                             class="w-24 h-24 object-cover rounded-lg">
                                    @else
                                        <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Info Aset --}}
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 text-lg">{{ $asset->nama }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $asset->kode_asset }}</p>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div>
                                            <span class="text-gray-500">Kategori:</span>
                                            <span class="font-medium text-gray-800 ml-1">{{ $asset->kategori }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Unit Pemilik:</span>
                                            <span class="font-medium text-gray-800 ml-1">{{ $asset->unit }}</span>
                                        </div>
                                        @if($asset->merk)
                                        <div>
                                            <span class="text-gray-500">Merk:</span>
                                            <span class="font-medium text-gray-800 ml-1">{{ $asset->merk }}</span>
                                        </div>
                                        @endif
                                        <div>
                                            <span class="text-gray-500">Kondisi:</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 ml-1">
                                                {{ ucfirst($asset->kondisi) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Checkmark --}}
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('staff.peminjaman.browse') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Ganti Aset
                        </a>

                    @else
                        {{-- Pilih aset dari dropdown --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Aset <span class="text-red-500">*</span>
                            </label>
                            <select name="asset_id" id="assetSelect" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('asset_id') border-red-500 @enderror">
                                <option value="">-- Pilih Aset yang Akan Dipinjam --</option>
                                @foreach($assets as $assetOption)
                                    <option value="{{ $assetOption->id }}" 
                                            data-unit="{{ $assetOption->unit }}"
                                            data-kondisi="{{ $assetOption->kondisi }}">
                                        {{ $assetOption->nama }} ({{ $assetOption->kode_asset }}) - {{ $assetOption->unit }}
                                    </option>
                                @endforeach
                            </select>
                            @error('asset_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium mb-1">Belum menemukan aset yang ingin dipinjam?</p>
                                    <a href="{{ route('staff.peminjaman.browse') }}" class="text-blue-700 hover:text-blue-900 underline font-medium">
                                        Browse katalog aset tersedia →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Detail Peminjaman --}}
            <div class="p-6 space-y-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Detail Peminjaman
                </h2>

                {{-- Tanggal Pengembalian --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Rencana Pengembalian <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_kembali_rencana" required
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           value="{{ old('tanggal_kembali_rencana') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_kembali_rencana') border-red-500 @enderror">
                    @error('tanggal_kembali_rencana')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Pilih tanggal minimal besok</p>
                </div>

                {{-- Keperluan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keperluan Peminjaman <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keperluan" rows="4" required maxlength="500"
                              placeholder="Jelaskan untuk apa aset ini akan digunakan..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keperluan') border-red-500 @enderror">{{ old('keperluan') }}</textarea>
                    @error('keperluan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Maksimal 500 karakter</p>
                </div>

                {{-- Catatan Tambahan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Tambahan (Opsional)
                    </label>
                    <textarea name="catatan_peminjam" rows="3" maxlength="500"
                              placeholder="Catatan tambahan yang perlu diketahui..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('catatan_peminjam') border-red-500 @enderror">{{ old('catatan_peminjam') }}</textarea>
                    @error('catatan_peminjam')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Maksimal 500 karakter</p>
                </div>

                {{-- Info Box --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium mb-1">Perhatian:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Pengajuan akan diproses oleh Admin</li>
                                <li>Anda akan mendapat notifikasi setelah pengajuan disetujui/ditolak</li>
                                <li>Pastikan data yang Anda masukkan sudah benar</li>
                                <li>Aset harus dikembalikan sesuai jadwal yang ditentukan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <a href="{{ route('staff.peminjaman.browse') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>

                <button type="submit" id="submitBtn"
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2 font-medium shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Ajukan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('borrowForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...';
});
</script>
@endpush

@endsection