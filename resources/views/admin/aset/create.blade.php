@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.aset.index') }}" class="hover:text-blue-600">Manajemen Aset</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-800 font-medium">Tambah Aset Baru</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">➕ Tambah Aset Baru</h1>
        <p class="text-gray-600 mt-2">Isi formulir untuk menambahkan aset ke inventaris</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.aset.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        
        <!-- Informasi Dasar -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-blue-500">
                📋 Informasi Dasar
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Aset -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Aset <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror"
                           placeholder="Contoh: Laptop Dell Latitude 5520">
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kategori') border-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('kategori') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Merk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Merk</label>
                    <input type="text" name="merk" value="{{ old('merk') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: Dell">
                    @error('merk')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe/Model</label>
                    <input type="text" name="tipe" value="{{ old('tipe') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: Latitude 5520">
                    @error('tipe')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Serial Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: DL552001">
                    @error('serial_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Spesifikasi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Spesifikasi</label>
                    <textarea name="spesifikasi" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Contoh: Intel i5 Gen 11, RAM 8GB, SSD 256GB, Windows 11 Pro">{{ old('spesifikasi') }}</textarea>
                    @error('spesifikasi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status & Kondisi -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-green-500">
                🔧 Status & Kondisi
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kondisi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kondisi <span class="text-red-500">*</span>
                    </label>
                    <select name="kondisi" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="cukup" {{ old('kondisi') == 'cukup' ? 'selected' : '' }}>Cukup</option>
                        <option value="kurang" {{ old('kondisi') == 'kurang' ? 'selected' : '' }}>Kurang</option>
                        <option value="rusak" {{ old('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                    @error('kondisi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="tersedia" {{ old('status', 'tersedia') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="digunakan" {{ old('status') == 'digunakan' ? 'selected' : '' }}>Digunakan</option>
                        <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="rusak" {{ old('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Lokasi & Penempatan -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-purple-500">
                📍 Lokasi & Penempatan
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Lokasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                    <select name="lokasi" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Lokasi</option>
                        @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi }}" {{ old('lokasi') == $lokasi ? 'selected' : '' }}>
                                {{ $lokasi }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Kerja</label>
                    <select name="unit" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit }}" {{ old('unit') == $unit ? 'selected' : '' }}>
                                {{ $unit }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penanggung Jawab (Auto-fill dari user login) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Penanggung Jawab
                        <span class="text-xs text-gray-500">(Otomatis terisi)</span>
                    </label>
                    <input type="text" name="penanggung_jawab" 
                           value="{{ old('penanggung_jawab', $penanggungJawab) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Nama penanggung jawab">
                    <p class="text-xs text-gray-500 mt-1">💡 Terisi otomatis dengan nama Anda, bisa diubah jika diperlukan</p>
                    @error('penanggung_jawab')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Informasi Pembelian & Garansi -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-orange-500">
                💰 Pembelian & Garansi
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tanggal Pembelian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pembelian</label>
                    <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('tanggal_pembelian')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Pembelian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Pembelian (Rp)</label>
                    <input type="number" name="harga_pembelian" value="{{ old('harga_pembelian') }}" min="0"
                    
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: 15000000">
                    @error('harga_pembelian')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Nilai Residu (Rp)
    </label>
    <input type="number" name="nilai_residu" value="{{ old('nilai_residu', $asset->nilai_residu ?? '') }}"
        placeholder="Contoh: 1000000" min="0"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
    <p class="text-xs text-gray-500 mt-1">Perkiraan harga jual/sisa aset saat sudah rusak (Kosongkan jika tidak ada).</p>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Umur Ekonomis (Tahun)
    </label>
    <input type="number" name="umur_ekonomis" value="{{ old('umur_ekonomis', $asset->umur_ekonomis ?? '') }}"
        placeholder="Contoh: 5" min="0"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
    <p class="text-xs text-gray-500 mt-1">Lama pemakaian aset secara optimal dalam hitungan tahun.</p>
</div>

                <!-- Masa Garansi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Masa Garansi (Bulan)</label>
                    <input type="number" name="masa_garansi" value="{{ old('masa_garansi') }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: 12">
                    <p class="text-xs text-gray-500 mt-1">Isi 0 jika tidak ada garansi</p>
                    @error('masa_garansi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Foto & Keterangan -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-pink-500">
                📸 Foto & Keterangan
            </h2>
            
            <div class="grid grid-cols-1 gap-4">
                <!-- Foto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Aset</label>
                    <input type="file" name="foto" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onchange="previewImage(event)">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                    @error('foto')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    
                    <!-- Preview -->
                    <div id="imagePreview" class="mt-3 hidden">
                        <img src="" alt="Preview" class="max-w-xs rounded-lg shadow-md">
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Tambahkan catatan atau informasi tambahan tentang aset ini...">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 pt-6 border-t">
            <a href="{{ route('admin.aset.index') }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                💾 Simpan Aset
            </button>
        </div>
    </form>

</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const img = preview.querySelector('img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endsection