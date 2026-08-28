@extends('admin.layouts.app')

@section('title', 'Edit Jadwal Retensi Arsip')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">

    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.retensi.index') }}" class="hover:text-blue-600">Jadwal Retensi Arsip</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-800 font-medium">Edit Aturan</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">📜 Edit Aturan — {{ $schedule->category->name ?? '-' }}</h1>
        <p class="text-gray-600 mt-2">Kategori arsip tidak bisa diubah. Untuk kategori berbeda, buat aturan baru.</p>
    </div>

    <form action="{{ route('admin.retensi.update', $schedule->id) }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Arsip</label>
            <input type="text" value="{{ $schedule->category->name ?? '-' }}" disabled
                   class="w-full px-4 py-2 border border-gray-200 bg-gray-100 text-gray-500 rounded-lg">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Klasifikasi</label>
            <input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi', $schedule->kode_klasifikasi) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('kode_klasifikasi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Masa Aktif (tahun) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="retensi_aktif_tahun" min="0" max="100"
                       value="{{ old('retensi_aktif_tahun', $schedule->retensi_aktif_tahun) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('retensi_aktif_tahun') border-red-500 @enderror">
                @error('retensi_aktif_tahun')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Masa Inaktif (tahun) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="retensi_inaktif_tahun" min="0" max="100"
                       value="{{ old('retensi_inaktif_tahun', $schedule->retensi_inaktif_tahun) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('retensi_inaktif_tahun') border-red-500 @enderror">
                @error('retensi_inaktif_tahun')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Nasib Akhir <span class="text-red-500">*</span>
            </label>
            <select name="nasib_akhir" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nasib_akhir') border-red-500 @enderror">
                @foreach(['musnah' => 'Dimusnahkan', 'permanen' => 'Permanen (disimpan selamanya)', 'dinilai_kembali' => 'Dinilai Kembali'] as $val => $label)
                    <option value="{{ $val }}" {{ old('nasib_akhir', $schedule->nasib_akhir) == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('nasib_akhir')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Dasar Hukum / Referensi</label>
            <input type="text" name="dasar_hukum" value="{{ old('dasar_hukum', $schedule->dasar_hukum) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('dasar_hukum')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
            <textarea name="keterangan" rows="3"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('keterangan', $schedule->keterangan) }}</textarea>
            @error('keterangan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}
                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
            <label for="is_active" class="text-sm font-medium text-gray-700">Aturan aktif dipakai</label>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
            ⚠️ Mengubah masa aktif/inaktif/nasib akhir akan langsung menghitung ulang tanggal retensi
            <strong>semua arsip</strong> yang memakai aturan ini.
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.retensi.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-lg transition">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
