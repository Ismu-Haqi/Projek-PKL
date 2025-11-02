<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Disposisi Surat</title>
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
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-pending { background-color: #fef3c7; color: #92400e; }
        .badge-progress { background-color: #dbeafe; color: #1e40af; }
        .badge-completed { background-color: #d1fae5; color: #065f46; }
        .badge-urgent { background-color: #fee2e2; color: #991b1b; }
        .badge-high { background-color: #fed7aa; color: #9a3412; }
        .badge-normal { background-color: #dbeafe; color: #1e40af; }
        .badge-low { background-color: #f3f4f6; color: #374151; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DISPOSISI SURAT</h1>
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
                <td>: {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'Semua' }} 
                    s/d {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'Sekarang' }}</td>
            </tr>
            <tr>
                <td class="font-bold">Total Disposisi</td>
                <td>: {{ $dispositions->count() }} Dokumen</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No. Disposisi</th>
                <th width="20%">Subjek</th>
                <th width="12%">Dari</th>
                <th width="12%">Kepada</th>
                <th width="10%">Prioritas</th>
                <th width="10%">Status</th>
                <th width="8%">Deadline</th>
                <th width="8%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dispositions as $index => $disposition)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $disposition->nomor_disposisi }}</td>
                <td>{{ $disposition->subject }}</td>
                <td>{{ $disposition->fromUser->name ?? '-' }}</td>
                <td>{{ $disposition->toUser->name ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $disposition->priority }}">
                        {{ strtoupper($disposition->priority) }}
                    </span>
                </td>
                <td class="text-center">
                    <span class="badge badge-{{ $disposition->status }}">
                        {{ strtoupper(str_replace('_', ' ', $disposition->status)) }}
                    </span>
                </td>
                <td class="text-center">
                    {{ $disposition->deadline ? $disposition->deadline->format('d/m/Y') : '-' }}
                </td>
                <td class="text-center">
                    {{ $disposition->created_at->format('d/m/Y') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data</td>
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