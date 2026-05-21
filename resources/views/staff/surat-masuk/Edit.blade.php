@extends('admin.layouts.app')

@section('title', 'Edit Surat Masuk')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    <div class="flex items-center mb-6">
        <a href="{{ route('admin.surat-masuk.show', $letter->id) }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Surat Masuk</h1>
            <p class="text-gray-500 text-sm mt-0.5">{{ $letter->nomor_agenda }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Edit Data Surat</h2>
        </div>

        <form action="{{ route('admin.surat-masuk.update', $letter->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $letter->nomor_surat) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    @error('nomor_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $letter->tanggal_surat->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    @error('tanggal_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Diterima <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima', $letter->tanggal_diterima->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    @error('tanggal_diterima')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sifat Surat <span class="text-red-500">*</span></label>
                    <select name="sifat" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        @foreach(['biasa' => 'Biasa','segera' => 'Segera','sangat_segera' => 'Sangat Segera','rahasia' => 'Rahasia'] as $val => $label)
                        <option value="{{ $val }}" {{ old('sifat', $letter->sifat) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Pengirim <span class="text-red-500">*</span></label>
                <input type="text" name="pengirim" value="{{ old('pengirim', $letter->pengirim) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                @error('pengirim')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Perihal <span class="text-red-500">*</span></label>
                <input type="text" name="perihal" value="{{ old('perihal', $letter->perihal) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                @error('perihal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                    <input type="text" name="kategori" value="{{ old('kategori', $letter->kategori) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Tujuan</label>
                    <input type="text" name="unit_tujuan" value="{{ old('unit_tujuan', $letter->unit_tujuan) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 resize-none">{{ old('keterangan', $letter->keterangan) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Ganti File Surat
                    @if($letter->file_name)
                    <span class="text-gray-400 font-normal">(kosongkan jika tidak ingin mengganti: {{ $letter->file_name }})</span>
                    @endif
                </label>
                <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                @error('file_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.surat-masuk.show', $letter->id) }}"
                   class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection