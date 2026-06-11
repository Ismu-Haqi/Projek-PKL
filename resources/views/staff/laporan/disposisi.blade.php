@extends('staff.layouts.app')
@section('title', 'Laporan Disposisi Surat')
@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">📋 Laporan Disposisi Surat</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar disposisi surat dalam sistem</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            @include('partials.laporan-buttons', ['type' => 'disposisi'])
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $total      = $dispositions->total();
            $pending    = $dispositions->getCollection()->where('status', 'pending')->count();
            $inProgress = $dispositions->getCollection()->where('status', 'in_progress')->count();
            $completed  = $dispositions->getCollection()->where('status', 'completed')->count();
        @endphp
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <p class="text-xs text-blue-600 font-medium">Total</p>
            <p class="text-2xl font-bold text-blue-700">{{ $total }}</p>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <p class="text-xs text-yellow-600 font-medium">Pending</p>
            <p class="text-2xl font-bold text-yellow-700">{{ $pending }}</p>
        </div>
        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
            <p class="text-xs text-indigo-600 font-medium">Diproses</p>
            <p class="text-2xl font-bold text-indigo-700">{{ $inProgress }}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <p class="text-xs text-green-600 font-medium">Selesai</p>
            <p class="text-2xl font-bold text-green-700">{{ $completed }}</p>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">No</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">No. Disposisi</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Subjek</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Dari</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Kepada</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Prioritas</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Deadline</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dispositions as $index => $disposition)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-600">{{ $dispositions->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-800 text-xs font-mono">{{ $disposition->nomor_disposisi }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ Str::limit($disposition->subject, 40) }}</p>
                            <p class="text-xs text-gray-400">{{ $disposition->archive->nomor_surat ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $disposition->fromUser->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $disposition->toUser->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $pc = ['urgent'=>'bg-red-100 text-red-700','high'=>'bg-orange-100 text-orange-700','normal'=>'bg-blue-100 text-blue-700','low'=>'bg-gray-100 text-gray-700'];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $pc[$disposition->priority] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $disposition->priorityLabel['text'] ?? ucfirst($disposition->priority) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $sc = ['pending'=>'bg-yellow-100 text-yellow-700','in_progress'=>'bg-blue-100 text-blue-700','completed'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700'];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $sc[$disposition->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $disposition->statusLabel['text'] ?? ucfirst($disposition->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            @if($disposition->deadline)
                                <span class="{{ $disposition->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    {{ $disposition->deadline->format('d M Y') }}
                                </span>
                                @if($disposition->isOverdue())
                                    <p class="text-xs text-red-500">⚠ Terlambat</p>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="font-medium">Tidak ada data disposisi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($dispositions->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $dispositions->links() }}</div>
        @endif
    </div>

</div>
@endsection
