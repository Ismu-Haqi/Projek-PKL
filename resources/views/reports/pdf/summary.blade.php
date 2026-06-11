<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Summary Sistem</title>
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
            text-align: center; /* KONSISTENSI: Memastikan perataan tengah */
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
            display: block; /* KONSISTENSI: Menjadikan elemen blok */
            margin: 0 auto; /* KONSISTENSI: Menengahkan gambar dalam sel */
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
            color: #888;
            margin-top: 5px;
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
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #ddd;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .detail-table th {
            background-color: #f3f4f6;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 10px;
            font-weight: bold;
        }
        .detail-table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding: 6px 0;
            background: #fff;
        }
        .font-bold { font-weight: bold; }
        .content-wrap {
            padding-bottom: 80px;
        }
            .signature-section {
            margin-top: 30px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-inner {
            width: 200px;
            float: right;
            text-align: center;
        }
        
        
        .qr-block { margin: 6px auto; text-align: center; }
        .qr-block img { width: 100px; height: 100px; display: block; margin: 0 auto; }
        .qr-label { font-size: 7px; color: #6b7280; margin: 2px 0 0 0; }
        .qr-url { font-size: 6px; color: #9ca3af; word-break: break-all; margin: 1px 0 0 0; }
        .signer-space { height: 6px; }
        .signer-name { font-weight: bold; font-size: 11px; margin: 3px 0 1px 0; }
        .signer-title { font-size: 10px; color: #555; margin: 0; }
    </style>
</head>
<body>
<div class="content-wrap">
    <div class="header">
        <div class="header-logos">
            <div class="logo-left">
                <img src="{{ public_path('images/logo-selidah.png') }}" alt="Logo Selidah">
            </div>
            <div class="header-text">
                <h1>LAPORAN SUMMARY SISTEM</h1>
                <p><strong>GANDARIA</strong></p>
                <p>Sistem pengelolaan arsip dan data aset terpadu, terstruktur, informatif, dan akuntabel</p>
                <p>Dinas Komunikasi dan Informatika Kab. Barito Kuala
            </div>
            <div class="logo-right">
                <img src="{{ public_path('images/gandaria.png') }}" alt="Logo Gandaria">
            </div>
        </div>
    </div>

    @if(isset($archives))
    <div class="stats-grid">
        <div class="stat-box">
            <h3>TOTAL ARSIP</h3>
            <div class="number">{{ $archives ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <h3>TOTAL ASET</h3>
            <div class="number">{{ $assets ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <h3>TOTAL DISPOSISI</h3>
            <div class="number">{{ $dispositions ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <h3>TOTAL USER</h3>
            <div class="number">{{ $users ?? 0 }}</div>
        </div>
    </div>
    @endif
    
    <div class="info-box">
        <table>
            <tr>
                <td width="150" class="font-bold">Tanggal Cetak</td>
                <td>: {{ now()->format('d F Y H:i:s') }}</td>
            </tr>
            <tr>
                <td class="font-bold">Dicetak oleh</td>
                <td>: {{ Auth::user()->name ?? 'Administrator' }}</td>
            </tr>
        </table>
    </div>

    @if(isset($archive_stats) && count($archive_stats) > 0)
    <div class="section-title">Summary Arsip Berdasarkan Kategori</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah Dokumen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($archive_stats as $item)
            <tr>
                <td>{{ $item['category'] }}</td>
                <td>{{ $item['total'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(isset($disposition_stats) && count($disposition_stats) > 0)
    <div class="section-title">Summary Disposisi Berdasarkan Status</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($disposition_stats as $item)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $item['status'])) }}</td>
                <td>{{ $item['total'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(isset($asset_stats) && count($asset_stats) > 0)
    <div class="section-title">Summary Aset Berdasarkan Kategori</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asset_stats as $item)
            <tr>
                <td>{{ $item['kategori'] ?? '-' }}</td>
                <td>{{ $item['total'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    <div class="signature-section">
        <div class="signature-inner">
            <p>Marabahan, {{ now()->format('d F Y') }}</p>
            <p>Mengetahui,</p>

            @if(isset($qrSvg) && $qrSvg)
            <div class="qr-block">
                <img src="{{ $qrSvg }}" alt="QR TTE">
                <p class="qr-label">Tanda Tangan Elektronik</p>
                <p class="qr-url">{{ $validasiUrl ?? '' }}</p>
            </div>
            @else
            <div style="height: 70px;"></div>
            @endif

            <div class="signer-space"></div>
            <p class="signer-name">{{ isset($signature) ? $signature->signed_by : 'Aris Saputera, S.STP.,MSi.' }}</p>
            <p class="signer-title">{{ isset($signature) && $signature->signed_by_title ? $signature->signed_by_title : 'Kepala Dinas' }}</p>
        </div>
    </div>

</div><!-- end content-wrap -->

    <div class="footer">
        <p>GANDARIA | Halaman 1 dari 1</p>
    </div>
</body>
</html>