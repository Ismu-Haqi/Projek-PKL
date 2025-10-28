@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Laporan Disposisi Surat')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center">
            <a href="{{ route(Auth::user()->role . '.laporan.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Laporan Disposisi Surat</h1>
                <p class="text-gray-600 mt-1">Tracking disposisi, status, prioritas, dan overdue</p>
            </div>
        </div>
        
    
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route(Auth::user()->role . '.laporan.disposisi') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Diproses</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Prioritas</label>
                <select name="priority" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Prioritas</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Sangat Mendesak</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Mendesak</option>
                    <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Biasa</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Tidak Mendesak</option>
                </select>
            </div>
            
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg font-medium">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Tombol Untuk Print dan Download -->
    <div class="flex gap-2 mb-4">
    <a href="{{ route(auth()->user()->role . '.laporan.print-pdf', ['type' => 'disposisi'] + request()->query()) }}" 
       target="_blank"
       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print PDF
    </a>
    
    <a href="{{ route(auth()->user()->role . '.laporan.export-pdf', ['type' => 'disposisi'] + request()->query()) }}" 
       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Download PDF
    </a>
</div>
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @php
            $total = $dispositions->total();
            $pending = $dispositions->where('status', 'pending')->count();
            $inProgress = $dispositions->where('status', 'in_progress')->count();
            $completed = $dispositions->where('status', 'completed')->count();
        @endphp

        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
            <div class="text-sm text-blue-600 font-medium mb-1">Total Disposisi</div>
            <div class="text-3xl font-bold text-blue-700">{{ $total }}</div>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl border border-yellow-200 p-6">
            <div class="text-sm text-yellow-600 font-medium mb-1">Pending</div>
            <div class="text-3xl font-bold text-yellow-700">{{ $pending }}</div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl border border-blue-200 p-6">
            <div class="text-sm text-blue-600 font-medium mb-1">Diproses</div>
            <div class="text-3xl font-bold text-blue-700">{{ $inProgress }}</div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-xl border border-green-200 p-6">
            <div class="text-sm text-green-600 font-medium mb-1">Selesai</div>
            <div class="text-3xl font-bold text-green-700">{{ $completed }}</div>
        </div>
    </div>

    <!-- Dispositions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Nomor Disposisi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Subjek</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Pengirim</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Penerima</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Prioritas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Deadline</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($dispositions as $index => $disposition)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $dispositions->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">{{ $disposition->nomor_disposisi }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ Str::limit($disposition->subject, 40) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $disposition->archive->nomor_surat ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $disposition->fromUser->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $disposition->toUser->name }}</td>
                        <td class="px-6 py-4">
                            @php
                                $priorityColors = [
                                    'urgent' => 'bg-red-100 text-red-700',
                                    'high' => 'bg-orange-100 text-orange-700',
                                    'normal' => 'bg-blue-100 text-blue-700',
                                    'low' => 'bg-gray-100 text-gray-700'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $priorityColors[$disposition->priority] }}">
                                {{ $disposition->priorityLabel['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$disposition->status] }}">
                                {{ $disposition->statusLabel['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($disposition->deadline)
                                <span class="{{ $disposition->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-700' }}">
                                    {{ $disposition->deadline->format('d M Y') }}
                                </span>
                                @if($disposition->isOverdue())
                                    <p class="text-xs text-red-600 mt-1">⚠ Terlambat</p>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">Tidak ada data disposisi</p>
                                <p class="text-gray-400 text-sm mt-1">Coba ubah filter pencarian</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($dispositions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $dispositions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection