@extends('staff.layouts.app')

@section('title', 'Ajukan Pemusnahan Aset')

@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('staff.pemusnahan.index') }}" class="hover:text-red-600">Pemusnahan Aset</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">Ajukan Pemusnahan Baru</span>
    </div>

    <div class="bg-white rounded-xl shadow p-8">
        <h1 class="text-xl font-bold text-gray-800 mb-1">🗑️ Ajukan Pemusnahan Aset</h1>
        <p class="text-sm text-gray-500 mb-6">Isi formulir untuk mengusulkan pemusnahan aset yang sudah rusak/tidak layak pakai. Usulan akan diproses oleh Admin.</p>

        @if($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('staff.pemusnahan.store') }}" class="space-y-5">
            @csrf

            {{-- Pilih Aset --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Aset yang Diusulkan <span class="text-red-500">*</span></label>
                <select name="asset_id" id="asset_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 @error('asset_id') border-red-400 @enderror">
                    <option value="">-- Pilih Aset --</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                            {{ $asset->kode_asset }} — {{ $asset->nama }} ({{ $asset->kategori }}, Status: {{ ucfirst($asset->status) }})
                        </option>
                    @endforeach
                </select>
                @error('asset_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-400">Hanya menampilkan aset yang belum pernah dimusnahkan.</p>
            </div>

            {{-- Kondisi Aset --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kondisi Aset Saat Ini</label>
                <input type="text" name="kondisi_aset" value="{{ old('kondisi_aset') }}"
                       placeholder="cth: Rusak berat, tidak dapat diperbaiki"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('kondisi_aset')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Tanggal Usulan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Usulan <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_usulan" value="{{ old('tanggal_usulan', date('Y-m-d')) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('tanggal_usulan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Alasan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Alasan Pemusnahan <span class="text-red-500">*</span></label>
                <textarea name="alasan_pemusnahan" rows="4" required
                          placeholder="Jelaskan alasan aset ini perlu dimusnahkan (kondisi kerusakan, sudah tidak ekonomis diperbaiki, dll)..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 @error('alasan_pemusnahan') border-red-400 @enderror">{{ old('alasan_pemusnahan') }}</textarea>
                @error('alasan_pemusnahan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Info --}}
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-xs text-yellow-800 flex gap-2">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Berita Acara Pemusnahan akan dibuat otomatis oleh sistem setelah usulan ini disetujui oleh Admin.
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('staff.pemusnahan.index') }}"
                   class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">Batal</a>
                <button type="submit"
                        class="px-6 py-2.5 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition shadow">
                    🗑️ Ajukan Pemusnahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
