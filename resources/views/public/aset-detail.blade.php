<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Aset - {{ $asset->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8 px-4">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $asset->nama }}</h1>
                    <p class="text-gray-600 font-mono">{{ $asset->kode_asset }}</p>
                </div>
            </div>

            <!-- Foto -->
            @if($asset->foto)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <img src="{{ asset('storage/' . $asset->foto) }}" alt="{{ $asset->nama }}" class="w-full rounded-lg">
            </div>
            @endif

            <!-- Status & Kondisi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Status & Kondisi</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Status</p>
                        @php
                            $statusConfig = [
                                'tersedia' => 'bg-green-100 text-green-700',
                                'digunakan' => 'bg-blue-100 text-blue-700',
                                'diperbaiki' => 'bg-yellow-100 text-yellow-700',
                                'rusak' => 'bg-red-100 text-red-700'
                            ];
                        @endphp
                        <span class="inline-flex px-4 py-2 rounded-lg text-sm font-bold {{ $statusConfig[$asset->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($asset->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Kondisi</p>
                        @php
                            $kondisiConfig = [
                                'baik' => 'bg-green-100 text-green-700',
                                'cukup' => 'bg-blue-100 text-blue-700',
                                'kurang' => 'bg-yellow-100 text-yellow-700',
                                'rusak' => 'bg-red-100 text-red-700'
                            ];
                        @endphp
                        <span class="inline-flex px-4 py-2 rounded-lg text-sm font-bold {{ $kondisiConfig[$asset->kondisi] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($asset->kondisi) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informasi Dasar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Dasar</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Kategori</p>
                        <p class="font-semibold text-gray-800">{{ $asset->kategori }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Merk</p>
                        <p class="font-semibold text-gray-800">{{ $asset->merk ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tipe/Model</p>
                        <p class="font-semibold text-gray-800">{{ $asset->tipe ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Serial Number</p>
                        <p class="font-semibold text-gray-800 font-mono text-sm">{{ $asset->serial_number ?? '-' }}</p>
                    </div>
                    @if($asset->spesifikasi)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Spesifikasi</p>
                        <p class="font-medium text-gray-800">{{ $asset->spesifikasi }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Lokasi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Lokasi & Penempatan</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Lokasi</p>
                        <p class="font-semibold text-gray-800">{{ $asset->lokasi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Unit Kerja</p>
                        <p class="font-semibold text-gray-800">{{ $asset->unit ?? '-' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>