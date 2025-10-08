<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <h5 class="modal-title font-bold">
                    <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Unggah Arsip Digital Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('admin.arsip.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="modal-body p-6">
                    {{-- Alert Info --}}
                    <div class="alert alert-info border-l-4 border-blue-500 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-blue-800">Petunjuk Pengisian:</p>
                                <p class="text-sm text-blue-700">Lengkapi semua field yang bertanda (*). File maksimal 10MB (PDF, DOC, DOCX, XLS, XLSX).</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nomor Surat --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Surat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nomor_surat" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Contoh: 001/IKP/2025">
                            <p class="text-xs text-gray-500 mt-1">Format: Nomor/Unit/Tahun</p>
                        </div>

                        {{-- Tanggal Surat --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Surat <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_surat" required value="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Judul --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Judul/Perihal Surat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="judul" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Masukkan judul atau perihal surat">
                        </div>

                        {{-- Pengirim --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pengirim <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pengirim" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Nama pengirim/instansi">
                        </div>

                        {{-- Unit/Bidang --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Unit/Bidang <span class="text-red-500">*</span>
                            </label>
                            <select name="unit" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Unit --</option>
                                <option value="Sekretariat">Sekretariat</option>
                                <option value="IKP">IKP (Informasi & Komunikasi Publik)</option>
                                <option value="Aptika">Aptika (Aplikasi Informatika)</option>
                                <option value="Komtel">Komtel (Komunikasi & Telematika)</option>
                                <option value="Statistik">Statistik & Persandian</option>
                                <option value="E-Gov">E-Government</option>
                            </select>
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tingkat Kepentingan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
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

                        {{-- Upload File --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Upload File <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer" id="uploadArea">
                                <input type="file" name="file" id="fileInput" required accept=".pdf,.doc,.docx,.xls,.xlsx" class="hidden" onchange="handleFileSelect(event)">
                                
                                <div id="uploadPlaceholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-semibold text-blue-600 hover:text-blue-700 cursor-pointer" onclick="document.getElementById('fileInput').click()">
                                            Klik untuk upload
                                        </span> atau drag & drop
                                    </p>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX (Max: 10MB)</p>
                                </div>

                                <div id="filePreview" class="hidden">
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="w-10 h-10 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900" id="fileName"></p>
                                                    <p class="text-xs text-gray-500" id="fileSize"></p>
                                                </div>
                                            </div>
                                            <button type="button" onclick="removeFile()" class="text-red-600 hover:text-red-800">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Keterangan/Catatan
                            </label>
                            <textarea name="keterangan" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Tambahkan catatan atau keterangan tambahan (opsional)"></textarea>
                        </div>

                        {{-- Tags --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tags
                            </label>
                            <input type="text" name="tags"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Pisahkan dengan koma (contoh: surat masuk, penting, 2025)">
                            <p class="text-xs text-gray-500 mt-1">Tags membantu pencarian arsip lebih mudah</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-gray-50 border-t">
                    <button type="button" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Arsip
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
#uploadArea {
    transition: all 0.3s ease;
}

#uploadArea:hover {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

#uploadArea.dragover {
    border-color: #2563eb;
    background-color: #dbeafe;
}
</style>

<script>
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('fileInput');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');
const filePreview = document.getElementById('filePreview');

// Click to upload
uploadArea.addEventListener('click', function(e) {
    if (!e.target.closest('button[onclick*="removeFile"]')) {
        fileInput.click();
    }
});

// Drag and drop
uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    
    if (e.dataTransfer.files.length) {
        fileInput.files = e.dataTransfer.files;
        handleFileSelect({ target: fileInput });
    }
});

// Handle file select
function handleFileSelect(event) {
    const file = event.target.files[0];
    
    if (!file) return;
    
    // Validate file size (10MB)
    const maxSize = 10 * 1024 * 1024; // 10MB in bytes
    if (file.size > maxSize) {
        alert('Ukuran file terlalu besar! Maksimal 10MB');
        fileInput.value = '';
        return;
    }
    
    // Validate file type
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    if (!allowedTypes.includes(file.type)) {
        alert('Format file tidak didukung! Gunakan PDF, DOC, DOCX, XLS, atau XLSX');
        fileInput.value = '';
        return;
    }
    
    // Show preview
    const fileSize = (file.size / 1024 / 1024).toFixed(2);
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = `${fileSize} MB`;
    
    uploadPlaceholder.classList.add('hidden');
    filePreview.classList.remove('hidden');
}

// Remove file
function removeFile() {
    fileInput.value = '';
    uploadPlaceholder.classList.remove('hidden');
    filePreview.classList.add('hidden');
}

// Form submit
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
});

// Reset form when modal closes
const uploadModal = document.getElementById('uploadModal');
uploadModal.addEventListener('hidden.bs.modal', function () {
    document.getElementById('uploadForm').reset();
    removeFile();
});
</script>