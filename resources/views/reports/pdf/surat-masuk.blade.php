<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Surat Masuk</title>
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
        table.data th { background-color: #0d9488; color: white; padding: 8px; text-align: left; border: 1px solid #ddd; font-size: 10px; }
        table.data td { padding: 6px 8px; border: 1px solid #ddd; font-size: 9px; vertical-align: top; }
        table.data tr:nth-child(even) { background-color: #f0fdfa; }
        .badge { padding: 2px 6px; border-radius: 10px; font-size: 8px; font-weight: bold; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-orange { background: #ffedd5; color: #9a3412; }
        .badge-gray   { background: #f3f4f6; color: #374151; }

        /* TTE Signature Section */
        .signature-section { margin-top: 40px; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-left { width: 55%; vertical-align: bottom; }
        .signature-right { width: 45%; vertical-align: top; text-align: center; }
        .tte-box { display: inline-block; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 10px; background: #f9fafb; text-align: center; width: 160px; }
        .tte-box img { width: 110px; height: 110px; display: block; margin: 0 auto 4px auto; }
        .tte-label { font-size: 8px; color: #6b7280; margin: 2px 0; line-height: 1.3; }
        .tte-label-bold { font-size: 8px; font-weight: bold; color: #374151; margin: 2px 0; }
        .tte-url { font-size: 6.5px; color: #9ca3af; word-break: break-all; margin-top: 3px; }
        .ttd-area { text-align: center; margin-top: 8px; }
        .ttd-area p { margin: 4px 0; font-size: 11px; }
        .ttd-space { height: 55px; }
        .signer-name { font-weight: bold; font-size: 11px; margin: 4px 0 2px 0; }
        .signer-title { font-size: 10px; color: #555; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #666; border-top: 1px solid #ddd; padding-top: 8px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-logos">
            <div class="logo-left">
                <img src="{{ public_path('images/logo-selidah.png') }}" alt="Logo">
            </div>
            <div class="header-text">
                <h1>LAPORAN SURAT MASUK</h1>
                <p><strong>GANDARIA</strong></p>
                <p>Sistem Pengelolaan Arsip dan Data Aset Terpadu</p>
                <p>Dinas Komunikasi dan Informatika Kab. Barito Kuala</p>
            </div>
            <div class="logo-right">
                <img src="{{ public_path('images/gandaria.png') }}" alt="Gandaria">
            </div>
        </div>
    </div>

    <!-- Info -->
    <div class="info">
        <table>
            <tr>
                <td width="150"><strong>Tanggal Cetak</strong></td>
                <td>: {{ now()->format('d F Y H:i:s') }}</td>
                <td width="150"><strong>Total Surat</strong></td>
                <td>: {{ isset($letters) ? $letters->count() : 0 }} surat</td>
            </tr>
            <tr>
                <td><strong>Periode</strong></td>
                <td>: {{ $period ?? 'Semua Data' }}</td>
                <td><strong>Dicetak oleh</strong></td>
                <td>: {{ isset($signature) ? $signature->signed_by : 'Sistem' }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data -->
    <table class="data">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="13%">No. Agenda</th>
                <th width="10%">Tgl Diterima</th>
                <th width="18%">Pengirim</th>
                <th width="24%">Perihal</th>
                <th width="8%">Sifat</th>
                <th width="10%">Status</th>
                <th width="14%">Diinput Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse(isset($letters) ? $letters : [] as $i => $letter)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $letter->nomor_agenda }}</strong></td>
                <td>{{ $letter->tanggal_diterima ? \Carbon\Carbon::parse($letter->tanggal_diterima)->format('d/m/Y') : '-' }}</td>
                <td>{{ $letter->pengirim }}</td>
                <td>{{ $letter->perihal }}</td>
                <td>
                    @php
                        $sifatClass = ['biasa' => 'gray', 'segera' => 'yellow', 'sangat_segera' => 'orange', 'rahasia' => 'red'];
                        $sifatLabel = ['biasa' => 'Biasa', 'segera' => 'Segera', 'sangat_segera' => 'Sgt Segera', 'rahasia' => 'Rahasia'];
                    @endphp
                    <span class="badge badge-{{ $sifatClass[$letter->sifat] ?? 'gray' }}">
                        {{ $sifatLabel[$letter->sifat] ?? $letter->sifat }}
                    </span>
                </td>
                <td>
                    @php
                        $statusClass = ['belum_disposisi' => 'yellow', 'sudah_disposisi' => 'blue', 'selesai' => 'green'];
                        $statusLabel = ['belum_disposisi' => 'Belum', 'sudah_disposisi' => 'Disposisi', 'selesai' => 'Selesai'];
                    @endphp
                    <span class="badge badge-{{ $statusClass[$letter->status] ?? 'gray' }}">
                        {{ $statusLabel[$letter->status] ?? $letter->status }}
                    </span>
                </td>
                <td>{{ $letter->uploader->name ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center; padding: 20px; color: #999;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature + TTE -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td class="signature-left"></td>
                <td class="signature-right">
                    @if(isset($qrSvg) && $qrSvg)
                    <div class="tte-box">
                        <img src="{{ $qrSvg }}" alt="QR TTE">
                        <p class="tte-label-bold">Tanda Tangan Elektronik</p>
                        <p class="tte-label">Scan QR untuk verifikasi keaslian dokumen</p>
                        <p class="tte-url">{{ $validasiUrl ?? '' }}</p>
                    </div>
                    @endif
                    <div class="ttd-area">
                        <p>Marabahan, {{ now()->translatedFormat('d F Y') }}</p>
                        <p>Mengetahui,</p>
                        <div class="ttd-space"></div>
                        <p class="signer-name">{{ isset($signature) ? $signature->signed_by : 'Azwar Arsyadi, S.Kom' }}</p>
                        <p class="signer-title">{{ isset($signature) && $signature->signed_by_title ? $signature->signed_by_title : 'Kepala Dinas' }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }} &nbsp;|&nbsp; GANDARIA - Sistem Arsip Digital &nbsp;|&nbsp; Halaman 1</p>
    </div>
</body>
</html>
