@extends('admin.layouts.app')

@section('title', 'Edit Klasifikasi JRA')

@section('content')
<div class="p-6 max-w-2xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('admin.jadwal-retensi.index') }}" class="hover:text-teal-600">Jadwal Retensi Arsip</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">Tambah Klasifikasi</span>
    </div>

    <div class="bg-white rounded-xl shadow p-8">
        <h1 class="text-xl font-bold text-gray-800 mb-6">📚 Edit Klasifikasi Retensi Arsip</h1>

        @if($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.jadwal-retensi.update', $jra->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Klasifikasi <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi', $jra->kode_klasifikasi) }}" required
                           placeholder="cth: 000, 100, 200"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nasib Akhir <span class="text-red-500">*</span></label>
                    <select name="nasib_akhir" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="musnah" {{ old('nasib_akhir', $jra->nasib_akhir) === 'musnah' ? 'selected' : '' }}>Musnah</option>
                        <option value="permanen" {{ old('nasib_akhir', $jra->nasib_akhir) === 'permanen' ? 'selected' : '' }}>Permanen</option>
                        <option value="dinilai_kembali" {{ old('nasib_akhir', $jra->nasib_akhir) === 'dinilai_kembali' ? 'selected' : '' }}>Dinilai Kembali</option>
                    </select>
                    <label class="flex items-center gap-2 mt-2 text-xs text-gray-600">
                        <input type="checkbox" name="aktif" value="1" {{ old('aktif', $jra->aktif) ? 'checked' : '' }} class="rounded text-teal-600 focus:ring-teal-500">
                        Klasifikasi ini aktif dipakai
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Klasifikasi <span class="text-red-500">*</span></label>
                <input type="text" name="nama_klasifikasi" value="{{ old('nama_klasifikasi', $jra->nama_klasifikasi) }}" required
                       placeholder="cth: Surat Masuk Umum"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('deskripsi', $jra->deskripsi) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Masa Simpan Aktif (tahun) <span class="text-red-500">*</span></label>
                    <input type="number" name="jangka_aktif_tahun" value="{{ old('jangka_aktif_tahun', $jra->jangka_aktif_tahun) }}" min="0" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Masa Simpan Inaktif (tahun) <span class="text-red-500">*</span></label>
                    <input type="number" name="jangka_inaktif_tahun" value="{{ old('jangka_inaktif_tahun', $jra->jangka_inaktif_tahun) }}" min="0" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-800">
                💡 <strong>Masa Aktif</strong> = lama arsip disimpan di unit kerja sebelum dipindahkan ke inaktif/gudang. <strong>Masa Inaktif</strong> = lama disimpan di gudang/inaktif sebelum keputusan nasib akhir (musnah/permanen/dinilai kembali).
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.jadwal-retensi.index') }}"
                   class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">Batal</a>
                <button type="submit"
                        class="px-6 py-2.5 bg-teal-600 text-white rounded-lg text-sm font-semibold hover:bg-teal-700 transition shadow">
                    Update Klasifikasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
