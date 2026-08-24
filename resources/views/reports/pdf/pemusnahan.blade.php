<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemusnahan Aset</title>
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
        .text-green { color: #16a34a; font-weight: bold; }

        .qr-block { margin: 6px auto; text-align: center; }
        .qr-block img { width: 100px; height: 100px; display: block; margin: 0 auto; }
        .qr-label { font-size: 7px; color: #6b7280; margin: 2px 0 0 0; }
        .qr-url { font-size: 6px; color: #9ca3af; word-break: break-all; margin: 1px 0 0 0; }
        .signer-space { height: 6px; }
        .signer-name { font-weight: bold; font-size: 11px; margin: 3px 0 1px 0; }
        .signer-title { font-size: 10px; color: #555; margin: 0; }

        .content-wrap { padding-bottom: 80px; }
        .footer {
            position: fixed; bottom: 0; left: 0; right: 0;
            text-align: center; font-size: 9px; color: #666;
            border-top: 1px solid #ddd; padding: 6px 0; background: #fff;
        }
        .signature-section { margin-top: 30px; width: 100%; page-break-inside: avoid; }
        .signature-inner { width: 200px; float: right; text-align: center; }
    </style>
</head>
<body>
<div class="content-wrap">
    <div class="header">
        <div class="header-logos">
            <div class="logo-left"><img src="{{ public_path('images/logo-selidah.png') }}"></div>
            <div class="header-text">
                <h1>LAPORAN PEMUSNAHAN ASET</h1>
                <p><strong>GANDARIA</strong></p>
                <p>Pengelolaan arsip dan data aset terstruktur, informatif, dan akuntabel</p>
            </div>
            <div class="logo-right"><img src="{{ public_path('images/gandaria.png') }}"></div>
        </div>
    </div>

    <div class="info">
        <table>
            <tr><td width="20%"><strong>Total Usulan Pemusnahan:</strong></td><td>{{ count($destructions) }} Usulan</td></tr>
            <tr><td><strong>Dicetak oleh:</strong></td><td>{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</td></tr>
            <tr><td><strong>Tanggal Cetak:</strong></td><td>{{ now()->format('d F Y H:i:s') }}</td></tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%" style="text-align: center">No</th>
                <th width="12%">No. Usulan</th>
                <th width="22%">Nama & Kode Aset</th>
                <th width="15%">Diajukan Oleh</th>
                <th width="10%">Tgl Usulan</th>
                <th width="18%">Alasan Pemusnahan</th>
                <th width="10%">Status</th>
                <th width="8%">Disetujui Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($destructions as $index => $d)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td><strong>{{ $d->nomor_pemusnahan }}</strong></td>
                <td>{{ $d->asset->nama ?? '-' }}<br><span style="color: #888;">{{ $d->asset->kode_asset ?? '-' }}</span></td>
                <td>{{ $d->pengaju->name ?? '-' }}</td>
                <td>{{ optional($d->tanggal_usulan)->format('d/m/Y') }}</td>
                <td>{{ \Illuminate\Support\Str::limit($d->alasan_pemusnahan, 80) }}</td>
                <td class="{{ $d->status === 'disetujui' ? 'text-green' : ($d->status === 'ditolak' ? 'text-red' : 'text-yellow') }}">
                    {{ strtoupper($d->status) }}
                </td>
                <td>{{ $d->penyetuju->name ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align: center; padding: 20px;">Belum ada data usulan pemusnahan aset.</td></tr>
            @endforelse
        </tbody>
    </table>

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
        Dicetak: {{ now()->format('d F Y H:i:s') }} &nbsp;|&nbsp; GANDARIA  &nbsp;|&nbsp; Halaman 1 dari 1
    </div>
</body>
</html>
