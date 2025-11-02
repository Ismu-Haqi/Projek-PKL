<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Statistik Periode</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-box {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 3px 5px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .stat-box h3 {
            margin: 0 0 10px 0;
            font-size: 12px;
            color: #666;
        }
        .stat-box .number {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        .stat-box .label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table thead {
            background-color: #4a5568;
            color: white;
        }
        table thead th {
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        table tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .comparison {
            font-size: 10px;
            color: #666;
        }
        .trend-up { color: #059669; }
        .trend-down { color: #dc2626; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN STATISTIK PERIODE</h1>
        <p>GANDARIA - Sistem pengelolaan arsip dan data aset terpadu, terstruktur, informatif, dan akuntabel</p>
        <p>Dinas Komunikasi dan Informatika Kab.Barito Kuala</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td width="150" class="font-bold">Tanggal Cetak</td>
                <td>: {{ now()->format('d F Y H:i:s') }}</td>
            </tr>
            <tr>
                <td class="font-bold">Periode Laporan</td>
                <td>: {{ isset($period_label) ? $period_label : $start_date->format('d M Y') . ' s/d ' . $end_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="font-bold">Tipe Periode</td>
                <td>: {{ isset($period_type) ? ucfirst($period_type) : 'Custom' }}</td>
            </tr>
        </table>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <h3>Total Arsip</h3>
            <div class="number">{{ count($archives) }}</div>
            @if(isset($comparison))
            <div class="comparison {{ $comparison['percentage'] >= 0 ? 'trend-up' : 'trend-down' }}">
                {{ $comparison['percentage'] >= 0 ? '↑' : '↓' }} {{ abs($comparison['percentage']) }}% vs periode lalu
            </div>
            @endif
        </div>
        <div class="stat-box">
            <h3>Total Disposisi</h3>
            <div class="number">{{ count($dispositions) }}</div>
        </div>
        <div class="stat-box">
            <h3>Pending</h3>
            <div class="number">{{ collect($dispositions)->where('status', 'pending')->count() }}</div>
        </div>
        <div class="stat-box">
            <h3>Selesai</h3>
            <div class="number">{{ collect($dispositions)->where('status', 'completed')->count() }}</div>
        </div>
    </div>

    <h3 style="margin-top: 20px; margin-bottom: 10px;">Detail Arsip per Kategori</h3>
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="60%">Kategori</th>
                <th width="15%">Total</th>
                <th width="15%">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php
                $categoryStats = collect($archives)->groupBy('category_id')->map(function($items, $categoryId) use ($archives) {
                    $category = $items->first()->category ?? null;
                    return [
                        'category' => $category ? $category->name : 'Tidak ada kategori',
                        'total' => $items->count(),
                        'percentage' => count($archives) > 0 ? round(($items->count() / count($archives)) * 100, 1) : 0
                    ];
                })->sortByDesc('total')->values();
                $counter = 1;
            @endphp
            @forelse($categoryStats as $stat)
            <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td>{{ $stat['category'] }}</td>
                <td class="text-center">{{ $stat['total'] }}</td>
                <td class="text-center">{{ $stat['percentage'] }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3 style="margin-top: 20px; margin-bottom: 10px;">Detail Disposisi per Status</h3>
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="60%">Status</th>
                <th width="15%">Total</th>
                <th width="15%">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusStats = collect($dispositions)->groupBy('status')->map(function($items, $status) use ($dispositions) {
                    return [
                        'status' => ucfirst(str_replace('_', ' ', $status)),
                        'total' => $items->count(),
                        'percentage' => count($dispositions) > 0 ? round(($items->count() / count($dispositions)) * 100, 1) : 0
                    ];
                })->sortByDesc('total')->values();
                $counter = 1;
            @endphp
            @forelse($statusStats as $stat)
            <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td>{{ $stat['status'] }}</td>
                <td class="text-center">{{ $stat['total'] }}</td>
                <td class="text-center">{{ $stat['percentage'] }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        <p>GANDARIA - Sistem Arsip Digital</p>
    </div>
</body>
</html>