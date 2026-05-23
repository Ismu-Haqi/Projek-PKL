@extends('admin.layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
{{-- Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Pengaturan Sistem</h1>
    <p class="text-gray-600 mt-1">Kelola pengaturan aplikasi dan profil Anda</p>
</div>

{{-- Tabs Navigation --}}
<div class="bg-white rounded-lg shadow-sm">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px" aria-label="Tabs">
            <button onclick="switchTab('profil')" 
                    id="tab-profil" 
                    class="tab-button active border-b-2 border-blue-500 py-4 px-6 text-center font-medium text-blue-600 focus:outline-none">
                <i class="fas fa-user mr-2"></i>
                Profil
            </button>
            
            @if(Auth::user()->role === 'admin')
            <button onclick="switchTab('sistem')" 
                    id="tab-sistem" 
                    class="tab-button border-b-2 border-transparent py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none">
                <i class="fas fa-cog mr-2"></i>
                Sistem
            </button>
            
            <button onclick="switchTab('tampilan')" 
                    id="tab-tampilan" 
                    class="tab-button border-b-2 border-transparent py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none">
                <i class="fas fa-palette mr-2"></i>
                Tampilan
            </button>
            
            <button onclick="switchTab('backup')" 
                    id="tab-backup" 
                    class="tab-button border-b-2 border-transparent py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none">
                <i class="fas fa-database mr-2"></i>
                Backup
            </button>
            @endif
        </nav>
    </div>

    {{-- Tab Contents --}}
    <div class="p-6">
        {{-- Tab Profil --}}
        <div id="content-profil" class="tab-content">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Profil Pengguna</h2>
            
            <form action="{{ route('admin.pengaturan.update-profil') }}" method="POST" id="formProfil" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', Auth::user()->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', Auth::user()->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Ubah Password</h3>
                    <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end pt-4">
                    <button type="button" 
                            onclick="confirmSaveProfile()"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        @if(Auth::user()->role === 'admin')
        {{-- Tab Sistem --}}
        <div id="content-sistem" class="tab-content hidden">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pengaturan Sistem</h2>
            
            <form action="{{ route('admin.pengaturan.update') }}" method="POST" id="formSistem" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Aplikasi</label>
                        <input type="text" 
                               name="app_name" 
                               id="app_name" 
                               value="{{ old('app_name', $settings['app_name'] ?? 'GANDARIA') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                        <select name="timezone" 
                                id="timezone" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>WIB - Jakarta</option>
                            <option value="Asia/Makassar" {{ ($settings['timezone'] ?? '') == 'Asia/Makassar' ? 'selected' : '' }}>WITA - Makassar</option>
                            <option value="Asia/Jayapura" {{ ($settings['timezone'] ?? '') == 'Asia/Jayapura' ? 'selected' : '' }}>WIT - Jayapura</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="items_per_page" class="block text-sm font-medium text-gray-700 mb-1">Items Per Halaman</label>
                        <input type="number" 
                               name="items_per_page" 
                               id="items_per_page" 
                               value="{{ old('items_per_page', $settings['items_per_page'] ?? 10) }}"
                               min="5"
                               max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="date_format" class="block text-sm font-medium text-gray-700 mb-1">Format Tanggal</label>
                        <select name="date_format" 
                                id="date_format" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="d/m/Y" {{ ($settings['date_format'] ?? 'd/m/Y') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                            <option value="Y-m-d" {{ ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                        </select>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Fitur Sistem</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="enable_registration" 
                                   id="enable_registration" 
                                   value="1"
                                   {{ ($settings['enable_registration'] ?? 0) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="enable_registration" class="ml-2 text-sm text-gray-700">
                                Aktifkan Registrasi Pengguna Baru
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="enable_notifications" 
                                   id="enable_notifications" 
                                   value="1"
                                   {{ ($settings['enable_notifications'] ?? 1) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="enable_notifications" class="ml-2 text-sm text-gray-700">
                                Aktifkan Notifikasi Sistem
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="maintenance_mode" 
                                   id="maintenance_mode" 
                                   value="1"
                                   {{ ($settings['maintenance_mode'] ?? 0) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="maintenance_mode" class="ml-2 text-sm text-gray-700">
                                Mode Maintenance (Nonaktifkan akses sementara)
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-6">
                    <button type="button" 
                            onclick="confirmClearCache()"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Bersihkan Cache
                    </button>
                    
                    <button type="button" 
                            onclick="confirmSaveSystem()"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        {{-- Tab Tampilan --}}
        <div id="content-tampilan" class="tab-content hidden">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pengaturan Tampilan</h2>
            
            <form action="{{ route('admin.pengaturan.update-appearance') }}" method="POST" id="formTampilan" class="space-y-6">
                @csrf
                @method('PUT')
                
                {{-- Theme Section --}}
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-palette mr-2 text-purple-500"></i>
                        Tema Aplikasi
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Light Theme --}}
                        <div class="relative">
                            <input type="radio" name="theme" id="theme-light" value="light" 
                                   {{ ($settings['theme'] ?? 'light') == 'light' ? 'checked' : '' }}
                                   class="peer sr-only">
                            <label for="theme-light" 
                                   class="flex flex-col p-4 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 transition-all">
                                <div class="w-full h-32 bg-gradient-to-br from-gray-50 to-gray-100 rounded-md mb-3 flex items-center justify-center">
                                    <i class="fas fa-sun text-4xl text-yellow-500"></i>
                                </div>
                                <span class="font-semibold text-gray-800 text-center">Terang</span>
                                <span class="text-xs text-gray-500 text-center mt-1">Mode terang (default)</span>
                            </label>
                            <div class="absolute top-2 right-2 hidden peer-checked:block">
                                <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                            </div>
                        </div>

                        {{-- Dark Theme --}}
                        <div class="relative">
                            <input type="radio" name="theme" id="theme-dark" value="dark" 
                                   {{ ($settings['theme'] ?? '') == 'dark' ? 'checked' : '' }}
                                   class="peer sr-only">
                            <label for="theme-dark" 
                                   class="flex flex-col p-4 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 transition-all">
                                <div class="w-full h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-md mb-3 flex items-center justify-center">
                                    <i class="fas fa-moon text-4xl text-blue-300"></i>
                                </div>
                                <span class="font-semibold text-gray-800 text-center">Gelap</span>
                                <span class="text-xs text-gray-500 text-center mt-1">Mode gelap</span>
                            </label>
                            <div class="absolute top-2 right-2 hidden peer-checked:block">
                                <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                            </div>
                        </div>

                        {{-- Auto Theme --}}
                        <div class="relative">
                            <input type="radio" name="theme" id="theme-auto" value="auto" 
                                   {{ ($settings['theme'] ?? '') == 'auto' ? 'checked' : '' }}
                                   class="peer sr-only">
                            <label for="theme-auto" 
                                   class="flex flex-col p-4 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 transition-all">
                                <div class="w-full h-32 bg-gradient-to-br from-gray-100 via-gray-300 to-gray-800 rounded-md mb-3 flex items-center justify-center">
                                    <i class="fas fa-adjust text-4xl text-gray-600"></i>
                                </div>
                                <span class="font-semibold text-gray-800 text-center">Otomatis</span>
                                <span class="text-xs text-gray-500 text-center mt-1">Sesuai sistem</span>
                            </label>
                            <div class="absolute top-2 right-2 hidden peer-checked:block">
                                <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Color Scheme Section --}}
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-swatchbook mr-2 text-pink-500"></i>
                        Skema Warna Aksen
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        <div class="relative">
                            <input type="radio" name="accent_color" id="color-blue" value="blue" 
                                   {{ ($settings['accent_color'] ?? 'blue') == 'blue' ? 'checked' : '' }}
                                   class="peer sr-only">
                            <label for="color-blue" 
                                   class="flex flex-col items-center p-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 transition-all">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full mb-2"></div>
                                <span class="text-xs font-medium text-gray-700">Biru</span>
                            </label>
                            <div class="absolute top-1 right-1 hidden peer-checked:block">
                                <i class="fas fa-check-circle text-blue-500"></i>
                            </div>
                        </div>

                        <div class="relative">
                            <input type="radio" name="accent_color" id="color-purple" value="purple" 
                                   {{ ($settings['accent_color'] ?? '') == 'purple' ? 'checked' : '' }}
                                   class="peer sr-only">
                            <label for="color-purple" 
                                   class="flex flex-col items-center p-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-500 peer-checked:border-purple-500 peer-checked:ring-2 peer-checked:ring-purple-200 transition-all">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full mb-2"></div>
                                <span class="text-xs font-medium text-gray-700">Ungu</span>
                            </label>
                            <div class="absolute top-1 right-1 hidden peer-checked:block">
                                <i class="fas fa-check-circle text-purple-500"></i>
                            </div>
                        </div>

                        <div class="relative">
                            <input type="radio" name="accent_color" id="color-green" value="green" 
                                   {{ ($settings['accent_color'] ?? '') == 'green' ? 'checked' : '' }}
                                   class="peer sr-only">
                            <label for="color-green" 
                                   class="flex flex-col items-center p-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-green-500 peer-checked:border-green-500 peer-checked:ring-2 peer-checked:ring-green-200 transition-all">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full mb-2"></div>
                                <span class="text-xs font-medium text-gray-700">Hijau</span>
                            </label>
                            <div class="absolute top-1 right-1 hidden peer-checked:block">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>

                        <div class="relative">
                            <input type="radio" name="accent_color" id="color-red" value="red" 
                                   {{ ($settings['accent_color'] ?? '') == 'red' ? 'checked' : '' }}
                                   class="peer sr-only">
                            <label for="color-red" 
                                   class="flex flex-col items-center p-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 peer-checked:border-red-500 peer-checked:ring-2 peer-checked:ring-red-200 transition-all">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-full mb-2"></div>
                                <span class="text-xs font-medium text-gray-700">Merah</span>
                            </label>
                            <div class="absolute top-1 right-1 hidden peer-checked:block">
                                <i class="fas fa-check-circle text-red-500"></i>
                            </div>
                        </div>

                        <div class="relative">
                            <input type="radio" name="accent_color" id="color-orange" value="orange" 
                                   {{ ($settings['accent_color'] ?? '') == 'orange' ? 'checked' : '' }}
                                   class="peer sr-only">
                            <label for="color-orange" 
                                   class="flex flex-col items-center p-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-orange-500 peer-checked:border-orange-500 peer-checked:ring-2 peer-checked:ring-orange-200 transition-all">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full mb-2"></div>
                                <span class="text-xs font-medium text-gray-700">Oranye</span>
                            </label>
                            <div class="absolute top-1 right-1 hidden peer-checked:block">
                                <i class="fas fa-check-circle text-orange-500"></i>
                            </div>
                        </div>

                        <div class="relative">
                            <input type="radio" name="accent_color" id="color-indigo" value="indigo" 
                                   {{ ($settings['accent_color'] ?? '') == 'indigo' ? 'checked' : '' }}
                                   class="peer sr-only">
                            <label for="color-indigo" 
                                   class="flex flex-col items-center p-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-200 transition-all">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full mb-2"></div>
                                <span class="text-xs font-medium text-gray-700">Indigo</span>
                            </label>
                            <div class="absolute top-1 right-1 hidden peer-checked:block">
                                <i class="fas fa-check-circle text-indigo-500"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Text Size & Display Options --}}
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-text-height mr-2 text-green-500"></i>
                        Ukuran Teks & Tampilan
                    </h3>
                    
                    {{-- Text Size Selector --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Ukuran Teks</label>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                            {{-- Text size options (xs, sm, md, lg, xl) --}}
                            <div class="relative">
                                <input type="radio" name="text_size" id="text-xs" value="xs" 
                                       {{ ($settings['text_size'] ?? '') == 'xs' ? 'checked' : '' }}
                                       class="peer sr-only">
                                <label for="text-xs" 
                                       class="flex flex-col items-center p-4 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 transition-all">
                                    <span class="text-xs font-semibold mb-1">Aa</span>
                                    <span class="text-xs text-gray-600">Sangat Kecil</span>
                                    <span class="text-xs text-gray-400">12px</span>
                                </label>
                                <div class="absolute top-1 right-1 hidden peer-checked:block">
                                    <i class="fas fa-check-circle text-blue-500"></i>
                                </div>
                            </div>

                            <div class="relative">
                                <input type="radio" name="text_size" id="text-sm" value="sm" 
                                       {{ ($settings['text_size'] ?? '') == 'sm' ? 'checked' : '' }}
                                       class="peer sr-only">
                                <label for="text-sm" 
                                       class="flex flex-col items-center p-4 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 transition-all">
                                    <span class="text-sm font-semibold mb-1">Aa</span>
                                    <span class="text-xs text-gray-600">Kecil</span>
                                    <span class="text-xs text-gray-400">13px</span>
                                </label>
                                <div class="absolute top-1 right-1 hidden peer-checked:block">
                                    <i class="fas fa-check-circle text-blue-500"></i>
                                </div>
                            </div>

                            <div class="relative">
                                <input type="radio" name="text_size" id="text-md" value="md" 
                                       {{ ($settings['text_size'] ?? 'md') == 'md' ? 'checked' : '' }}
                                       class="peer sr-only">
                                <label for="text-md" 
                                       class="flex flex-col items-center p-4 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 transition-all">
                                    <span class="text-base font-semibold mb-1">Aa</span>
                                    <span class="text-xs text-gray-600">Sedang</span>
                                    <span class="text-xs text-gray-400">14px</span>
                                </label>
                                <div class="absolute top-1 right-1 hidden peer-checked:block">
                                    <i class="fas fa-check-circle text-blue-500"></i>
                                </div>
                            </div>

                            <div class="relative">
                                <input type="radio" name="text_size" id="text-lg" value="lg" 
                                       {{ ($settings['text_size'] ?? '') == 'lg' ? 'checked' : '' }}
                                       class="peer sr-only">
                                <label for="text-lg" 
                                       class="flex flex-col items-center p-4 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 transition-all">
                                    <span class="text-lg font-semibold mb-1">Aa</span>
                                    <span class="text-xs text-gray-600">Besar</span>
                                    <span class="text-xs text-gray-400">16px</span>
                                </label>
                                <div class="absolute top-1 right-1 hidden peer-checked:block">
                                    <i class="fas fa-check-circle text-blue-500"></i>
                                </div>
                            </div>

                            <div class="relative">
                                <input type="radio" name="text_size" id="text-xl" value="xl" 
                                       {{ ($settings['text_size'] ?? '') == 'xl' ? 'checked' : '' }}
                                       class="peer sr-only">
                                <label for="text-xl" 
                                       class="flex flex-col items-center p-4 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 transition-all">
                                    <span class="text-xl font-semibold mb-1">Aa</span>
                                    <span class="text-xs text-gray-600">Sangat Besar</span>
                                    <span class="text-xs text-gray-400">18px</span>
                                </label>
                                <div class="absolute top-1 right-1 hidden peer-checked:block">
                                    <i class="fas fa-check-circle text-blue-500"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Display Toggles --}}
                    <div class="space-y-3 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <label for="compact_mode" class="font-medium text-gray-700">Mode Kompak</label>
                                <p class="text-sm text-gray-500">Mengurangi spacing untuk menampilkan lebih banyak konten</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="compact_mode" id="compact_mode" value="1"
                                       {{ ($settings['compact_mode'] ?? 0) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <label for="smooth_scrolling" class="font-medium text-gray-700">Smooth Scrolling</label>
                                <p class="text-sm text-gray-500">Membuat scrolling lebih halus</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="smooth_scrolling" id="smooth_scrolling" value="1"
                                       {{ ($settings['smooth_scrolling'] ?? 1) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <button type="button" 
                            onclick="confirmResetAppearance()"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-undo mr-2"></i>
                        Reset ke Default
                    </button>
                    
                    <button type="button" 
                            onclick="confirmSaveAppearance()"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        {{-- Tab Backup --}}
        <div id="content-backup" class="tab-content hidden">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Backup Database</h2>
            <p class="text-gray-500 text-sm mb-5">Buat salinan database MySQL ke file <code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">.sql</code> yang tersimpan di server.</p>

            {{-- Info Box --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Informasi Backup</p>
                    <p>File backup disimpan di <code class="bg-blue-100 px-1.5 py-0.5 rounded">storage/app/backups/</code>. Sistem otomatis memilih metode terbaik: <strong>mysqldump</strong> (jika tersedia) atau <strong>PHP-native</strong> sebagai fallback. Backup berisi seluruh struktur tabel dan data.</p>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-wrap gap-3 mb-6">
                <button type="button" id="btnCreateBackup" onclick="confirmCreateBackup()"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-5 rounded-lg transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Buat Backup Sekarang
                </button>
                <button type="button" onclick="loadBackupList()"
                        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-5 rounded-lg border border-gray-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Refresh Daftar Backup
                </button>
            </div>

            {{-- Daftar Backup --}}
            <div>
                <h3 class="text-base font-semibold text-gray-700 mb-3">📂 Daftar File Backup</h3>
                <div id="backup-list-content" class="bg-gray-50 border border-gray-200 rounded-lg p-4 min-h-24">
                    <div class="text-center text-gray-400 py-6">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        <p class="text-sm">Klik "Refresh Daftar Backup" untuk memuat daftar file</p>
                    </div>
                </div>
            </div>

            {{-- ===== GOOGLE DRIVE BACKUP ===== --}}
            <div class="mt-8 border-t border-gray-200 pt-6">
                <div class="flex items-center gap-3 mb-4">
                    <img src="https://www.gstatic.com/images/branding/product/1x/drive_2020q4_48dp.png"
                         alt="Google Drive" class="w-7 h-7">
                    <h3 class="text-base font-semibold text-gray-700">Backup ke Google Drive</h3>
                    <span id="gdrive-status-badge" class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Memeriksa...</span>
                </div>

                {{-- Info setup --}}
                <div id="gdrive-setup-info" class="hidden mb-5 p-4 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800">
                    <p class="font-semibold mb-2">⚙️ Konfigurasi belum lengkap</p>
                    <ol class="list-decimal pl-5 space-y-1">
                        <li>Buka <a href="https://console.cloud.google.com" target="_blank" class="underline">Google Cloud Console</a>, buat project & aktifkan <strong>Google Drive API</strong></li>
                        <li>Buat <strong>Service Account</strong> di IAM &amp; Admin → Service Accounts</li>
                        <li>Download JSON key, simpan di <code class="bg-amber-100 px-1 rounded">storage/app/google-credentials.json</code></li>
                        <li>Buat folder di Google Drive, share ke email service account (akses <strong>Editor</strong>)</li>
                        <li>Salin ID folder dari URL Drive, isi <code class="bg-amber-100 px-1 rounded">GOOGLE_DRIVE_FOLDER_ID</code> di <code class="bg-amber-100 px-1 rounded">.env</code></li>
                        <li>Set <code class="bg-amber-100 px-1 rounded">GOOGLE_DRIVE_AUTO_BACKUP=true</code> untuk backup otomatis harian</li>
                    </ol>
                </div>

                {{-- Panel konfigurasi terkini --}}
                <div id="gdrive-config-panel" class="hidden mb-5 bg-green-50 border border-green-200 rounded-lg p-4 text-sm">
                    <p class="font-semibold text-green-800 mb-2">✅ Konfigurasi Terdeteksi</p>
                    <div class="grid grid-cols-2 gap-2 text-green-700">
                        <span>Folder ID:</span> <span id="gdrive-folder-id" class="font-mono">-</span>
                        <span>Auto Backup:</span> <span id="gdrive-auto-status">-</span>
                    </div>
                </div>

                {{-- Tombol aksi --}}
                <div class="flex flex-wrap gap-3 mb-5">
                    <button onclick="gdriveTestConnection()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tes Koneksi Drive
                    </button>
                    <button onclick="gdriveBackup('arsip')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Backup Arsip ke Drive
                    </button>
                    <button onclick="gdriveBackup('disposisi')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Backup Disposisi ke Drive
                    </button>
                    <button onclick="gdriveBackup('all')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Backup Semua ke Drive
                    </button>
                </div>

                {{-- Progress / hasil --}}
                <div id="gdrive-progress" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-800">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <span id="gdrive-progress-text">Sedang mengupload file ke Google Drive...</span>
                    </div>
                </div>

                <div id="gdrive-result" class="hidden rounded-lg p-4 text-sm"></div>
            </div>
            {{-- ===== END GOOGLE DRIVE ===== --}}

        </div>
        @endif
    </div>
</div>

{{-- Include SweetAlert --}}
@include('partials.sweetalert')

@endsection

@push('scripts')
<script>
// Tab Switching Function
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    const selectedContent = document.getElementById(`content-${tabName}`);
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
    
    const selectedTab = document.getElementById(`tab-${tabName}`);
    if (selectedTab) {
        selectedTab.classList.add('active', 'border-blue-500', 'text-blue-600');
        selectedTab.classList.remove('border-transparent', 'text-gray-500');
    }
}

// ============================================
// CONFIRM SAVE PROFILE
// ============================================
function confirmSaveProfile() {
    if (typeof Swal === 'undefined') {
        document.getElementById('formProfil').submit();
        return;
    }

    const password = document.getElementById('password').value;
    const hasPasswordChange = password.length > 0;
    
    Swal.fire({
        title: '💾 Simpan Perubahan Profil?',
        html: `
            <div class="text-left">
                <p class="text-gray-700 mb-3">Perubahan yang akan disimpan:</p>
                <ul class="text-sm text-gray-600 space-y-1 mb-3">
                    <li>✓ Informasi profil (nama, email)</li>
                    ${hasPasswordChange ? '<li class="text-orange-600">⚠️ Password akan diubah</li>' : ''}
                </ul>
                ${hasPasswordChange ? '<div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded"><p class="text-sm text-yellow-800"><i class="fas fa-exclamation-triangle mr-2"></i>Anda harus login ulang dengan password baru</p></div>' : ''}
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-save mr-2"></i> Ya, Simpan',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
        reverseButtons: true,
        customClass: {
            popup: 'animated-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading('Menyimpan perubahan profil...');
            document.getElementById('formProfil').submit();
        }
    });
}

// ============================================
// CONFIRM SAVE SYSTEM SETTINGS
// ============================================
function confirmSaveSystem() {
    if (typeof Swal === 'undefined') {
        document.getElementById('formSistem').submit();
        return;
    }

    const maintenanceMode = document.getElementById('maintenance_mode').checked;
    
    Swal.fire({
        title: '⚙️ Simpan Pengaturan Sistem?',
        html: `
            <div class="text-left">
                <p class="text-gray-700 mb-3">Pengaturan sistem akan diperbarui</p>
                ${maintenanceMode ? '<div class="bg-red-50 border-l-4 border-red-400 p-3 rounded mb-3"><p class="text-sm text-red-600 font-semibold"><i class="fas fa-exclamation-circle mr-2"></i>Mode Maintenance akan <strong>AKTIF</strong>. User tidak dapat mengakses sistem!</p></div>' : ''}
                <p class="text-sm text-gray-600">Pastikan pengaturan sudah benar sebelum menyimpan</p>
            </div>
        `,
        icon: maintenanceMode ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-save mr-2"></i> Ya, Simpan',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
        reverseButtons: true,
        customClass: {
            popup: 'animated-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading('Menyimpan pengaturan sistem...');
            document.getElementById('formSistem').submit();
        }
    });
}

// ============================================
// CONFIRM SAVE APPEARANCE
// ============================================
function confirmSaveAppearance() {
    if (typeof Swal === 'undefined') {
        document.getElementById('formTampilan').submit();
        return;
    }

    Swal.fire({
        title: '🎨 Simpan Pengaturan Tampilan?',
        html: `
            <div class="text-left">
                <p class="text-gray-700 mb-3">Pengaturan tampilan akan diterapkan:</p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>✓ Tema dan warna aksen</li>
                    <li>✓ Ukuran teks</li>
                    <li>✓ Opsi tampilan lainnya</li>
                </ul>
                <p class="text-sm text-blue-600 mt-3"><i class="fas fa-info-circle mr-2"></i>Perubahan akan diterapkan setelah refresh halaman</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-save mr-2"></i> Ya, Simpan',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
        reverseButtons: true,
        customClass: {
            popup: 'animated-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading('Menyimpan pengaturan tampilan...');
            document.getElementById('formTampilan').submit();
        }
    });
}

// ============================================
// CONFIRM CLEAR CACHE
// ============================================
function confirmClearCache() {
    if (typeof Swal === 'undefined') {
        if (confirm('Bersihkan semua cache sistem?')) {
            clearCacheProcess();
        }
        return;
    }

    Swal.fire({
        title: '🗑️ Bersihkan Cache?',
        html: `
            <div class="text-left">
                <p class="text-gray-700 mb-3">Cache yang akan dibersihkan:</p>
                <ul class="text-sm text-gray-600 space-y-1 mb-3">
                    <li>• Cache konfigurasi</li>
                    <li>• Cache view/template</li>
                    <li>• Cache route</li>
                    <li>• Cache aplikasi</li>
                </ul>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Proses ini akan meningkatkan performa sistem
                    </p>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#eab308',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i> Ya, Bersihkan',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
        reverseButtons: true,
        customClass: {
            popup: 'animated-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            clearCacheProcess();
        }
    });
}

function clearCacheProcess() {
    Swal.fire({
        title: 'Membersihkan Cache...',
        html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-yellow-500 mb-3"></i><p>Mohon tunggu sebentar</p></div>',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('{{ route("admin.pengaturan.clear-cache") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '✅ Cache Berhasil Dibersihkan!',
                html: `<p class="text-gray-700">${data.message}</p>`,
                confirmButtonColor: '#22c55e',
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Membersihkan Cache',
                html: `<p class="text-gray-700">${data.message || 'Terjadi kesalahan'}</p>`,
                confirmButtonColor: '#dc2626'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Gagal membersihkan cache. Silakan coba lagi.',
            confirmButtonColor: '#dc2626'
        });
    });
}

// ============================================
// CONFIRM CREATE BACKUP
// ============================================
function confirmCreateBackup() {
    if (typeof Swal === 'undefined') {
        if (confirm('Buat backup database sekarang?')) {
            createBackupProcess();
        }
        return;
    }

    Swal.fire({
        title: '💾 Buat Backup Database?',
        html: `
            <div class="text-left">
                <p class="text-gray-700 mb-3">Backup akan menyimpan:</p>
                <ul class="text-sm text-gray-600 space-y-1 mb-3">
                    <li>• Semua data database</li>
                    <li>• Struktur tabel</li>
                    <li>• Data pengguna & arsip</li>
                </ul>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-clock mr-2"></i>
                        Proses mungkin memakan waktu tergantung ukuran database
                    </p>
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-database mr-2"></i> Ya, Buat Backup',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
        reverseButtons: true,
        customClass: {
            popup: 'animated-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            createBackupProcess();
        }
    });
}

function createBackupProcess() {
    Swal.fire({
        title: 'Membuat Backup...',
        html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-green-500 mb-3"></i><p>Mohon tunggu, sedang memproses backup database...</p></div>',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('{{ route("admin.pengaturan.backup") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '✅ Backup Berhasil Dibuat!',
                html: `
                    <div class="text-left">
                        <p class="text-gray-700 mb-2">${data.message}</p>
                        <div class="bg-gray-50 p-3 rounded">
                            <p class="text-sm text-gray-600">File: <span class="font-mono font-semibold text-blue-600">${data.filename}</span></p>
                            <p class="text-sm text-gray-600">Ukuran: <span class="font-semibold">${data.size || '-'}</span></p>
                        </div>
                    </div>
                `,
                confirmButtonColor: '#22c55e',
                confirmButtonText: 'OK'
            }).then(() => {
                loadBackupList();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Membuat Backup',
                html: `<p class="text-gray-700">${data.message || 'Terjadi kesalahan'}</p>`,
                confirmButtonColor: '#dc2626'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Gagal membuat backup. Silakan coba lagi.',
            confirmButtonColor: '#dc2626'
        });
    });
}

// ============================================
// CONFIRM RESET APPEARANCE
// ============================================
function confirmResetAppearance() {
    if (typeof Swal === 'undefined') {
        if (confirm('Reset semua pengaturan tampilan ke default?')) {
            resetAppearanceToDefault();
        }
        return;
    }

    Swal.fire({
        title: '🔄 Reset Pengaturan Tampilan?',
        html: `
            <div class="text-left">
                <p class="text-gray-700 mb-3">Pengaturan akan dikembalikan ke default:</p>
                <ul class="text-sm text-gray-600 space-y-1 mb-3">
                    <li>• Tema: Light</li>
                    <li>• Warna aksen: Biru</li>
                    <li>• Ukuran teks: Sedang</li>
                    <li>• Opsi tampilan: Default</li>
                </ul>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Anda masih perlu klik "Simpan" untuk menerapkan
                    </p>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6b7280',
        cancelButtonColor: '#dc2626',
        confirmButtonText: '<i class="fas fa-undo mr-2"></i> Ya, Reset',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
        reverseButtons: true,
        customClass: {
            popup: 'animated-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            resetAppearanceToDefault();
            Swal.fire({
                icon: 'success',
                title: 'Pengaturan Direset!',
                text: 'Jangan lupa klik "Simpan Pengaturan" untuk menerapkan perubahan',
                confirmButtonColor: '#22c55e',
                timer: 3000,
                timerProgressBar: true
            });
        }
    });
}

function resetAppearanceToDefault() {
    document.getElementById('theme-light').checked = true;
    document.getElementById('color-blue').checked = true;
    document.getElementById('text-md').checked = true;
    document.getElementById('compact_mode').checked = false;
    document.getElementById('smooth_scrolling').checked = true;
}

// Load Backup List Function
function loadBackupList() {
    const backupListContent = document.getElementById('backup-list-content');
    
    backupListContent.innerHTML = `
        <div class="text-center text-gray-400 py-6">
            <svg class="w-8 h-8 animate-spin mx-auto mb-2 text-blue-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <p class="text-sm">Memuat daftar backup...</p>
        </div>
    `;
    
    fetch('{{ route("admin.pengaturan.backup-list") }}', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.backups.length > 0) {
            let html = '<div class="divide-y divide-gray-200">';
            data.backups.forEach(backup => {
                html += `
                    <div class="flex items-center justify-between py-3 px-2 hover:bg-gray-100 rounded-lg transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-800 text-sm">${backup.name}</p>
                                <p class="text-xs text-gray-400">${backup.size} &bull; ${backup.date}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="/admin/pengaturan/backup/download/${encodeURIComponent(backup.name)}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh
                            </a>
                            <button onclick="confirmDeleteBackup('${backup.name}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-medium hover:bg-red-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            backupListContent.innerHTML = html;
        } else {
            backupListContent.innerHTML = `
                <div class="text-center text-gray-400 py-8">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/></svg>
                    <p class="font-medium">Belum ada file backup</p>
                    <p class="text-sm mt-1">Klik "Buat Backup Sekarang" untuk membuat backup pertama</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        backupListContent.innerHTML = `
            <div class="text-center text-red-400 py-6">
                <p class="text-sm">Gagal memuat daftar backup. Silakan coba lagi.</p>
            </div>
        `;
    });
}

// Hapus Backup
function confirmDeleteBackup(filename) {
    if (typeof Swal === 'undefined') {
        if (confirm('Hapus file backup ini?')) deleteBackupProcess(filename);
        return;
    }
    Swal.fire({
        title: '🗑️ Hapus File Backup?',
        html: `<p class="text-gray-700">File <code class="bg-gray-100 px-1 rounded">${filename}</code> akan dihapus permanen dari server.</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then(result => {
        if (result.isConfirmed) deleteBackupProcess(filename);
    });
}

function deleteBackupProcess(filename) {
    fetch(`/admin/pengaturan/backup/${encodeURIComponent(filename)}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
            }
            loadBackupList();
        } else {
            alert(data.message || 'Gagal menghapus backup.');
        }
    })
    .catch(() => alert('Terjadi kesalahan jaringan.'));
}

// Initialize on page load — auto-load backup list jika tab backup aktif
document.addEventListener('DOMContentLoaded', function() {
    switchTab('profil');
    // Auto load backup list setiap kali tab backup diklik
    const tabBackup = document.getElementById('tab-backup');
    if (tabBackup) {
        tabBackup.addEventListener('click', function() {
            setTimeout(loadBackupList, 100);
        });
    }
});
</script>
@endpush
{{-- Google Drive JS ditambahkan terpisah --}}
@push('scripts')
<script>
const GDRIVE_CSRF = '{{ csrf_token() }}';

(function gdriveLoadStatus() {
    fetch('{{ route("admin.pengaturan.gdrive.status") }}', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': GDRIVE_CSRF }
    })
    .then(r => r.json())
    .then(data => {
        const badge     = document.getElementById('gdrive-status-badge');
        const setupInfo = document.getElementById('gdrive-setup-info');
        const cfgPanel  = document.getElementById('gdrive-config-panel');
        if (!badge) return;
        if (data.is_configured) {
            badge.textContent = '✅ Terkonfigurasi';
            badge.className   = 'px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700';
            if (cfgPanel) cfgPanel.classList.remove('hidden');
            const folderEl = document.getElementById('gdrive-folder-id');
            const autoEl   = document.getElementById('gdrive-auto-status');
            if (folderEl) folderEl.textContent = data.folder_id;
            if (autoEl)   autoEl.textContent   = data.auto_backup_enabled ? 'Aktif (01:00/hari)' : 'Nonaktif';
        } else {
            badge.textContent = '⚠️ Belum dikonfigurasi';
            badge.className   = 'px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700';
            if (setupInfo) setupInfo.classList.remove('hidden');
        }
    }).catch(() => {});
})();

function gdriveTestConnection() {
    const resultEl   = document.getElementById('gdrive-result');
    const progressEl = document.getElementById('gdrive-progress');
    resultEl.className = 'hidden';
    document.getElementById('gdrive-progress-text').textContent = 'Menguji koneksi...';
    progressEl.classList.remove('hidden');
    fetch('{{ route("admin.pengaturan.gdrive.test") }}', {
        method:'POST', headers:{'Accept':'application/json','X-CSRF-TOKEN':GDRIVE_CSRF}
    }).then(r=>r.json()).then(data=>{
        progressEl.classList.add('hidden'); resultEl.classList.remove('hidden');
        if (data.success) {
            resultEl.className='rounded-lg p-4 text-sm bg-green-50 border border-green-200 text-green-800';
            resultEl.innerHTML=`✅ <strong>Koneksi berhasil!</strong> Folder: <strong>${data.folder_name}</strong>`;
        } else {
            resultEl.className='rounded-lg p-4 text-sm bg-red-50 border border-red-200 text-red-800';
            resultEl.innerHTML=`❌ <strong>Gagal:</strong> ${data.message}`;
        }
    }).catch(()=>{ progressEl.classList.add('hidden'); });
}

function gdriveBackup(type) {
    const labels={arsip:'file arsip & surat',disposisi:'bukti disposisi',all:'semua file (arsip + disposisi)'};
    if (typeof Swal==='undefined') { if(confirm(`Backup ${labels[type]} ke Google Drive?`)) gdriveBackupProcess(type); return; }
    Swal.fire({
        title:'☁️ Backup ke Google Drive',
        html:`Upload <strong>${labels[type]}</strong> ke Drive akan dimulai.<br><small>Jangan tutup halaman ini.</small>`,
        icon:'question',showCancelButton:true,confirmButtonColor:'#2563eb',cancelButtonColor:'#6b7280',
        confirmButtonText:'Ya, Backup',cancelButtonText:'Batal'
    }).then(r=>{ if(r.isConfirmed) gdriveBackupProcess(type); });
}

function gdriveBackupProcess(type) {
    const urls={
        arsip:'{{ route("admin.pengaturan.gdrive.backup-arsip") }}',
        disposisi:'{{ route("admin.pengaturan.gdrive.backup-disposisi") }}',
        all:'{{ route("admin.pengaturan.gdrive.backup-all") }}'
    };
    const resultEl=document.getElementById('gdrive-result');
    const progressEl=document.getElementById('gdrive-progress');
    resultEl.className='hidden';
    document.getElementById('gdrive-progress-text').textContent='Mengupload ke Google Drive... (jangan tutup halaman ini)';
    progressEl.classList.remove('hidden');
    fetch(urls[type],{method:'POST',headers:{'Accept':'application/json','X-CSRF-TOKEN':GDRIVE_CSRF}})
    .then(r=>r.json()).then(data=>{
        progressEl.classList.add('hidden'); resultEl.classList.remove('hidden');
        if (data.success) {
            resultEl.className='rounded-lg p-4 text-sm bg-green-50 border border-green-200 text-green-800';
            let html=`✅ <strong>Backup selesai!</strong><br>${data.message}`;
            if(data.result?.arsip){const a=data.result.arsip;html+=`<br><span class="text-xs block mt-1">📁 Arsip: ${a.success} berhasil, ${a.skipped} dilewati, ${a.failed} gagal</span>`;}
            if(data.result?.disposisi){const d=data.result.disposisi;html+=`<span class="text-xs block">📎 Disposisi: ${d.success} berhasil, ${d.skipped} dilewati, ${d.failed} gagal</span>`;}
            resultEl.innerHTML=html;
            if(typeof Swal!=='undefined') Swal.fire({icon:'success',title:'✅ Selesai',text:data.message,timer:3000,showConfirmButton:false,toast:true,position:'top-end'});
        } else {
            resultEl.className='rounded-lg p-4 text-sm bg-red-50 border border-red-200 text-red-800';
            resultEl.innerHTML=`❌ <strong>Gagal:</strong> ${data.message}`;
        }
    }).catch(()=>{
        progressEl.classList.add('hidden'); resultEl.classList.remove('hidden');
        resultEl.className='rounded-lg p-4 text-sm bg-red-50 border border-red-200 text-red-800';
        resultEl.textContent='❌ Kesalahan jaringan saat upload.';
    });
}
</script>
@endpush
