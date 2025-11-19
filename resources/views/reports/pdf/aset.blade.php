<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Aset</title>
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
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 3px 0;
            font-size: 11px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table.data th {
            background-color: #f3f4f6;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 10px;
            font-weight: bold;
        }
        table.data td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-red { background: #fee2e2; color: #991b1b; }
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
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-logos">
            <div class="logo-left">
                <img src="{{ public_path('images/logo-selidah.png') }}" alt="Logo Selidah">
            </div>
            <div class="header-text">
                <h1>LAPORAN ASET</h1>
                <p><strong>GANDARIA</strong></p>
                <p>Sistem pengelolaan arsip dan data aset terpadu, terstruktur, informatif, dan akuntabel</p>
                <p>Dinas Komunikasi dan Informatika Kab. Barito Kuala</p>
                <p>Periode: {{ $period ?? 'Semua Periode' }}</p>
            </div>
            <div class="logo-right">
                <img src="{{ public_path('images/gandaria.png') }}" alt="Logo Gandaria">
            </div>
        </div>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="30%"><strong>Total Aset:</strong></td>
                <td>{{ count($assets) }} aset</td>
            </tr>
            <tr>
                <td><strong>Dicetak oleh:</strong></td>
                <td>{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</td>
            </tr>
            <tr>
                <td><strong>Tanggal Cetak:</strong></td>
                <td>{{ now()->format('d F Y H:i:s') }}</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Kode Aset</th>
                <th width="25%">Nama Aset</th>
                <th width="15%">Kategori</th>
                <th width="12%">Unit</th>
                <th width="10%">Kondisi</th>
                <th width="10%">Status</th>
                <th width="11%">Tgl Input</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $index => $asset)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>{{ $asset->kode_asset }}</td>
                <td>
                    <strong>{{ $asset->nama }}</strong><br>
                    @if($asset->merk)
                    <small style="color: #666;">{{ $asset->merk }}</small>
                    @endif
                </td>
                <td>{{ $asset->kategori }}</td>
                <td>{{ $asset->unit ?? '-' }}</td>
                <td>
                    @php
                        $kondisiClass = [
                            'baik' => 'badge-green',
                            'cukup' => 'badge-blue',
                            'kurang' => 'badge-yellow',
                            'rusak' => 'badge-red'
                        ][$asset->kondisi] ?? 'badge-blue';
                    @endphp
                    <span class="badge {{ $kondisiClass }}">
                        {{ ucfirst($asset->kondisi) }}
                    </span>
                </td>
                <td>
                    @php
                        $statusClass = [
                            'tersedia' => 'badge-green',
                            'digunakan' => 'badge-blue',
                            'dipinjam' => 'badge-yellow',
                            'rusak' => 'badge-red'
                        ][$asset->status] ?? 'badge-blue';
                    @endphp
                    <span class="badge {{ $statusClass }}">
                        {{ ucfirst($asset->status) }}
                    </span>
                </td>
                <td>{{ $asset->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                    Tidak ada data aset
                </td>
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
        <p>GANDARIA - Sistem Arsip Dan Aset Digital | Halaman 1 dari 1</p>
    </div>
</body>
</html>