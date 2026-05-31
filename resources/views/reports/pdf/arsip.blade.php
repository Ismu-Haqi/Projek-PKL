<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Arsip Digital</title>
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
            display: block;
            margin: 0 auto;
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
        .info-box table { width: 100%; }
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

        /* ── Signature Section ── */
        .signature-section {
            margin-top: 40px;
            text-align: right;
        }
        .signature-wrapper {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }
        .signature-wrapper p {
            margin: 4px 0;
            font-size: 11px;
        }
        /* QR Code di atas nama */
        .qr-block {
            margin: 10px auto;
            text-align: center;
        }
        .qr-block img {
            width: 110px;
            height: 110px;
            display: block;
            margin: 0 auto;
        }
        .qr-label {
            font-size: 7.5px;
            color: #6b7280;
            margin: 3px 0 0 0;
        }
        .qr-url {
            font-size: 6.5px;
            color: #9ca3af;
            word-break: break-all;
            margin: 2px 0 0 0;
        }
        /* Nama penandatangan — tanpa garis */
        .signer-space {
            height: 8px;
        }
        .signer-name {
            font-weight: bold;
            font-size: 11px;
            margin: 4px 0 2px 0;
        }
        .signer-title {
            font-size: 10px;
            color: #555;
            margin: 0;
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
<div class="content-wrap">
    <!-- Header with Logos -->
    <div class="header">
        <div class="header-logos">
            <div class="logo-left">
                <img src="{{ public_path('images/logo-selidah.png') }}" alt="Logo Selidah">
            </div>
            <div class="header-text">
                <h1>LAPORAN ARSIP </h1>
                <p><strong>GANDARIA</strong></p>
                <p>Pengelolaan arsip dan data aset terstruktur, informatif dan akuntabel</p>
                <p>Dinas Komunikasi dan Informatika Kab. Barito Kuala</p>
            </div>
            <div class="logo-right">
                <img src="{{ public_path('images/gandaria.png') }}" alt="Logo Gandaria">
            </div>
        </div>
    </div>

    <!-- Info Box -->
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
                <td class="font-bold">Total Arsip</td>
                <td>: {{ $archives->count() }} Dokumen</td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Nomor Surat</th>
                <th width="25%">Judul</th>
                <th width="15%">Kategori</th>
                <th width="15%">Pengirim</th>
                <th width="15%">Unit</th>
                <th width="10%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($archives as $index => $archive)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $archive->nomor_surat }}</td>
                <td>{{ $archive->judul }}</td>
                <td>{{ $archive->category->name ?? '-' }}</td>
                <td>{{ $archive->pengirim }}</td>
                <td>{{ $archive->unit }}</td>
                <td class="text-center">{{ $archive->tanggal_surat ? $archive->tanggal_surat->format('d/m/Y') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div><!-- end content-wrap -->
    <!-- Signature Section -->
</div><!-- end content-wrap -->
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

    <!-- Footer -->
    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        <p>GANDARIA | Halaman 1 dari 1</p>
    </div>
</body>
</html>