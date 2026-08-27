@extends('admin.layouts.app')

@section('title', 'Catat Surat Keluar')

@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('admin.surat-keluar.index') }}" class="hover:text-teal-600">Surat Keluar</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">Catat Surat Keluar Baru</span>
    </div>

    <div class="bg-white rounded-xl shadow p-8">
        <h1 class="text-xl font-bold text-gray-800 mb-1">📤 Catat Surat Keluar</h1>
        <p class="text-sm text-gray-500 mb-6">Nomor agenda akan dibuat otomatis oleh sistem.</p>

        @if($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.surat-keluar.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Surat</label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}"
                           placeholder="Opsional, cth: 100/DKP/VIII/2026"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tujuan Surat <span class="text-red-500">*</span></label>
                <input type="text" name="tujuan" value="{{ old('tujuan') }}" required
                       placeholder="cth: Dinas Pendidikan Kabupaten Barito Kuala"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Perihal <span class="text-red-500">*</span></label>
                <input type="text" name="perihal" value="{{ old('perihal') }}" required
                       placeholder="cth: Undangan Rapat Koordinasi"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Sifat Surat <span class="text-red-500">*</span></label>
                <select name="sifat" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="biasa"   {{ old('sifat') === 'biasa'   ? 'selected' : '' }}>Biasa</option>
                    <option value="penting" {{ old('sifat') === 'penting' ? 'selected' : '' }}>Penting</option>
                    <option value="segera"  {{ old('sifat') === 'segera'  ? 'selected' : '' }}>Segera</option>
                    <option value="rahasia" {{ old('sifat') === 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Lampiran Berkas Surat</label>
                <input type="file" name="file" accept=".pdf,.doc,.docx"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <p class="mt-1 text-xs text-gray-400">PDF/DOC/DOCX, maksimal 5MB. Opsional.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('keterangan') }}</textarea>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-xs text-yellow-800">
                Fitur Tanda Tangan Elektronik (TTE) untuk surat keluar akan tersedia pada pembaruan berikutnya.
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.surat-keluar.index') }}"
                   class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">Batal</a>
                <button type="submit"
                        class="px-6 py-2.5 bg-teal-600 text-white rounded-lg text-sm font-semibold hover:bg-teal-700 transition shadow">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
