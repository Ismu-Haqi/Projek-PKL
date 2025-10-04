@extends('admin.layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="p-6">
    
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Pengaturan Sistem</h1>
    <p class="text-sm text-gray-500 mb-6">Kelola konfigurasi aplikasi dan preferensi pengguna</p>

    {{-- Tabs Navigation --}}
    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 mb-6">
        <ul class="flex flex-wrap -mb-px">
            <li class="mr-2">
                <a href="#profil" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg text-blue-600 hover:border-gray-300" aria-current="page">Profil</a>
            </li>
            <li class="mr-2">
                <a href="#sistem" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Sistem</a>
            </li>
            <li class="mr-2">
                <a href="#backup" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Backup</a>
            </li>
             <li class="mr-2">
                <a href="#notifikasi" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Notifikasi</a>
            </li>
             <li class="mr-2">
                <a href="#tampilan" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Tampilan</a>
            </li>
        </ul>
    </div>

    {{-- TAB CONTENT 1: PROFIL --}}
    <div id="profil" class="bg-white p-6 rounded-lg shadow-lg mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
             <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Profil Pengguna
        </h2>
        <form>
            {{-- Foto Profil dan Info Dasar --}}
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center text-xl font-bold text-gray-600">BS</div>
                <div>
                    <button type="button" class="text-blue-600 hover:text-blue-800 font-medium">Ubah Foto</button>
                    <p class="text-xs text-gray-500">JPG, PNG max 2MB</p>
                </div>
            </div>

            {{-- Input Fields --}}
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" value="Dr. Budi Santoso" class="mt-1 block w-full border border-gray-300 rounded-md p-2" disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" value="budi.santoso@diskominfo.batola.go.id" class="mt-1 block w-full border border-gray-300 rounded-md p-2" disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" value="+62 812-3456-7890" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bagian</label>
                    <select class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option selected>Kepala Dinas</option>
                    </select>
                </div>
            </div>

            {{-- Keamanan --}}
            <div class="mt-10 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7a3 3 0 116 0v1H9V7z"></path></svg>
                    Keamanan
                </h3>
                
                <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md mb-4">
                    <div>
                        <p class="font-medium text-gray-800">Ubah Password</p>
                        <p class="text-sm text-gray-500">Terakhir diubah 3 bulan yang lalu</p>
                    </div>
                    <button type="button" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">Ubah Password</button>
                </div>
                
                <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md">
                    <div>
                        <p class="font-medium text-gray-800">Autentikasi Dua Faktor</p>
                        <p class="text-sm text-gray-500">Tambahkan keamanan untuk akun Anda</p>
                    </div>
                    {{-- Toggle Switch (Placeholder) --}}
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                 <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md font-medium">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    {{-- TAB CONTENT 2: SISTEM --}}
    <div id="sistem" class="bg-white p-6 rounded-lg shadow-lg mb-8 hidden">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
             <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m6 4a2 2 0 100-4m0 4a2 2 0 110-4m6 4a2 2 0 100-4m0 4a2 2 0 110-4m-6-8a2 2 0 100-4m0 4a2 2 0 110-4m0-4a2 2 0 100-4m0 4a2 2 0 110-4m6 8a2 2 0 100-4m0 4a2 2 0 110-4m6 0a2 2 0 100-4m0 4a2 2 0 110-4"></path></svg>
            Pengaturan Sistem
        </h2>
         <form>
            <div class="grid grid-cols-2 gap-6">
                {{-- Nama Aplikasi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Aplikasi</label>
                    <input type="text" value="DISKOMINFO Arsip Digital" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                {{-- Nama Instansi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Instansi</label>
                    <input type="text" value="Dinas Komunikasi dan Informatika Barito Kuala" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                {{-- Zona Waktu --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Zona Waktu</label>
                    <select class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option selected>Asia/Jakarta (WIB)</option>
                    </select>
                </div>
                 {{-- Bahasa --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bahasa</label>
                    <select class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option selected>Bahasa Indonesia</option>
                    </select>
                </div>
            </div>

            {{-- Keamanan Sistem --}}
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Keamanan Sistem</h3>
                 <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md mb-4">
                    <div>
                        <p class="font-medium text-gray-800">Login Session Timeout</p>
                        <p class="text-sm text-gray-500">Durasi sesi login sebelum logout otomatis</p>
                    </div>
                    <select class="p-2 border border-gray-300 rounded-md">
                        <option>8 Jam</option>
                        <option>4 Jam</option>
                    </select>
                </div>
                <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md">
                    <div>
                        <p class="font-medium text-gray-800">Force HTTPS</p>
                        <p class="text-sm text-gray-500">Paksa penggunaan koneksi HTTPS</p>
                    </div>
                    {{-- Toggle Switch --}}
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-blue-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                 <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-md font-medium">Simpan Pengaturan</button>
            </div>
        </form>
    </div>

    {{-- TAB CONTENT 3: BACKUP --}}
    <div id="backup" class="bg-white p-6 rounded-lg shadow-lg mb-8 hidden">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
             <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Backup & Restore
        </h2>
         <form>
            {{-- Backup Otomatis Harian --}}
             <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md mb-4">
                <div>
                    <p class="font-medium text-gray-800">Backup Otomatis Harian</p>
                    <p class="text-sm text-gray-500">Backup dilakukan setiap hari pada pukul 02:00 WIB</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-blue-600 rounded-full peer peer-checked:after:translate-x-full after:bg-white after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:absolute after:transition-all"></div>
                </label>
            </div>
            {{-- Backup Mingguan --}}
             <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md mb-4">
                <div>
                    <p class="font-medium text-gray-800">Backup Mingguan</p>
                    <p class="text-sm text-gray-500">Backup lengkap setiap hari Minggu</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-blue-600 rounded-full peer peer-checked:after:translate-x-full after:bg-white after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:absolute after:transition-all"></div>
                </label>
            </div>
            {{-- Retensi Backup --}}
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md mb-8">
                <div>
                    <p class="font-medium text-gray-800">Retensi Backup</p>
                    <p class="text-sm text-gray-500">Simpan backup selama 30 hari</p>
                </div>
                 <select class="p-2 border border-gray-300 rounded-md">
                    <option selected>30 Hari</option>
                    <option>60 Hari</option>
                </select>
            </div>

            {{-- Backup Manual --}}
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Backup Manual</h3>
                <div class="flex space-x-4">
                    <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">Download Backup</button>
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md font-medium">Restore Backup</button>
                </div>
                <p class="text-xs text-gray-500 mt-4">Backup terakhir: 20 Januari 2024, 02:05 WIB (Database: 45 MB, Files: 2.3 GB)</p>
            </div>
        </form>
    </div>

    {{-- TAB CONTENT 4: NOTIFIKASI --}}
    <div id="notifikasi" class="bg-white p-6 rounded-lg shadow-lg mb-8 hidden">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            Pengaturan Notifikasi
        </h2>
        <form>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Notifikasi Email</h3>
            
            {{-- Notifikasi Email Global --}}
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md mb-4">
                <div>
                    <p class="font-medium text-gray-800">Notifikasi Email</p>
                    <p class="text-sm text-gray-500">Terima pemberitahuan via email</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-blue-600 rounded-full after:bg-white after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:absolute after:transition-all"></div>
                </label>
            </div>

            {{-- Arsip Baru --}}
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md mb-4">
                <div>
                    <p class="font-medium text-gray-800">Arsip Baru</p>
                    <p class="text-sm text-gray-500">Notifikasi saat ada arsip baru ditambahkan</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-blue-600 rounded-full after:bg-white after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:absolute after:transition-all"></div>
                </label>
            </div>
            
            {{-- Disposisi Baru --}}
             <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md mb-4">
                <div>
                    <p class="font-medium text-gray-800">Disposisi Baru</p>
                    <p class="text-sm text-gray-500">Notifikasi saat menerima disposisi</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-blue-600 rounded-full after:bg-white after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:absolute after:transition-all"></div>
                </label>
            </div>
            
            {{-- Deadline Reminder --}}
             <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md mb-4">
                <div>
                    <p class="font-medium text-gray-800">Deadline Reminder</p>
                    <p class="text-sm text-gray-500">Pengingat deadline disposisi</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-blue-600 rounded-full after:bg-white after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:absolute after:transition-all"></div>
                </label>
            </div>
            
            {{-- Laporan Mingguan --}}
             <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md mb-8">
                <div>
                    <p class="font-medium text-gray-800">Laporan Mingguan</p>
                    <p class="text-sm text-gray-500">Ringkasan aktivitas mingguan</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-blue-600 rounded-full after:bg-white after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:absolute after:transition-all"></div>
                </label>
            </div>

            {{-- Konfigurasi Email --}}
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfigurasi Email</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SMTP Server</label>
                        <input type="text" value="smtp.gmail.com" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Port</label>
                        <input type="text" value="587" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" value="noreply@diskominfo.batola.go.id" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" value="******" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                </div>
                <button type="button" class="mt-4 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md font-medium">Test Koneksi Email</button>
            </div>
        </form>
    </div>
    
     {{-- TAB CONTENT 5: TAMPILAN --}}
    <div id="tampilan" class="bg-white p-6 rounded-lg shadow-lg mb-8 hidden">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6m-2-2h4a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"></path></svg>
            Tampilan & Tema
        </h2>
         <form>
            {{-- Mode Gelap --}}
             <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md mb-4">
                <div>
                    <p class="font-medium text-gray-800">Mode Gelap</p>
                    <p class="text-sm text-gray-500">Gunakan tema gelap untuk kenyamanan mata</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 rounded-full after:bg-white after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:absolute after:transition-all"></div>
                </label>
            </div>
            
            {{-- Tema Warna --}}
            <div class="grid grid-cols-2 gap-6 bg-gray-50 p-4 rounded-md mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tema Warna</label>
                    <select class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option selected>Biru (Default)</option>
                        <option>Hijau</option>
                        <option>Ungu</option>
                    </select>
                </div>
                 {{-- Ukuran Font --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ukuran Font</label>
                    <select class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option selected>Sedang</option>
                        <option>Kecil</option>
                        <option>Besar</option>
                    </select>
                </div>
            </div>
            
            {{-- Sidebar Compact --}}
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md mb-4">
                <div>
                    <p class="font-medium text-gray-800">Sidebar Compact</p>
                    <p class="text-sm text-gray-500">Gunakan sidebar yang lebih ringkas</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 rounded-full after:bg-white after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:absolute after:transition-all"></div>
                </label>
            </div>

            {{-- Animasi --}}
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-md">
                <div>
                    <p class="font-medium text-gray-800">Animasi</p>
                    <p class="text-sm text-gray-500">Aktifkan animasi transisi</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-blue-600 rounded-full after:bg-white after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:absolute after:transition-all"></div>
                </label>
            </div>
        </form>
    </div>

    {{-- Script untuk Tab Switching (JS Dasar) --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.text-sm.font-medium.text-center a');
            const contents = document.querySelectorAll('.bg-white.p-6.rounded-lg.shadow-lg');

            function hideAllContents() {
                contents.forEach(content => content.classList.add('hidden'));
                tabs.forEach(tab => {
                    tab.classList.remove('text-blue-600', 'border-blue-600');
                    tab.classList.add('border-transparent');
                });
            }

            function activateTab(tab) {
                hideAllContents();
                
                tab.classList.add('text-blue-600', 'border-blue-600');
                tab.classList.remove('border-transparent');
                
                const targetId = tab.getAttribute('href').substring(1);
                const targetContent = document.getElementById(targetId);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                }
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    activateTab(this);
                });
            });

            // Activate default tab (Profil) on load
            if (tabs.length > 0) {
                activateTab(tabs[0]);
            }
        });
    </script>
    @endpush
</div>
@endsection