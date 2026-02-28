<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemeliharaan Aset</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
        .header { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #333; }
        .header-logos { display: table; width: 100%; margin-bottom: 15px; }
        .logo-left, .logo-right { display: table-cell; width: 15%; vertical-align: middle; text-align: center; }
        .header-text { display: table-cell; width: 70%; text-align: center; vertical-align: middle; }
        .logo-left img, .logo-right img { width: 80px; height: 80px; object-fit: contain; display: block; margin: 0 auto; }
        .header-text h1 { margin: 0; font-size: 18px; color: #333; font-weight: bold; }
        .header-text p { margin: 5px 0; font-size: 11px; color: #666; }
        .info { margin-bottom: 20px; }
        .info table { width: 100%; }
        .info td { padding: 3px 0; font-size: 11px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.data th { background-color: #f3f4f6; padding: 8px; text-align: left; border: 1px solid #ddd; font-size: 10px; font-weight: bold; }
        table.data td { padding: 6px 8px; border: 1px solid #ddd; font-size: 9px; vertical-align: top; }
        .text-red { color: #dc2626; font-weight: bold; }
        .text-yellow { color: #d97706; font-weight: bold; }
        .signature-section { margin-top: 50px; text-align: right; }
        .signature-box { display: inline-block; text-align: center; min-width: 200px; }
        .signature-box p { margin: 5px 0; font-size: 11px; }
        .signature-space { height: 60px; margin: 10px 0; }
        .signature-line { border-top: 1px solid #333; padding-top: 5px; font-weight: bold; font-size: 11px; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #666; padding: 10px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-logos">
            <div class="logo-left"><img src="{{ public_path('images/logo-selidah.png') }}"></div>
            <div class="header-text">
                <h1>LAPORAN PEMELIHARAAN & KERUSAKAN ASET</h1>
                <p><strong>GANDARIA</strong></p>
                <p>Sistem pengelolaan arsip dan data aset terpadu, terstruktur, informatif, dan akuntabel</p>
            </div>
            <div class="logo-right"><img src="{{ public_path('images/gandaria.png') }}"></div>
        </div>
    </div>

    <div class="info">
        <table>
            <tr><td width="20%"><strong>Total Aset Bermasalah:</strong></td><td>{{ count($assets) }} Barang</td></tr>
            <tr><td><strong>Dicetak oleh:</strong></td><td>{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</td></tr>
            <tr><td><strong>Tanggal Cetak:</strong></td><td>{{ now()->format('d F Y H:i:s') }}</td></tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%" style="text-align: center">No</th>
                <th width="15%">Kode Aset</th>
                <th width="30%">Nama Aset</th>
                <th width="20%">Lokasi / Unit</th>
                <th width="15%">Kondisi</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $index => $asset)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td><strong>{{ $asset->kode_asset }}</strong></td>
                <td>{{ $asset->nama }}<br><span style="color: #888;">PJ: {{ $asset->penanggung_jawab ?? '-' }}</span></td>
                <td>{{ $asset->lokasi ?? '-' }}<br><span style="color: #888;">{{ $asset->unit ?? '-' }}</span></td>
                <td class="{{ $asset->kondisi == 'rusak' ? 'text-red' : 'text-yellow' }}">{{ strtoupper($asset->kondisi) }}</td>
                <td class="{{ $asset->status == 'rusak' ? 'text-red' : 'text-yellow' }}">
                    {{ $asset->status == 'maintenance' ? 'PEMELIHARAAN' : strtoupper($asset->status) }}
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align: center; padding: 20px;">Semua aset dalam keadaan baik. Tidak ada data pemeliharaan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Marabahan, {{ now()->format('d F Y') }}</p><p>Mengetahui,</p>
            <div class="signature-space"></div>
            <div class="signature-line">Azwar Arsyadi, S.Kom</div>
        </div>
    </div>
    <div class="footer"><p>GANDARIA - Sistem Arsip Dan Aset Digital</p></div>
</body>
</html>