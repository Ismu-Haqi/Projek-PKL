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
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #333;
        }
        .header-logos {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .logo-left, .logo-right {
            display: table-cell;
            width: 15%;
            vertical-align: middle;
        }
        .header-text {
            display: table-cell;
            width: 70%;
            text-align: center;
            vertical-align: middle;
        }
        .logo-left img, .logo-right img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .header-text h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }
        .header-text p {
            margin: 5px 0;
            font-size: 11px;
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
            font-size: 11px;
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
        .badge-pending { background-color: #fef3c7; color: #92400e; }
        .badge-progress { background-color: #dbeafe; color: #1e40af; }
        .badge-completed { background-color: #d1fae5; color: #065f46; }
        .badge-urgent { background-color: #fee2e2; color: #991b1b; }
        .badge-high { background-color: #fed7aa; color: #9a3412; }
        .badge-normal { background-color: #dbeafe; color: #1e40af; }
        .badge-low { background-color: #f3f4f6; color: #374151; }
        .signature-section {
            margin-top: 50px;
            text-align: right;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }
        .signature-box p {
            margin: 5px 0;
            font-size: 11px;
        }
        .signature-space {
            height: 60px;
            margin: 10px 0;
        }
        .signature-line {
            border-top: 1px solid #333;
            padding-top: 5px;
            font-weight: bold;
            font-size: 11px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-logos">
            <div class="logo-left">
                <img src="{{ public_path('images/logo-selidah.png') }}" alt="Logo Selidah">
            </div>
            <div class="header-text">
                <h1>LAPORAN DISPOSISI SURAT</h1>
                <p><strong>GANDARIA</strong></p>
                <p>Sistem pengelolaan arsip dan data aset terpadu, terstruktur, informatif, dan akuntabel</p>
                <p>Dinas Komunikasi dan Informatika Kab. Barito Kuala</p>
            </div>
            <div class="logo-right">
                <img src="{{ public_path('images/gandaria.png') }}" alt="Logo Gandaria">
            </div>
        </div>
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
                        @php
                            $status_en_formatted = strtoupper(str_replace('_', ' ', $disposition->status));
                            $status_id = str_replace(
                                ['PENDING', 'IN PROGRESS', 'COMPLETED', 'REJECTED'],
                                ['MENUNGGU', 'DIPROSES', 'SELESAI', 'DITOLAK'],
                                $status_en_formatted
                            );
                        @endphp
                        {{ $status_id }}
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

    <div class="signature-section">
        <div class="signature-box">
            <p>Marabahan, {{ now()->format('d F Y') }}</p>
            <p>Mengetahui,</p>
            <div class="signature-space"></div>
            <div class="signature-line">
                Azwar Arsyadi, S.Kom
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        <p>GANDARIA - Sistem Arsip Digital | Halaman 1 dari 1</p>
    </div>
</body>
</html>