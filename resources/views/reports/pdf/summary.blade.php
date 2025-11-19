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

    @if(isset($stats))
    <div class="stats-grid">
        <div class="stat-box">
            <h3>TOTAL ARSIP</h3>
            <div class="number">{{ $stats['total_arsip'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <h3>TOTAL ASET</h3>
            <div class="number">{{ $stats['total_aset'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <h3>ARSIP BULAN INI</h3>
            <div class="number">{{ $stats['arsip_bulan_ini'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <h3>ASET BARU</h3>
            <div class="number">{{ $stats['aset_baru'] ?? 0 }}</div>
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

    @if(isset($archive_summary))
    <div class="section-title">Summary Arsip Berdasarkan Kategori</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah Dokumen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($archive_summary as $summary)
            <tr>
                <td>{{ $summary->kategori }}</td>
                <td>{{ $summary->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

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