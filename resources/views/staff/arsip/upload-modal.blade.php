<div id="uploadModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-bold text-gray-900">
                    Unggah Arsip Baru
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="uploadModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <form action="{{ route('admin.arsip.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    
                    {{-- Judul Dokumen --}}
                    <div>
                        <label for="judul_dokumen" class="block mb-2 text-sm font-medium text-gray-900">Judul Dokumen</label>
                        <input type="text" name="judul_dokumen" id="judul_dokumen" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required placeholder="Masukkan judul dokumen">
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 resize-none" placeholder="Masukkan deskripsi singkat"></textarea>
                    </div>

                    {{-- Kategori & Unit/Bidang --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="kategori" class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                            <select name="kategori" id="kategori" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Surat Keputusan">Surat Keputusan</option>
                                <option value="Laporan Keuangan">Laporan Keuangan</option>
                                <option value="Dokumentasi">Dokumentasi</option>
                            </select>
                        </div>
                        <div>
                            <label for="unit" class="block mb-2 text-sm font-medium text-gray-900">Unit/Bidang</label>
                            <select name="unit" id="unit" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="" disabled selected>Pilih Unit</option>
                                <option value="Diskominfo">Diskominfo</option>
                                <option value="Sekretariat">Sekretariat</option>
                                <option value="Bidang Keuangan">Bidang Keuangan</option>
                            </select>
                        </div>
                    </div>
                    
                    {{-- Tanggal Dokumen --}}
                    <div>
                        <label for="tanggal_dokumen" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Dokumen</label>
                        <div class="relative">
                            {{-- Input type="date" untuk tanggal dokumen --}}
                            <input type="date" name="tanggal_dokumen" id="tanggal_dokumen" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    {{-- File Upload (Tambahan yang perlu disiapkan) --}}
                    <div>
                        <label for="file_arsip" class="block mb-2 text-sm font-medium text-gray-900">Pilih File Arsip (PDF, Doc, Excel)</label>
                        <input type="file" name="file_arsip" id="file_arsip" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none" required>
                    </div>

                </div>
                <div class="flex items-center justify-end p-5 space-x-2 border-t border-gray-200 rounded-b">
                    <button type="button" class="text-gray-900 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg border border-gray-300 text-sm font-medium px-5 py-2.5">Batal</button>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Simpan Arsip</button>
                </div>
            </form>
        </div>
    </div>
</div>