@extends('admin.layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
{{-- Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Pengaturan Sistem</h1>
    <p class="text-gray-600 mt-1">Kelola pengaturan aplikasi dan profil Anda</p>
</div>

{{-- Alert Messages --}}
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

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
            
            <form action="{{ route('admin.pengaturan.update-profil') }}" method="POST" class="space-y-4">
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
                    <button type="submit" 
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
            
            <form action="{{ route('admin.pengaturan.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Aplikasi</label>
                        <input type="text" 
                               name="app_name" 
                               id="app_name" 
                               value="{{ old('app_name', $settings['app_name'] ?? 'SIPPB') }}"
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
                            onclick="clearCache()"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Bersihkan Cache
                    </button>
                    
                    <button type="submit" 
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
            
            <form action="{{ route('admin.pengaturan.update-appearance') }}" method="POST" class="space-y-6">
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
                            {{-- Extra Small --}}
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

                            {{-- Small --}}
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

                            {{-- Medium (Default) --}}
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

                            {{-- Large --}}
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

                            {{-- Extra Large --}}
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
                        <p class="text-sm text-gray-500 mt-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pilih ukuran teks yang nyaman untuk dibaca
                        </p>
                    </div>

                    {{-- Display Toggles --}}
                    <div class="space-y-3 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <label for="show_breadcrumbs" class="font-medium text-gray-700">Tampilkan Breadcrumbs</label>
                                <p class="text-sm text-gray-500">Menampilkan navigasi breadcrumb di setiap halaman</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="show_breadcrumbs" id="show_breadcrumbs" value="1"
                                       {{ ($settings['show_breadcrumbs'] ?? 1) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

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
                            onclick="resetAppearance()"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-undo mr-2"></i>
                        Reset ke Default
                    </button>
                    
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        {{-- Tab Backup --}}
        <div id="content-backup" class="tab-content hidden">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Backup Database</h2>
            
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <h3 class="font-medium text-blue-800 mb-1">Informasi Backup</h3>
                        <p class="text-sm text-blue-700">
                            Backup database akan disimpan di folder <code class="bg-blue-100 px-2 py-1 rounded">storage/app/backups</code>.
                            Pastikan folder memiliki permission yang sesuai.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex gap-3">
                    <button type="button" 
                            onclick="createBackup()"
                            class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Buat Backup Sekarang
                    </button>
                    
                    <button type="button" 
                            onclick="loadBackupList()"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Daftar Backup
                    </button>
                </div>
                
                <div id="backup-list" class="mt-6 hidden">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">Daftar Backup</h3>
                    <div id="backup-list-content" class="bg-gray-50 rounded-md p-4">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                            <p>Memuat daftar backup...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
// Tab Switching Function
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    const selectedContent = document.getElementById(`content-${tabName}`);
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
    
    // Add active class to selected tab
    const selectedTab = document.getElementById(`tab-${tabName}`);
    if (selectedTab) {
        selectedTab.classList.add('active', 'border-blue-500', 'text-blue-600');
        selectedTab.classList.remove('border-transparent', 'text-gray-500');
    }
}

// Clear Cache Function
function clearCache() {
    if (confirm('Bersihkan semua cache sistem?\n\nIni akan membersihkan:\n- Cache konfigurasi\n- Cache view\n- Cache route\n- Cache aplikasi')) {
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Membersihkan...';
        
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
                alert('✅ ' + data.message);
            } else {
                alert('❌ ' + (data.message || 'Gagal membersihkan cache'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat membersihkan cache');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        });
    }
}

// Create Backup Function
function createBackup() {
    if (confirm('Buat backup database sekarang?\n\nProses ini mungkin memakan waktu beberapa saat tergantung ukuran database.')) {
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Membuat Backup...';
        
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
                alert('✅ ' + data.message + '\n\nFile: ' + data.filename);
                const backupList = document.getElementById('backup-list');
                if (backupList && !backupList.classList.contains('hidden')) {
                    loadBackupList();
                }
            } else {
                alert('❌ ' + (data.message || 'Gagal membuat backup'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat membuat backup');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        });
    }
}

// Load Backup List Function
function loadBackupList() {
    const backupListDiv = document.getElementById('backup-list');
    const backupListContent = document.getElementById('backup-list-content');
    
    backupListDiv.classList.remove('hidden');
    backupListContent.innerHTML = `
        <div class="text-center text-gray-500">
            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
            <p>Memuat daftar backup...</p>
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
            let html = '<div class="space-y-2">';
            data.backups.forEach(backup => {
                html += `
                    <div class="flex items-center justify-between p-3 bg-white rounded border border-gray-200 hover:border-blue-300 transition">
                        <div class="flex items-center">
                            <i class="fas fa-file-archive text-blue-500 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-800">${backup.name}</p>
                                <p class="text-sm text-gray-500">${backup.size} - ${backup.date}</p>
                            </div>
                        </div>
                        <a href="/admin/pengaturan/backup/download/${backup.name}" 
                           class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                `;
            });
            html += '</div>';
            backupListContent.innerHTML = html;
        } else {
            backupListContent.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-folder-open text-4xl mb-3"></i>
                    <p class="text-lg font-medium">Belum ada backup</p>
                    <p class="text-sm">Buat backup pertama Anda sekarang</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        backupListContent.innerHTML = `
            <div class="text-center text-red-500 py-8">
                <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                <p>Gagal memuat daftar backup</p>
            </div>
        `;
    });
}

// Reset Appearance Function
function resetAppearance() {
    if (confirm('Reset semua pengaturan tampilan ke default?\n\nTindakan ini akan mengembalikan:\n- Tema ke Light\n- Warna aksen ke Biru\n- Ukuran teks ke Sedang\n- Semua opsi tampilan ke nilai awal')) {
        // Reset theme
        document.getElementById('theme-light').checked = true;
        
        // Reset color
        document.getElementById('color-blue').checked = true;
        
        // Reset text size
        document.getElementById('text-md').checked = true;
        
        // Reset checkboxes
        document.getElementById('show_breadcrumbs').checked = true;
        document.getElementById('compact_mode').checked = false;
        document.getElementById('smooth_scrolling').checked = true;
        
        alert('Pengaturan telah direset! Klik "Simpan" untuk menerapkan.');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Show first tab by default
    switchTab('profil');
    
    // Auto-hide success/error messages after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('[role="alert"]').forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endpush