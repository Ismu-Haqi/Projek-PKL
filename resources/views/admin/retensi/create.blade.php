@extends('admin.layouts.app')

@section('title', 'Tambah Jadwal Retensi Arsip')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">

    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.retensi.index') }}" class="hover:text-blue-600">Jadwal Retensi Arsip</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-800 font-medium">Tambah Aturan</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">📜 Tambah Aturan Jadwal Retensi Arsip</h1>
        <p class="text-gray-600 mt-2">Tentukan masa aktif, masa inaktif, dan nasib akhir untuk satu kategori arsip.</p>
    </div>

    <form action="{{ route('admin.retensi.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Kategori Arsip <span class="text-red-500">*</span>
            </label>
            <select name="category_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-400 mt-1">Hanya kategori yang belum memiliki aturan JRA yang muncul di sini.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Klasifikasi</label>
            <input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Contoh: 000.1 (sesuai pola klasifikasi arsip instansi)">
            @error('kode_klasifikasi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Masa Aktif (tahun) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="retensi_aktif_tahun" min="0" max="100" value="{{ old('retensi_aktif_tahun', 2) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('retensi_aktif_tahun') border-red-500 @enderror">
                @error('retensi_aktif_tahun')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Masa Inaktif (tahun) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="retensi_inaktif_tahun" min="0" max="100" value="{{ old('retensi_inaktif_tahun', 3) }}" required
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
                <option value="musnah" {{ old('nasib_akhir') == 'musnah' ? 'selected' : '' }}>Dimusnahkan</option>
                <option value="permanen" {{ old('nasib_akhir') == 'permanen' ? 'selected' : '' }}>Permanen (disimpan selamanya)</option>
                <option value="dinilai_kembali" {{ old('nasib_akhir') == 'dinilai_kembali' ? 'selected' : '' }}>Dinilai Kembali</option>
            </select>
            @error('nasib_akhir')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Dasar Hukum / Referensi</label>
            <input type="text" name="dasar_hukum" value="{{ old('dasar_hukum') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Contoh: Perka ANRI No. ... Tahun ... tentang JRA">
            @error('dasar_hukum')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
            <textarea name="keterangan" rows="3"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
            @error('keterangan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-800">
            💡 Setelah disimpan, aturan ini otomatis diterapkan ke arsip lama dari kategori ini yang tanggal
            retensinya belum pernah dihitung berdasarkan JRA.
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.retensi.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-lg transition">Simpan Aturan</button>
        </div>
    </form>
</div>
@endsection
