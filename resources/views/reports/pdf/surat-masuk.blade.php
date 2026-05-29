<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Surat Masuk</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
        .header { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #0d9488; }
        .header-logos { display: table; width: 100%; margin-bottom: 10px; }
        .logo-left, .logo-right { display: table-cell; width: 15%; vertical-align: middle; text-align: center; }
        .header-text { display: table-cell; width: 70%; text-align: center; vertical-align: middle; }
        .logo-left img, .logo-right img { width: 75px; height: 75px; object-fit: contain; display: block; margin: 0 auto; }
        .header-text h1 { margin: 0; font-size: 17px; color: #0d9488; font-weight: bold; }
        .header-text p { margin: 3px 0; font-size: 10px; color: #555; }
        .info { background: #f0fdfa; border: 1px solid #ccfbf1; border-radius: 4px; padding: 8px 12px; margin-bottom: 15px; }
        .info table { width: 100%; }
        .info td { padding: 2px 5px; font-size: 10px; }
        .info td:first-child { font-weight: bold; width: 120px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background-color: #0d9488; color: white; padding: 7px 6px; text-align: left; border: 1px solid #ddd; font-size: 9px; }
        table.data td { padding: 5px 6px; border: 1px solid #ddd; font-size: 9px; vertical-align: top; }
        table.data tr:nth-child(even) { background-color: #f0fdfa; }
        .badge { padding: 2px 5px; border-radius: 8px; font-size: 8px; font-weight: bold; white-space: nowrap; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-orange { background: #ffedd5; color: #9a3412; }
        .badge-gray   { background: #f3f4f6; color: #374151; }

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

        /* Pisah data per halaman, tiap halaman ada TTE */
        .page-block        { page-break-after: always; }
        .page-block:last-child { page-break-after: avoid; }
    </style>
</head>
<body>

@php
    // Potong data per 20 baris agar tiap halaman punya TTE
    $chunks = isset($letters) ? $letters->chunk(20) : collect([collect([])]);
    $totalPages = $chunks->count();
@endphp

@foreach($chunks as $pageNum => $pageLetters)
<div class="{{ $pageNum < $totalPages - 1 ? 'page-block' : '' }}">

    {{-- Header (muncul di setiap halaman) --}}
    <div class="header">
        <div class="header-logos">
            <div class="logo-left">
                <img src="{{ public_path('images/logo-selidah.png') }}" alt="Logo">
            </div>
            <div class="header-text">
                <h1>LAPORAN SURAT MASUK</h1>
                <p><strong>GANDARIA</strong> — Pengelolaan arsip dan data aset terstruktur, informatif, dan akuntabel</p>
                <p>Dinas Komunikasi dan Informatika Kab. Barito Kuala</p>
            </div>
            <div class="logo-right">
                <img src="{{ public_path('images/gandaria.png') }}" alt="Gandaria">
            </div>
        </div>
    </div>

    {{-- Info (hanya halaman pertama) --}}
    @if($pageNum === 0)
    <div class="info">
        <table>
            <tr>
                <td>Tanggal Cetak</td>
                <td>: {{ now()->format('d F Y H:i:s') }}</td>
                <td width="20px"></td>
                <td width="100px"><strong>Total Surat</strong></td>
                <td>: {{ isset($letters) ? $letters->count() : 0 }} surat</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>: {{ $period ?? 'Semua Data' }}</td>
                <td></td>
                <td><strong>Halaman</strong></td>
                <td>: {{ $pageNum + 1 }} dari {{ $totalPages }}</td>
            </tr>
        </table>
    </div>
    @else
    <p style="font-size:9px; color:#666; margin-bottom:8px;">
        Halaman {{ $pageNum + 1 }} dari {{ $totalPages }} &nbsp;|&nbsp; Lanjutan data surat masuk
    </p>
    @endif

    {{-- Tabel Data --}}
    <table class="data">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">No. Agenda</th>
                <th width="9%">Tgl Diterima</th>
                <th width="16%">Pengirim</th>
                <th width="22%">Perihal</th>
                <th width="7%">Sifat</th>
                <th width="9%">Status</th>
                <th width="14%">Diinput Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pageLetters as $i => $letter)
            <tr>
                <td style="text-align:center;">{{ ($pageNum * 20) + $i + 1 }}</td>
                <td><strong style="color:#0d9488;">{{ $letter->nomor_agenda }}</strong></td>
                <td>{{ $letter->tanggal_diterima ? \Carbon\Carbon::parse($letter->tanggal_diterima)->format('d/m/Y') : '-' }}</td>
                <td>{{ $letter->pengirim }}</td>
                <td>{{ $letter->perihal }}</td>
                <td>
                    @php
                        $sifatClass = ['biasa'=>'gray','segera'=>'yellow','sangat_segera'=>'orange','rahasia'=>'red'];
                        $sifatLabel = ['biasa'=>'Biasa','segera'=>'Segera','sangat_segera'=>'Sgt Segera','rahasia'=>'Rahasia'];
                    @endphp
                    <span class="badge badge-{{ $sifatClass[$letter->sifat] ?? 'gray' }}">
                        {{ $sifatLabel[$letter->sifat] ?? $letter->sifat }}
                    </span>
                </td>
                <td>
                    @php
                        $stClass = ['belum_disposisi'=>'yellow','sudah_disposisi'=>'blue','selesai'=>'green'];
                        $stLabel = ['belum_disposisi'=>'Belum','sudah_disposisi'=>'Disposisi','selesai'=>'Selesai'];
                    @endphp
                    <span class="badge badge-{{ $stClass[$letter->status] ?? 'gray' }}">
                        {{ $stLabel[$letter->status] ?? $letter->status }}
                    </span>
                </td>
                <td>{{ $letter->uploader->name ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:15px;color:#999;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- TTE di setiap halaman --}}
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

    {{-- Footer --}}
    <div class="footer">
        Dicetak: {{ now()->format('d F Y H:i:s') }} &nbsp;|&nbsp; GANDARIA &nbsp;|&nbsp; Halaman {{ $pageNum + 1 }} dari {{ $totalPages }}
    </div>

</div>
@endforeach

</body>
</html>
