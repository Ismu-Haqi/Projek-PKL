@extends('admin.layouts.app')

@section('title', 'Edit Arsip')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">✏️ Edit Arsip Digital</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi arsip digital</p>
        </div>
        <a href="{{ route('admin.arsip.show', $archive->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition flex items-center">
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
    <form action="{{ route('admin.arsip.update', $archive->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            {{-- Info Box --}}
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded">
                <div class="flex">
                    <svg class="w-6 h-6 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-yellow-800">Perhatian:</p>
                        <p class="text-sm text-yellow-700 mt-1">Jika Anda upload file baru, file lama akan diganti. Kosongkan field file jika tidak ingin mengubah file.</p>
                    </div>
                </div>
            </div>

            {{-- Form Fields --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Nomor Surat --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Nomor Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $archive->nomor_surat) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Tanggal Surat --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tanggal Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_surat" 
                           value="{{ old('tanggal_surat', $archive->tanggal_surat ? $archive->tanggal_surat->format('Y-m-d') : $archive->tanggal_arsip->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Judul --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Judul/Perihal Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" value="{{ old('judul', $archive->judul) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Pengirim --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Pengirim <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pengirim" value="{{ old('pengirim', $archive->pengirim) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Unit (Fixed - Tidak bisa diubah) --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Unit/Bidang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="unit" value="{{ old('unit', $archive->unit) }}" readonly
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">🔒 Unit tidak dapat diubah setelah arsip dibuat</p>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required onchange="updateRetensiPreview()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $archive->category_id) == $category->id ? 'selected' : '' }}>
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
                        <option value="Biasa" {{ old('priority', $archive->priority) == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="Penting" {{ old('priority', $archive->priority) == 'Penting' ? 'selected' : '' }}>Penting</option>
                        <option value="Sangat Penting" {{ old('priority', $archive->priority) == 'Sangat Penting' ? 'selected' : '' }}>Sangat Penting</option>
                        <option value="Segera" {{ old('priority', $archive->priority) == 'Segera' ? 'selected' : '' }}>Segera</option>
                    </select>
                </div>

                {{-- Tanggal Retensi --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tanggal Retensi <span class="text-gray-400 font-normal text-xs">(opsional, manual)</span>
                    </label>
                    <input type="date" name="tanggal_retensi" id="tanggal_retensi"
                           value="{{ old('tanggal_retensi', $archive->tanggal_retensi ? $archive->tanggal_retensi->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Batas waktu arsip aktif sebelum dipindahkan ke inaktif/dimusnahkan. Sistem akan mengingatkan otomatis.</p>

                    <div id="retensiJraInfo" class="hidden mt-2 bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                        📜 Kategori ini memiliki aturan <strong>Jadwal Retensi Arsip (JRA)</strong>: masa aktif
                        <strong><span id="jraAktif"></span> tahun</strong>, masa inaktif
                        <strong><span id="jraInaktif"></span> tahun</strong> (total <span id="jraTotal"></span> tahun),
                        nasib akhir: <strong><span id="jraNasib"></span></strong>.
                        Menyimpan perubahan akan <strong>menghitung ulang</strong> tanggal retensi otomatis sesuai aturan ini.
                    </div>

                    @if($archive->retentionSchedule)
                    <div class="mt-2 bg-gray-50 border border-gray-200 rounded-lg p-3 text-xs text-gray-600">
                        Saat ini mengikuti JRA — tanggal inaktif: <strong>{{ optional($archive->tanggal_inaktif)->format('d/m/Y') ?? '-' }}</strong>,
                        nasib akhir: <strong>{{ \App\Models\RetentionSchedule::labelNasibAkhir($archive->nasib_akhir_arsip) }}</strong>.
                    </div>
                    @endif
                </div>

                <script>
                const retentionRules = @json($retentionRules ?? []);

                function updateRetensiPreview() {
                    const categoryId = document.getElementById('category_id').value;
                    const infoBox = document.getElementById('retensiJraInfo');
                    const rule = retentionRules[categoryId];

                    if (rule) {
                        document.getElementById('jraAktif').textContent = rule.aktif_tahun;
                        document.getElementById('jraInaktif').textContent = rule.inaktif_tahun;
                        document.getElementById('jraTotal').textContent = rule.total_tahun;
                        document.getElementById('jraNasib').textContent = rule.nasib_akhir;
                        infoBox.classList.remove('hidden');
                    } else {
                        infoBox.classList.add('hidden');
                    }
                }

                document.addEventListener('DOMContentLoaded', updateRetensiPreview);
                </script>


                {{-- File Saat Ini --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        File Saat Ini
                    </label>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-10 h-10 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $archive->file_name ?? basename($archive->file_path) }}</p>
                                @if($archive->file_size)
                                    <p class="text-xs text-gray-500">{{ number_format($archive->file_size / 1024, 2) }} KB</p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.arsip.download', $archive->id) }}" 
                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Download
                        </a>
                    </div>
                </div>

                {{-- Upload File Baru --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Upload File Baru (Opsional)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-all cursor-pointer bg-gray-50 hover:bg-blue-50" id="uploadArea">
                        <input type="file" name="files[]" id="fileInput" multiple 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" 
                               class="hidden">
                        
                        <div id="uploadPlaceholder">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <p class="text-sm text-gray-600 mb-2">
                                <span class="font-semibold text-blue-600 hover:text-blue-700 cursor-pointer">
                                    Klik untuk upload file baru
                                </span> atau drag & drop
                            </p>
                            <p class="text-xs text-gray-500">
                                PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max: 10MB per file)
                            </p>
                            <p class="text-xs text-yellow-600 mt-2">
                                ⚠️ File lama akan diganti jika Anda upload file baru
                            </p>
                        </div>

                        <div id="filesList" class="mt-4 space-y-2 hidden"></div>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Keterangan/Catatan
                    </label>
                    <textarea name="keterangan" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('keterangan', $archive->keterangan) }}</textarea>
                </div>

                {{-- Tags --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tags
                    </label>
                    <input type="text" name="tags" value="{{ old('tags', $archive->tags) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Pisahkan dengan koma">
                </div>

            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-between">
           

            <div class="flex gap-3">
                <a href="{{ route('admin.arsip.show', $archive->id) }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Batal
                </a>
                <button type="submit" id="submitBtn"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Arsip
                </button>
            </div>
             </form>
            <button type="button" onclick="confirmDelete(this, 'Arsip akan dihapus permanen!')" 
                    class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus Arsip
            </button>
            </form>
        </div>
   
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

// File input change
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
        const dataTransfer = new DataTransfer();
        Array.from(e.dataTransfer.files).forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
        handleFiles(e.dataTransfer.files);
    }
});

function handleFiles(files) {
    if (files.length === 0) return;
    selectedFiles = Array.from(files);
    displayFiles();
}

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
        
        const maxSize = 10;
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

function removeFile(index) {
    selectedFiles.splice(index, 1);
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    fileInput.files = dataTransfer.files;
    displayFiles();
}

// Form submit
document.getElementById('editForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
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