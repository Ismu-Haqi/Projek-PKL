@extends('staff.layouts.app')

@section('title', 'Unggah Arsip Baru')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📤 Unggah Arsip Digital Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Lengkapi informasi dan unggah file arsip digital</p>
        </div>
        <a href="{{ route('admin.arsip.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    {{-- Alert Messages --}}
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
        <div class="flex">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-bold text-red-800">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside text-sm text-red-700 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.arsip.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf
        
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            {{-- Info Box --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                <div class="flex">
                    <svg class="w-6 h-6 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-blue-800">Petunjuk Pengisian:</p>
                        <ul class="list-disc list-inside text-sm text-blue-700 mt-2">
                            <li>Field bertanda (*) wajib diisi</li>
                            <li>Anda bisa upload <strong>BEBERAPA FILE SEKALIGUS</strong> (max 10MB per file)</li>
                            <li>Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG</li>
                            <li>Data pengirim dan unit otomatis terisi dari akun Anda</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Form Fields --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Nomor Surat (Auto-generated, readonly) --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Nomor Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $nomorSurat) }}" required readonly
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 cursor-not-allowed @error('nomor_surat') border-red-500 @enderror">
                    @error('nomor_surat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">📌 Nomor surat otomatis ter-generate berdasarkan unit dan bulan</p>
                </div>

                {{-- Tanggal Surat --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tanggal Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Judul (Full Width) --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Judul/Perihal Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Masukkan judul atau perihal surat">
                </div>

                {{-- Pengirim (Auto-filled, readonly) --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Pengirim <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pengirim" value="{{ Auth::user()->name }}" readonly
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">Otomatis terisi dari akun Anda</p>
                </div>

                {{-- Unit (Auto-filled dari user, bisa edit jika perlu) --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Unit/Bidang <span class="text-red-500">*</span>
                    </label>
                    <select name="unit" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="{{ Auth::user()->unit ?? '' }}" selected>{{ Auth::user()->unit ?? 'Pilih Unit' }}</option>
                        <option value="Sekretariat">Sekretariat</option>
                        <option value="IKP">IKP (Informasi & Komunikasi Publik)</option>
                        <option value="Aptika">Aptika (Aplikasi Informatika)</option>
                        <option value="Komtel">Komtel (Komunikasi & Telematika)</option>
                        <option value="Statistik">Statistik & Persandian</option>
                        <option value="E-Gov">E-Government</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Unit Anda: <strong>{{ Auth::user()->unit ?? 'Belum diset' }}</strong></p>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Prioritas --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tingkat Kepentingan
                    </label>
                    <select name="priority"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Biasa" selected>Biasa</option>
                        <option value="Penting">Penting</option>
                        <option value="Sangat Penting">Sangat Penting</option>
                        <option value="Segera">Segera</option>
                    </select>
                </div>

                {{-- Upload Multiple Files --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Upload File <span class="text-red-500">*</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-all cursor-pointer bg-gray-50 hover:bg-blue-50" id="uploadArea">
                        <input type="file" name="files[]" id="fileInput" multiple required 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" 
                               class="hidden">
                        
                        <div id="uploadPlaceholder">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <p class="text-lg text-gray-600 mb-2">
                                <span class="font-bold text-blue-600 hover:text-blue-700 cursor-pointer">
                                    Klik untuk upload
                                </span> atau drag & drop file
                            </p>
                            <p class="text-sm text-gray-500 mb-1">
                                <strong>Bisa upload BEBERAPA FILE sekaligus!</strong>
                            </p>
                            <p class="text-xs text-gray-500">
                                PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max: 10MB per file)
                            </p>
                        </div>

                        {{-- File Preview List --}}
                        <div id="filesList" class="mt-4 space-y-2 hidden"></div>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Keterangan/Catatan
                    </label>
                    <textarea name="keterangan" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Tambahkan catatan atau keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Tags --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tags
                    </label>
                    <input type="text" name="tags" value="{{ old('tags') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Pisahkan dengan koma (contoh: surat masuk, penting, 2025)">
                    <p class="text-xs text-gray-500 mt-1">Tags membantu pencarian arsip lebih mudah</p>
                </div>

            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.arsip.index') }}" 
               class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                Batal
            </a>
            <button type="submit" id="submitBtn"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Arsip
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('fileInput');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');
const filesList = document.getElementById('filesList');
let selectedFiles = [];

// Click to upload
uploadArea.addEventListener('click', function(e) {
    if (!e.target.closest('.remove-file-btn')) {
        fileInput.click();
    }
});

// File input change (FIX: Langsung trigger saat pilih file)
fileInput.addEventListener('change', function(e) {
    handleFiles(this.files);
});

// Drag and drop
uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    e.stopPropagation();
    uploadArea.classList.add('border-blue-500', 'bg-blue-50');
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    e.stopPropagation();
    uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    e.stopPropagation();
    uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
    
    if (e.dataTransfer.files.length) {
        // Update file input dengan files yang di-drop
        const dataTransfer = new DataTransfer();
        Array.from(e.dataTransfer.files).forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
        
        handleFiles(e.dataTransfer.files);
    }
});

// Handle files
function handleFiles(files) {
    if (files.length === 0) return;
    
    selectedFiles = Array.from(files);
    displayFiles();
}

// Display file list
function displayFiles() {
    if (selectedFiles.length === 0) {
        filesList.classList.add('hidden');
        uploadPlaceholder.classList.remove('hidden');
        return;
    }
    
    uploadPlaceholder.classList.add('hidden');
    filesList.classList.remove('hidden');
    filesList.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        // Validate file
        const maxSize = 10; // 10MB
        const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
        
        let statusClass = 'bg-green-50 border-green-200';
        let statusIcon = 'text-green-600';
        let statusText = `${fileSize} MB`;
        
        if (file.size > maxSize * 1024 * 1024) {
            statusClass = 'bg-red-50 border-red-200';
            statusIcon = 'text-red-600';
            statusText = `File terlalu besar! (${fileSize} MB)`;
        } else if (!allowedExtensions.includes(fileExtension)) {
            statusClass = 'bg-red-50 border-red-200';
            statusIcon = 'text-red-600';
            statusText = `Format tidak didukung!`;
        }
        
        const fileDiv = document.createElement('div');
        fileDiv.className = `${statusClass} border rounded-lg p-4 flex items-center justify-between`;
        fileDiv.innerHTML = `
            <div class="flex items-center flex-1">
                <svg class="w-10 h-10 ${statusIcon} mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900 truncate">${file.name}</p>
                    <p class="text-xs text-gray-500">${statusText}</p>
                </div>
            </div>
            <button type="button" onclick="removeFile(${index})" class="remove-file-btn text-red-600 hover:text-red-800 ml-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        filesList.appendChild(fileDiv);
    });
}

// Remove file
function removeFile(index) {
    selectedFiles.splice(index, 1);
    
    // Update file input
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    fileInput.files = dataTransfer.files;
    
    displayFiles();
}

// Form submit validation
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    
    // Validate files
    const hasValidFiles = selectedFiles.length > 0 && selectedFiles.every(file => {
        const maxSize = 10 * 1024 * 1024;
        const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        return file.size <= maxSize && allowedExtensions.includes(fileExtension);
    });
    
    if (!hasValidFiles) {
        e.preventDefault();
        alert('Pastikan semua file valid (max 10MB dan format yang didukung)!');
        return false;
    }
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menyimpan...
    `;
});
</script>
@endpush

@endsection