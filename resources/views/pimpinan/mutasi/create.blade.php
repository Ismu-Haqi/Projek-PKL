@extends('pimpinan.layouts.app')

@section('title', 'Ajukan Mutasi Aset')

@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('pimpinan.mutasi.index') }}" class="hover:text-blue-600">Mutasi Aset</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">Ajukan Mutasi Baru</span>
    </div>

    <div class="bg-white rounded-xl shadow p-8">
        <h1 class="text-xl font-bold text-gray-800 mb-1">🔄 Ajukan Mutasi Aset</h1>
        <p class="text-sm text-gray-500 mb-6">Isi formulir untuk mengajukan perpindahan aset permanen antar unit/bidang.</p>

        @if($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pimpinan.mutasi.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Pilih Aset --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Aset yang Dimutasi <span class="text-red-500">*</span></label>
                <select name="asset_id" id="asset_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('asset_id') border-red-400 @enderror">
                    <option value="">-- Pilih Aset --</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}"
                            data-unit="{{ $asset->unit }}"
                            data-lokasi="{{ $asset->lokasi }}"
                            {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                            {{ $asset->kode_asset }} — {{ $asset->nama }} (Unit: {{ $asset->unit ?: '-' }})
                        </option>
                    @endforeach
                </select>
                @error('asset_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Unit & Lokasi Asal (readonly) --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Asal</label>
                    <input type="text" id="unit_asal_display" readonly
                           class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2.5 text-sm text-gray-500 cursor-not-allowed"
                           placeholder="Otomatis dari aset">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Asal</label>
                    <input type="text" id="lokasi_asal_display" readonly
                           class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2.5 text-sm text-gray-500 cursor-not-allowed"
                           placeholder="Otomatis dari aset">
                </div>
            </div>

            {{-- Panah --}}
            <div class="flex items-center justify-center gap-3 text-gray-400">
                <div class="h-px flex-1 bg-gray-200"></div>
                <div class="flex items-center gap-2 text-blue-600 font-medium text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    Dipindahkan ke
                </div>
                <div class="h-px flex-1 bg-gray-200"></div>
            </div>

            {{-- Unit & Lokasi Tujuan --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Tujuan <span class="text-red-500">*</span></label>
                    <select name="unit_tujuan" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('unit_tujuan') border-red-400 @enderror">
                        <option value="">-- Pilih Unit Tujuan --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit }}" {{ old('unit_tujuan') === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                    @error('unit_tujuan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Tujuan</label>
                    <input type="text" name="lokasi_tujuan" value="{{ old('lokasi_tujuan') }}"
                           placeholder="cth: Ruang Server, Lantai 2"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Mutasi <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_mutasi" value="{{ old('tanggal_mutasi', date('Y-m-d')) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('tanggal_mutasi')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Alasan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Alasan Mutasi <span class="text-red-500">*</span></label>
                <textarea name="alasan" rows="3" required
                          placeholder="Jelaskan alasan perpindahan aset ini..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('alasan') border-red-400 @enderror">{{ old('alasan') }}</textarea>
                @error('alasan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Berita Acara --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Berita Acara <span class="text-gray-400 font-normal">(opsional, PDF/JPG/PNG, maks. 5MB)</span>
                </label>
                <input type="file" name="berita_acara" accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('berita_acara')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('pimpinan.mutasi.index') }}"
                   class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">Batal</a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition shadow">
                    🔄 Ajukan Mutasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('asset_id').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    document.getElementById('unit_asal_display').value   = opt.dataset.unit   || '-';
    document.getElementById('lokasi_asal_display').value = opt.dataset.lokasi || '-';
});
window.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('asset_id');
    if (sel.value) sel.dispatchEvent(new Event('change'));
});
</script>
@endsection
