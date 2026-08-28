<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keluar - {{ $letter->nomor_agenda }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 30px; color: #1f2937; }
        .header { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #0d9488; }
        .header-logos { display: table; width: 100%; margin-bottom: 10px; }
        .logo-left, .logo-right { display: table-cell; width: 15%; vertical-align: middle; text-align: center; }
        .header-text { display: table-cell; width: 70%; text-align: center; vertical-align: middle; }
        .logo-left img, .logo-right img { width: 75px; height: 75px; object-fit: contain; display: block; margin: 0 auto; }
        .header-text h1 { margin: 0; font-size: 15px; color: #0d9488; font-weight: bold; }
        .header-text p { margin: 3px 0; font-size: 10px; color: #555; }

        .doc-title { text-align: center; margin: 20px 0; }
        .doc-title h2 { margin: 0; font-size: 14px; text-decoration: underline; }
        .doc-title p { margin: 3px 0 0 0; font-size: 11px; }

        table.data { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table.data td { padding: 5px 8px; border: 1px solid #ccc; vertical-align: top; }
        table.data td.label { width: 160px; font-weight: bold; background: #f9fafb; }

        .perihal-block { margin: 15px 0; text-align: justify; line-height: 1.6; }
        .perihal-block .label { font-weight: bold; margin-bottom: 4px; }

        .signature-section { width: 100%; margin-top: 40px; }
        .signature-box { display: table; width: 100%; }
        .signature-col-left { display: table-cell; width: 50%; vertical-align: top; }
        .signature-col-right { display: table-cell; width: 50%; text-align: center; vertical-align: top; }
        .signature-col-right p { margin: 2px 0; }
        .signature-space { height: 70px; }
        .signer-name { font-weight: bold; text-decoration: underline; margin: 0; }
        .signer-title { font-size: 10px; color: #555; margin: 0; }

        .qr-block { text-align: center; margin-top: 10px; }
        .qr-block img { width: 100px; height: 100px; display: block; margin: 0 auto; }
        .qr-label { font-size: 8px; color: #0d9488; font-style: italic; margin: 4px 0 0 0; }
        .qr-url { font-size: 7px; color: #9ca3af; word-break: break-all; margin: 1px 0 0 0; }

        .status-note { margin-top: 30px; text-align: center; font-size: 10px; color: #d97706; font-style: italic; }

        .doc-number { text-align: center; font-size: 10px; color: #666; margin-top: 30px; padding-top: 8px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-logos">
            <div class="logo-left"><img src="{{ public_path('images/logo-selidah.png') }}" alt="Logo"></div>
            <div class="header-text">
                <h1>PEMERINTAH KABUPATEN BARITO KUALA</h1>
                <p>Dinas Komunikasi dan Informatika</p>
                <p><strong>GANDARIA</strong> — Sistem Pengelolaan Arsip dan Aset</p>
            </div>
            <div class="logo-right"><img src="{{ public_path('images/gandaria.png') }}" alt="Gandaria"></div>
        </div>
    </div>

    <div class="doc-title">
        <h2>SURAT KELUAR</h2>
        <p>Nomor Agenda: {{ $letter->nomor_agenda }}</p>
    </div>

    <table class="data">
        <tr>
            <td class="label">Nomor Surat</td>
            <td>{{ $letter->nomor_surat ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Surat</td>
            <td>{{ $letter->tanggal_surat->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Tujuan</td>
            <td>{{ $letter->tujuan }}</td>
        </tr>
        <tr>
            <td class="label">Sifat</td>
            <td>{{ ucfirst($letter->sifat) }}</td>
        </tr>
        <tr>
            <td class="label">Dibuat Oleh</td>
            <td>{{ $letter->pembuat->name ?? '-' }}</td>
        </tr>
    </table>

    <div class="perihal-block">
        <p class="label">Perihal:</p>
        <p>{{ $letter->perihal }}</p>
    </div>

    @if($letter->keterangan)
    <div class="perihal-block">
        <p class="label">Keterangan:</p>
        <p>{{ $letter->keterangan }}</p>
    </div>
    @endif

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-col-left"></div>
            <div class="signature-col-right">
                <p>Marabahan, {{ now()->translatedFormat('d F Y') }}</p>

                @if($qrSvg)
                <div class="qr-block">
                    <img src="{{ $qrSvg }}" alt="QR TTE">
                    <p class="qr-label">Ditandatangani secara elektronik</p>
                    <p class="qr-url">{{ $validasiUrl }}</p>
                </div>
                <p class="signer-name" style="margin-top: 6px;">{{ $signature->signed_by ?? '-' }}</p>
                <p class="signer-title">{{ $signature->signed_by_title ?? 'Kepala Dinas' }}</p>
                @else
                <div class="signature-space"></div>
                <p class="signer-name">( ..................................... )</p>
                <p class="signer-title">Kepala Dinas</p>
                @endif
            </div>
        </div>
    </div>

    @if(!$qrSvg)
    <p class="status-note">
        ⚠ Dokumen ini belum ditandatangani secara elektronik oleh pimpinan.
    </p>
    @endif

    <div class="doc-number">
        Dokumen ini digenerate oleh sistem GANDARIA — {{ $letter->nomor_agenda }}
        — dicetak pada {{ now()->format('d F Y H:i:s') }}
    </div>

</body>
</html>
