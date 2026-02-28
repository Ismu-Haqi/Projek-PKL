<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Valuasi dan Penyusutan Aset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
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
            text-align: center;
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
            display: block;
            margin: 0 auto;
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
            vertical-align: top;
        }
        .text-red { color: #991b1b; }
        .text-green { color: #065f46; font-weight: bold;}
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
                <h1>LAPORAN PENYUSUTAN ASET</h1>
                <p><strong>GANDARIA (Metode Garis Lurus)</strong></p>
                <p>Sistem pengelolaan arsip dan data aset terpadu, terstruktur, informatif, dan akuntabel</p>
                <p>Dinas Komunikasi dan Informatika Kab. Barito Kuala</p>
            </div>
            <div class="logo-right">
                <img src="{{ public_path('images/gandaria.png') }}" alt="Logo Gandaria">
            </div>
        </div>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="30%"><strong>Total Harga Perolehan (Beli):</strong></td>
                <td>Rp {{ number_format($totalAsetAwal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Valuasi Saat Ini (Nilai Buku):</strong></td>
                <td><strong class="text-green">Rp {{ number_format($totalNilaiBuku, 0, ',', '.') }}</strong></td>
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
                <th width="5%" style="text-align: center">No</th>
                <th width="20%">Kode & Nama Aset</th>
                <th width="10%">Tgl Beli</th>
                <th width="15%">Harga Beli (Rp)</th>
                <th width="15%">Umur & Residu</th>
                <th width="15%">Penyusutan/Thn</th>
                <th width="15%">Nilai Saat Ini</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $index => $asset)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $asset->kode_asset }}</strong><br>
                    <span style="color: #666;">{{ $asset->nama }}</span><br>
                    <strong style="color: #1e40af;">Status: {{ $asset->status_kelayakan }}</strong>
                </td>
                <td>{{ \Carbon\Carbon::parse($asset->tanggal_pembelian)->format('d/m/Y') }}</td>
                <td>{{ number_format($asset->harga_pembelian, 0, ',', '.') }}</td>
                <td>
                    {{ $asset->umur_ekonomis }} Tahun<br>
                    <span style="color: #666;">Residu: {{ number_format($asset->nilai_residu, 0, ',', '.') }}</span>
                </td>
                <td class="text-red">-{{ number_format($asset->penyusutan_per_tahun, 0, ',', '.') }}</td>
                <td class="text-green">{{ number_format($asset->nilai_buku, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #999;">
                    Tidak ada data aset yang memiliki harga pembelian untuk dihitung.
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