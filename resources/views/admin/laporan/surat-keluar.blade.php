@extends(Auth::user()->role . '.layouts.app')
@section('title', 'Laporan Surat Keluar')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Surat Keluar</h1>
            <p class="text-gray-600 mt-1">Rekap surat keluar beserta status Tanda Tangan Elektronik (TTE).</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            @include('partials.laporan-buttons', ['type' => 'laporan-surat-keluar'])
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="menunggu_tte" {{ request('status') === 'menunggu_tte' ? 'selected' : '' }}>Menunggu TTE</option>
                    <option value="ditandatangani" {{ request('status') === 'ditandatangani' ? 'selected' : '' }}>Sudah Ditandatangani</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-700 transition">Filter</button>
            <a href="{{ route(Auth::user()->role . '.laporan.surat-keluar') }}" class="text-gray-500 px-4 py-2 rounded-lg text-sm border hover:bg-gray-50 transition">Reset</a>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-gray-500">
            <p class="text-xs font-medium text-gray-500">Total</p>
            <p class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-gray-400">
            <p class="text-xs font-medium text-gray-500">Draft</p>
            <p class="text-xl font-bold text-gray-600 mt-1">{{ $stats['draft'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-yellow-500">
            <p class="text-xs font-medium text-gray-500">Menunggu TTE</p>
            <p class="text-xl font-bold text-yellow-600 mt-1">{{ $stats['menunggu_tte'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-green-500">
            <p class="text-xs font-medium text-gray-500">Ditandatangani</p>
            <p class="text-xl font-bold text-green-600 mt-1">{{ $stats['ditandatangani'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-red-500">
            <p class="text-xs font-medium text-gray-500">Ditolak</p>
            <p class="text-xl font-bold text-red-600 mt-1">{{ $stats['ditolak'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase">
                    <tr>
                        <th class="px-6 py-4">No. Agenda</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Tujuan</th>
                        <th class="px-6 py-4">Perihal</th>
                        <th class="px-6 py-4">Dibuat Oleh</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Ditandatangani Oleh</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($letters as $letter)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-800">{{ $letter->nomor_agenda }}</td>
                        <td class="px-6 py-4">{{ $letter->tanggal_surat->format('d M Y') }}</td>
                        <td class="px-6 py-4">{{ $letter->tujuan }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ \Illuminate\Support\Str::limit($letter->perihal, 40) }}</td>
                        <td class="px-6 py-4">{{ $letter->pembuat->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @php $badge = $letter->status_badge; @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                {{ $badge['color'] === 'gray'   ? 'bg-gray-100 text-gray-700'   : '' }}
                                {{ $badge['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $badge['color'] === 'green'  ? 'bg-green-100 text-green-700'   : '' }}
                                {{ $badge['color'] === 'red'    ? 'bg-red-100 text-red-700'       : '' }}
                                {{ $badge['color'] === 'blue'   ? 'bg-blue-100 text-blue-700'     : '' }}">
                                {{ $badge['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $letter->penandatangan->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route(Auth::user()->role . '.surat-keluar.download-pdf', $letter->id) }}"
                               class="text-teal-600 hover:underline text-xs font-medium">
                                Unduh PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-6 py-12 text-center text-gray-500">Belum ada data surat keluar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($letters->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $letters->links() }}</div>
        @endif
    </div>
</div>
@endsection
