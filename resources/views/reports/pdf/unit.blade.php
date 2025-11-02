<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produktivitas Unit Kerja</title>
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
        .top-units {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .top-unit {
            display: table-cell;
            width: 33.33%;
            padding: 15px;
            text-align: center;
            border: 2px solid #ddd;
            background-color: #fef3c7;
        }
        .top-unit.second {
            background-color: #f3f4f6;
        }
        .top-unit.third {
            background-color: #fed7aa;
        }
        .top-unit .rank {
            font-size: 24px;
            font-weight: bold;
            color: #666;
        }
        .top-unit .unit-name {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
        }
        .top-unit .stats {
            font-size: 11px;
            color: #666;
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
            font-size: 10px;
        }
        table tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-excellent { background-color: #d1fae5; color: #065f46; }
        .badge-good { background-color: #dbeafe; color: #1e40af; }
        .badge-fair { background-color: #fef3c7; color: #92400e; }
        .badge-poor { background-color: #fee2e2; color: #991b1b; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PRODUKTIVITAS UNIT KERJA</h1>
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
                <td>: {{ $start_date->format('d M Y') }} s/d {{ $end_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="font-bold">Total Unit Kerja</td>
                <td>: {{ count($units) }} Unit</td>
            </tr>
        </table>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <h3>Rata-rata Arsip/Unit</h3>
            <div class="number">{{ number_format(collect($units)->avg('total_archives'), 1) }}</div>
        </div>
        <div class="stat-box">
            <h3>Rata-rata Disposisi/Unit</h3>
            <div class="number">{{ number_format(collect($units)->avg('total_dispositions'), 1) }}</div>
        </div>
        <div class="stat-box">
            <h3>Avg Completion Rate</h3>
            <div class="number">{{ number_format(collect($units)->avg('completion_rate'), 1) }}%</div>
        </div>
        <div class="stat-box">
            <h3>Unit Terbaik</h3>
            <div class="number" style="font-size: 14px;">{{ collect($units)->first()['unit'] ?? '-' }}</div>
        </div>
    </div>

    @if(count($units) >= 3)
    <h3 style="margin-top: 20px; margin-bottom: 10px;">Top 3 Unit Terbaik</h3>
    <div class="top-units">
        @foreach(array_slice($units, 0, 3) as $index => $unit)
        <div class="top-unit {{ $index == 1 ? 'second' : ($index == 2 ? 'third' : '') }}">
            <div class="rank">#{{ $index + 1 }}</div>
            <div class="unit-name">{{ $unit['unit'] }}</div>
            <div class="stats">
                Arsip: {{ $unit['total_archives'] }} | 
                Disposisi: {{ $unit['total_dispositions'] }} | 
                Rate: {{ $unit['completion_rate'] }}%
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <h3 style="margin-top: 20px; margin-bottom: 10px;">Detail Produktivitas per Unit</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">Rank</th>
                <th width="25%">Unit Kerja</th>
                <th width="12%">Arsip</th>
                <th width="12%">Disposisi</th>
                <th width="12%">Selesai</th>
                <th width="14%">Completion Rate</th>
                <th width="20%">Performance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($units as $index => $unit)
            <tr>
                <td class="text-center font-bold">{{ $index + 1 }}</td>
                <td>{{ $unit['unit'] }}</td>
                <td class="text-center">{{ $unit['total_archives'] }}</td>
                <td class="text-center">{{ $unit['total_dispositions'] }}</td>
                <td class="text-center">{{ $unit['completed_dispositions'] }}</td>
                <td class="text-center">{{ $unit['completion_rate'] }}%</td>
                <td class="text-center">
                    @if($unit['completion_rate'] >= 80)
                        <span class="badge badge-excellent">Baik Sekali</span>
                    @elseif($unit['completion_rate'] >= 60)
                        <span class="badge badge-good">Baik</span>
                    @elseif($unit['completion_rate'] >= 40)
                        <span class="badge badge-fair">Lumayan</span>
                    @else
                        <span class="badge badge-poor">Tingkatkan Lagi</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
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