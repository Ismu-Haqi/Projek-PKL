<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Beban Kerja Validasi Pimpinan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
        .header { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #333; }
        .header-logos { display: table; width: 100%; margin-bottom: 15px; }
        .logo-left, .logo-right { display: table-cell; width: 15%; vertical-align: middle; text-align: center; }
        .header-text { display: table-cell; width: 70%; text-align: center; vertical-align: middle; }
        .logo-left img, .logo-right img { width: 80px; height: 80px; object-fit: contain; display: block; margin: 0 auto; }
        .header-text h1 { margin: 0; font-size: 17px; color: #333; font-weight: bold; }
        .header-text p { margin: 5px 0; font-size: 11px; color: #666; }
        .info table { width: 100%; }
        .info td { padding: 3px 0; font-size: 11px; }

        .section-title { font-size: 13px; font-weight: bold; margin: 18px 0 8px 0; color: #1f2937; }

        .stat-grid { display: table; width: 100%; margin-bottom: 10px; }
        .stat-box { display: table-cell; width: 20%; padding: 8px; border: 1px solid #ddd; text-align: center; }
        .stat-box .label { font-size: 8.5px; color: #666; }
        .stat-box .value { font-size: 15px; font-weight: bold; color: #1f2937; margin-top: 2px; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background-color: #f3f4f6; padding: 7px; text-align: left; border: 1px solid #ddd; font-size: 9.5px; font-weight: bold; }
        table.data td { padding: 6px 7px; border: 1px solid #ddd; font-size: 9px; }
        .text-center { text-align: center; }
        .text-green { color: #16a34a; font-weight: bold; }
        .text-red { color: #dc2626; font-weight: bold; }

        .content-wrap { padding-bottom: 60px; }
        .footer {
            position: fixed; bottom: 0; left: 0; right: 0;
            text-align: center; font-size: 9px; color: #666;
            border-top: 1px solid #ddd; padding: 6px 0; background: #fff;
        }
    </style>
</head>
<body>
<div class="content-wrap">
    <div class="header">
        <div class="header-logos">
            <div class="logo-left"><img src="{{ public_path('images/logo-selidah.png') }}"></div>
            <div class="header-text">
                <h1>LAPORAN BEBAN KERJA VALIDASI PIMPINAN</h1>
                <p><strong>GANDARIA</strong></p>
                <p>Jumlah &amp; waktu proses persetujuan yang ditangani pimpinan — {{ $period }}</p>
            </div>
            <div class="logo-right"><img src="{{ public_path('images/gandaria.png') }}"></div>
        </div>
    </div>

    <div class="info">
        <table>
            <tr><td width="20%"><strong>Dicetak oleh:</strong></td><td>{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</td></tr>
            <tr><td><strong>Tanggal Cetak:</strong></td><td>{{ now()->format('d F Y H:i:s') }}</td></tr>
        </table>
    </div>

    <div class="section-title">📄 Validasi Laporan (Tanda Tangan Elektronik)</div>
    <div class="stat-grid">
        <div class="stat-box"><div class="label">Total Divalidasi</div><div class="value">{{ $laporanStats['total_divalidasi'] }}</div></div>
        <div class="stat-box"><div class="label">Disetujui</div><div class="value" style="color:#16a34a;">{{ $laporanStats['disetujui'] }}</div></div>
        <div class="stat-box"><div class="label">Ditolak</div><div class="value" style="color:#dc2626;">{{ $laporanStats['ditolak'] }}</div></div>
        <div class="stat-box"><div class="label">Rata-rata Proses</div><div class="value">{{ $laporanStats['rata_rata_jam'] ? number_format($laporanStats['rata_rata_jam'], 1) : '-' }} jam</div></div>
        <div class="stat-box"><div class="label">Tercepat / Terlama</div><div class="value" style="font-size:11px;">
            {{ $laporanStats['tercepat_jam'] !== null ? number_format($laporanStats['tercepat_jam'], 1) : '-' }} / {{ $laporanStats['terlama_jam'] !== null ? number_format($laporanStats['terlama_jam'], 1) : '-' }} jam
        </div></div>
    </div>

    @if($laporanPerJenis->isNotEmpty())
    <table class="data">
        <thead>
            <tr>
                <th width="50%">Jenis Laporan</th>
                <th width="25%" class="text-center">Jumlah Divalidasi</th>
                <th width="25%" class="text-center">Rata-rata Waktu (jam)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanPerJenis as $jenis => $d)
            <tr>
                <td>{{ ucfirst(str_replace('-', ' ', $jenis)) }}</td>
                <td class="text-center">{{ $d['total'] }}</td>
                <td class="text-center">{{ $d['rata_rata_jam'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="section-title">📋 Disposisi yang Ditangani Pimpinan</div>
    <div class="stat-grid">
        <div class="stat-box" style="width: 33.3%;"><div class="label">Total Diterima</div><div class="value">{{ $disposisiStats['total_diterima'] }}</div></div>
        <div class="stat-box" style="width: 33.3%;"><div class="label">Total Diselesaikan</div><div class="value" style="color:#16a34a;">{{ $disposisiStats['total_selesai'] }}</div></div>
        <div class="stat-box" style="width: 33.3%;"><div class="label">Rata-rata Waktu Selesai</div><div class="value">{{ $disposisiStats['rata_rata_jam'] }} jam</div></div>
    </div>

    <div class="section-title">Detail Validasi Laporan</div>
    <table class="data">
        <thead>
            <tr>
                <th width="26%">Judul Laporan</th>
                <th width="16%">Pengaju</th>
                <th width="16%">Diajukan</th>
                <th width="16%">Divalidasi</th>
                <th width="13%" class="text-center">Waktu Proses</th>
                <th width="13%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporanValidasi as $item)
            <tr>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->pengaju->name ?? '-' }}</td>
                <td>{{ $item->diajukan_at->format('d/m/Y H:i') }}</td>
                <td>{{ $item->divalidasi_at->format('d/m/Y H:i') }}</td>
                <td class="text-center">{{ $item->waktu_proses_jam }} jam</td>
                <td class="text-center {{ $item->status === 'disetujui' ? 'text-green' : 'text-red' }}">
                    {{ strtoupper($item->status) }}
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align: center; padding: 20px;">Belum ada laporan yang divalidasi pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
    <div class="footer">
        Dicetak: {{ now()->format('d F Y H:i:s') }} &nbsp;|&nbsp; GANDARIA &nbsp;|&nbsp; Halaman 1 dari 1
    </div>
</body>
</html>
