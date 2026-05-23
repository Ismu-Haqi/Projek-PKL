@extends('admin.layouts.app')

@section('title', 'Buat Disposisi Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="{{ route('admin.disposisi.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Buat Disposisi Baru</h1>
                <p class="text-gray-600 mt-1">Isi formulir untuk membuat disposisi surat atau aset</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Formulir Disposisi
            </h2>
        </div>

        <form action="{{ route('admin.disposisi.store') }}" method="POST" class="p-6 space-y-6" id="form-disposisi" onsubmit="return prepareSubmit()">
            @csrf

            {{-- Hidden inputs backend --}}
            <input type="hidden" name="item_id"              id="final_item_id"   value="">
            <input type="hidden" name="incoming_letter_mode" id="letter_mode"     value="0">
            <input type="hidden" name="from_letter"          id="final_letter_id" value="">

            <!-- Select Item Type -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tipe Item <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-purple-500 border-purple-500 bg-purple-50"
                           id="label-arsip" onclick="switchType('arsip')">
                        <input type="radio" name="item_type" value="arsip" id="radio-arsip" checked class="sr-only">
                        <div class="flex items-center w-full">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Arsip/Surat</p>
                                <p class="text-xs text-gray-500">Disposisi dokumen arsip</p>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-pink-500 border-gray-300"
                           id="label-aset" onclick="switchType('aset')">
                        <input type="radio" name="item_type" value="aset" id="radio-aset" class="sr-only">
                        <div class="flex items-center w-full">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Aset</p>
                                <p class="text-xs text-gray-500">Disposisi inventaris aset</p>
                            </div>
                        </div>
                    </label>
                </div>
                @error('item_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Select Item (Dynamic based on type) -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <span id="label-item">Pilih Arsip/Surat</span>
                    <span class="text-red-500">*</span>
                </label>

                <!-- Satu dropdown yang berubah isinya sesuai tipe item -->
                <div id="arsip-wrapper">
                    <select id="item_select_arsip"
                            onchange="onArsipChange(this)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">-- Pilih Arsip/Surat --</option>

                        @if(isset($incomingLetters) && $incomingLetters->count())
                        <optgroup label="📨 Surat Masuk">
                            @foreach($incomingLetters as $letter)
                            <option value="letter_{{ $letter->id }}"
                                    data-subject="{{ $letter->perihal }}"
                                    data-letter="{{ $letter->id }}">
                                [SM] {{ $letter->nomor_agenda }} — {{ Str::limit($letter->perihal, 45) }}
                            </option>
                            @endforeach
                        </optgroup>
                        @endif

                        @if($archives->count())
                        <optgroup label="🗂️ Arsip Digital">
                            @foreach($archives as $archive)
                            <option value="archive_{{ $archive->id }}"
                                    data-subject="{{ $archive->judul }}"
                                    data-archive="{{ $archive->id }}">
                                {{ $archive->nomor_surat }} — {{ Str::limit($archive->judul, 45) }}
                            </option>
                            @endforeach
                        </optgroup>
                        @endif
                    </select>
                </div>

                <div id="aset-wrapper" style="display:none;">
                    <select id="item_select_aset"
                            onchange="onAsetChange(this)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <option value="">-- Pilih Aset --</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" data-subject="{{ $asset->nama }}">
                            {{ $asset->kode_asset }} - {{ $asset->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                @error('item_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1" id="hint-item">Pilih surat yang akan didisposisikan</p>
            </div>

            <!-- Recipient -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Penerima Disposisi <span class="text-red-500">*</span>
                </label>
                <select name="to_user_id" id="to_user_id" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('to_user_id') border-red-500 @enderror">
                    <option value="">-- Pilih Penerima --</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('to_user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} - {{ $user->unit ?? 'Tidak ada unit' }}
                    </option>
                    @endforeach
                </select>
                @error('to_user_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Pilih staff yang akan menerima disposisi</p>
            </div>

            <!-- Subject -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Subjek Disposisi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="subject" id="subject" required
                    value="{{ old('subject') }}"
                    placeholder="Contoh: Tindak Lanjut Surat Edaran"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('subject') border-red-500 @enderror">
                @error('subject')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Priority & Deadline -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Priority -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Prioritas <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" id="priority" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('priority') border-red-500 @enderror">
                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Biasa</option>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Tidak Mendesak</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Mendesak</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Sangat Mendesak</option>
                    </select>
                    @error('priority')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deadline -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Batas Waktu
                    </label>
                    <input type="date" name="deadline" id="deadline"
                        value="{{ old('deadline') }}"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('deadline') border-red-500 @enderror">
                    @error('deadline')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Opsional - Kosongkan jika tidak ada deadline</p>
                </div>
            </div>

            <!-- Instruction -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Instruksi/Perintah <span class="text-red-500">*</span>
                </label>
                <textarea name="instruction" id="instruction" rows="6" required
                    placeholder="Tuliskan instruksi atau perintah disposisi secara detail..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('instruction') border-red-500 @enderror">{{ old('instruction') }}</textarea>
                @error('instruction')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Jelaskan secara detail apa yang harus dilakukan</p>
            </div>

            <!-- Info Box -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200 p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h4 class="text-sm font-bold text-blue-800 mb-1">Informasi Penting</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Penerima akan mendapat notifikasi setelah disposisi dibuat</li>
                            <li>• Nomor disposisi akan dibuat otomatis oleh sistem</li>
                            <li>• Status awal disposisi adalah "Menunggu"</li>
                            <li>• Anda dapat memantau progress disposisi di halaman detail</li>
                            <li>• Disposisi dapat dibuat untuk arsip/surat atau aset inventaris</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.disposisi.index') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Disposisi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ── Switch tipe item (arsip / aset) ─────────────────────────────────────────
function switchType(type) {
    const arsipWrapper = document.getElementById('arsip-wrapper');
    const asetWrapper  = document.getElementById('aset-wrapper');
    const labelArsip   = document.getElementById('label-arsip');
    const labelAset    = document.getElementById('label-aset');
    const labelItem    = document.getElementById('label-item');
    const hintItem     = document.getElementById('hint-item');
    const radioArsip   = document.getElementById('radio-arsip');
    const radioAset    = document.getElementById('radio-aset');

    // Reset hidden inputs
    document.getElementById('final_item_id').value   = '';
    document.getElementById('letter_mode').value     = '0';
    document.getElementById('final_letter_id').value = '';

    if (type === 'arsip') {
        arsipWrapper.style.display = 'block';
        asetWrapper.style.display  = 'none';
        labelArsip.className = labelArsip.className.replace('border-gray-300', 'border-purple-500 bg-purple-50');
        labelAset.className  = labelAset.className.replace('border-pink-500 bg-pink-50', 'border-gray-300');
        labelItem.textContent = 'Pilih Arsip/Surat';
        hintItem.textContent  = 'Pilih surat yang akan didisposisikan';
        radioArsip.checked = true;
        radioAset.checked  = false;
    } else {
        arsipWrapper.style.display = 'none';
        asetWrapper.style.display  = 'block';
        labelAset.className = labelAset.className.replace('border-gray-300', 'border-pink-500 bg-pink-50');
        labelArsip.className = labelArsip.className.replace('border-purple-500 bg-purple-50', 'border-gray-300');
        labelItem.textContent = 'Pilih Aset';
        hintItem.textContent  = 'Pilih aset yang akan didisposisikan';
        radioAset.checked  = true;
        radioArsip.checked = false;
    }
}

// ── Handler perubahan dropdown arsip ────────────────────────────────────────
function onArsipChange(sel) {
    const selected    = sel.options[sel.selectedIndex];
    const val         = selected.value;
    const dataSubject = selected.getAttribute('data-subject') || '';
    const subjectInput = document.getElementById('subject');

    if (!val) {
        document.getElementById('final_item_id').value   = '';
        document.getElementById('letter_mode').value     = '0';
        document.getElementById('final_letter_id').value = '';
        return;
    }

    if (val.startsWith('letter_')) {
        const lid = selected.getAttribute('data-letter');
        document.getElementById('final_item_id').value   = lid;
        document.getElementById('letter_mode').value     = '1';
        document.getElementById('final_letter_id').value = lid;
    } else if (val.startsWith('archive_')) {
        const aid = selected.getAttribute('data-archive');
        document.getElementById('final_item_id').value   = aid;
        document.getElementById('letter_mode').value     = '0';
        document.getElementById('final_letter_id').value = '';
    }

    if (dataSubject && !subjectInput.value) {
        subjectInput.value = 'Disposisi: ' + dataSubject;
    }
}

// ── Handler perubahan dropdown aset ─────────────────────────────────────────
function onAsetChange(sel) {
    const selected    = sel.options[sel.selectedIndex];
    const val         = selected.value;
    const dataSubject = selected.getAttribute('data-subject') || '';
    const subjectInput = document.getElementById('subject');

    document.getElementById('final_item_id').value   = val;
    document.getElementById('letter_mode').value     = '0';
    document.getElementById('final_letter_id').value = '';

    if (dataSubject && !subjectInput.value) {
        subjectInput.value = 'Disposisi: ' + dataSubject;
    }
}

// ── Validasi & prepare sebelum submit ───────────────────────────────────────
function prepareSubmit() {
    const itemId = document.getElementById('final_item_id').value;

    if (!itemId) {
        const activeType = document.getElementById('radio-aset').checked ? 'aset' : 'arsip';
        if (activeType === 'arsip') {
            alert('Pilih arsip atau surat masuk terlebih dahulu.');
            document.getElementById('item_select_arsip').style.borderColor = '#ef4444';
            document.getElementById('item_select_arsip').focus();
        } else {
            alert('Pilih aset terlebih dahulu.');
            document.getElementById('item_select_aset').style.borderColor = '#ef4444';
            document.getElementById('item_select_aset').focus();
        }
        return false;
    }
    return true;
}
</script>
@endsection