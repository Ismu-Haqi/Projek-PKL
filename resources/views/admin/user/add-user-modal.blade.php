<div id="addUserModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            
            {{-- Modal Header --}}
            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Tambah Pengguna Baru</h3>
                    <p class="text-sm text-gray-500">Tambahkan pegawai baru ke sistem</p>
                </div>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="addUserModal">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            {{-- Modal Body --}}
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    
                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap *</label>
                        <input type="text" name="name" id="name" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required placeholder="Nama lengkap pegawai">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email *</label>
                        <input type="email" name="email" id="email" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required placeholder="nama@diskominfo.baritokualakab.go.id">
                    </div>

                    {{-- Role & Unit/Bidang --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Role *</label>
                            <select name="role" id="role" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="pegawai" selected>Pegawai</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <label for="unit" class="block mb-2 text-sm font-medium text-gray-900">Unit/Bidang *</label>
                            <select name="unit" id="unit" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="" disabled selected>Pilih unit</option>
                                <option value="kepala_dinas">Kepala Dinas</option>
                                <option value="bidang_tik">Bidang Infrastruktur TIK</option>
                                {{-- Tambahkan unit lain sesuai kebutuhan --}}
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end p-5 space-x-3 border-t border-gray-200 rounded-b">
                    <button type="button" class="text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-300 text-sm font-medium px-5 py-2.5" data-modal-hide="addUserModal">Batal</button>
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>