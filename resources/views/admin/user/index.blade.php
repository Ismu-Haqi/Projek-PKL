@extends('admin.layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="p-6">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
            <p class="text-sm text-gray-500">Kelola daftar pengguna (Admin dan Staff) di sistem</p>
        </div>
        
        {{-- Tombol Tambah Pengguna --}}
        <button type="button" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-md flex items-center"
                data-modal-target="addUserModal" data-modal-toggle="addUserModal">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pengguna
        </button>
    </div>

    {{-- Filter dan Search Bar --}}
    <div class="bg-white p-4 rounded-lg shadow-lg mb-6 flex space-x-3 items-center">
        {{-- Search Input --}}
        <div class="relative flex-grow border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">🔍</span>
            <input type="text" placeholder="Cari pengguna berdasarkan nama, role, atau unit..." class="w-full py-2 pl-10 pr-4 rounded-lg focus:outline-none text-sm">
        </div>
        
        {{-- Filter Role --}}
        <select class="py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <option selected disabled>Semua Role</option>
            <option>Admin</option>
            <option>Pegawai</option>
        </select>
        
        {{-- Filter Unit --}}
        <select class="py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <option selected disabled>Semua Unit</option>
            <option>Diskominfo</option>
            <option>Sekretariat</option>
        </select>
    </div>

    {{-- Tabel Pengguna Placeholder --}}
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold mb-4">Daftar Pengguna Aktif</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit/Bidang</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{-- Baris Placeholder --}}
                @foreach (range(1, 5) as $i)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Aris Saputera, S.STP., M, Si.</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">ArisSaputera@diskominfo.batola.go.id</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Admin</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Kepala Dinas</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah Pengguna Baru --}}
@include('admin.user.add-user-modal')
@endsection