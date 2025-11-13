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
                        <input type="text" name="name" id="name" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required placeholder="Contoh: Ahmad Budi Santoso">
                    </div>

                    {{-- Email & No. Telepon --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email *</label>
                            <input type="email" name="email" id="email" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required placeholder="user@diskominfo.go.id">
                        </div>
                        <div>
                            <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">No. Telepon</label>
                            <input type="text" name="phone" id="phone" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username *</label>
                        <input type="text" name="username" id="username" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required placeholder="username_unik">
                        <p class="mt-1 text-xs text-gray-500">Username harus unik dan tidak boleh sama dengan user lain</p>
                    </div>

                    {{-- Role & Unit Kerja --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Role *</label>
                            <select name="role" id="role" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="" disabled selected>-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="pimpinan">Pimpinan</option>
                            </select>
                        </div>
                        <div>
                            <label for="unit" class="block mb-2 text-sm font-medium text-gray-900">Unit Kerja</label>
                            <select name="unit" id="unit" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Semua Unit</option>
                                <option value="Sekretariat">Sekretariat</option>
                                <option value="IKP">IKP</option>
                                <option value="SP">SP (Statistik & Persandian)</option>
                                <option value="E-Government">E-Government</option>
                            </select>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password *</label>
                            <input type="password" name="password" id="password" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required placeholder="Minimal 8 karakter">
                            <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Konfirmasi Password *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required placeholder="Ulangi password">
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-1">Informasi Penting</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>User akan langsung aktif setelah dibuat</li>
                                    <li>Password harus minimal 8 karakter</li>
                                    <li>Username harus unik dan tidak boleh sama dengan user lain</li>
                                    <li>Pastikan email yang digunakan valid</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end p-5 space-x-3 border-t border-gray-200 rounded-b">
                    <button type="button" class="text-gray-900 bg-white hover:bg-gray-100 rounded-lg border border-gray-300 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10" data-modal-hide="addUserModal">
                        Batal
                    </button>
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        <svg class="w-4 h-4 inline mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>