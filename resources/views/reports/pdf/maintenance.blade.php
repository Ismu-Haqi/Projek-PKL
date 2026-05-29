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
        /* ── TTE & Signature ────────────────────────── */
        .signature-section { margin-top: 30px; page-break-inside: avoid; }
        .signature-table   { width: 100%; border-collapse: collapse; }
        .sig-left          { width: 55%; vertical-align: bottom; }
        .sig-right         { width: 45%; vertical-align: top; text-align: center; }
        .tte-box           { display: inline-block; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 10px; background: #f9fafb; text-align: center; width: 155px; }
        .tte-box img       { width: 105px; height: 105px; display: block; margin: 0 auto 4px auto; }
        .tte-label-bold    { font-size: 8px; font-weight: bold; color: #374151; margin: 2px 0; }
        .tte-label         { font-size: 7px; color: #6b7280; margin: 1px 0; line-height: 1.3; }
        .tte-url           { font-size: 6px; color: #9ca3af; word-break: break-all; margin-top: 2px; }
        .ttd-area          { text-align: center; margin-top: 6px; }
        .ttd-area p        { margin: 3px 0; font-size: 11px; }
        .ttd-space         { height: 50px; }
        .signer-name       { font-weight: bold; font-size: 11px; margin: 3px 0 1px 0; }
        .signer-title      { font-size: 10px; color: #555; margin: 0; }
        .footer            { text-align: center; font-size: 9px; color: #666; border-top: 1px solid #ddd; padding-top: 6px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-logos">
            <div class="logo-left"><img src="{{ public_path('images/logo-selidah.png') }}"></div>
            <div class="header-text">
                <h1>LAPORAN PEMELIHARAAN & KERUSAKAN ASET</h1>
                <p><strong>GANDARIA</strong></p>
                <p>Pengelolaan arsip dan data aset terstruktur, informatif, dan akuntabel</p>
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
        <div class="signature-wrapper">
            <p>Marabahan, {{ now()->format('d F Y') }}</p>
            <p>Mengetahui,</p>

            {{-- QR Code di atas nama --}}
            @if(isset($qrSvg) && $qrSvg)
            <div class="qr-block">
                <img src="{{ $qrSvg }}" alt="QR TTE">
                <p class="qr-label">Tanda Tangan Elektronik</p>
                <p class="qr-url">{{ $validasiUrl ?? '' }}</p>
            </div>
            @else
            <div style="height: 70px;"></div>
            @endif

            {{-- Nama tanpa garis --}}
            <div class="signer-space"></div>
            <p class="signer-name">{{ isset($signature) ? $signature->signed_by : 'Aris Saputera, S.STP.,MSi.' }}</p>
            <p class="signer-title">{{ isset($signature) && $signature->signed_by_title ? $signature->signed_by_title : 'Kepala Dinas' }}</p>
        </div>
    </div>
    <div class="footer">
        Dicetak: {{ now()->format('d F Y H:i:s') }} &nbsp;|&nbsp; GANDARIA  &nbsp;|&nbsp; Halaman 1 dari 1
    </div>
</body>
</html>