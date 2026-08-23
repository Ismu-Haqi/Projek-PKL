<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara Pemusnahan Aset - {{ $destruction->nomor_pemusnahan }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 30px; color: #1f2937; }
        .header { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #0d9488; }
        .header-logos { display: table; width: 100%; margin-bottom: 10px; }
        .logo-left, .logo-right { display: table-cell; width: 15%; vertical-align: middle; text-align: center; }
        .header-text { display: table-cell; width: 70%; text-align: center; vertical-align: middle; }
        .logo-left img, .logo-right img { width: 75px; height: 75px; object-fit: contain; display: block; margin: 0 auto; }
        .header-text h1 { margin: 0; font-size: 16px; color: #0d9488; font-weight: bold; }
        .header-text p { margin: 3px 0; font-size: 10px; color: #555; }

        .title-block { text-align: center; margin: 25px 0; }
        .title-block h2 { margin: 0; font-size: 15px; text-decoration: underline; letter-spacing: 1px; }
        .title-block p { margin: 3px 0 0 0; font-size: 11px; }

        .intro { text-align: justify; margin-bottom: 15px; line-height: 1.6; }

        table.data { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table.data td { padding: 5px 8px; border: 1px solid #ccc; vertical-align: top; }
        table.data td.label { width: 200px; font-weight: bold; background: #f9fafb; }

        .reason-block { margin: 15px 0; text-align: justify; line-height: 1.6; }
        .reason-block .label { font-weight: bold; margin-bottom: 4px; }

        .closing { text-align: justify; margin: 20px 0; line-height: 1.6; }

        .signature-section { width: 100%; margin-top: 40px; }
        .signature-box { display: table; width: 100%; }
        .signature-col { display: table-cell; width: 50%; text-align: center; vertical-align: top; }
        .signature-col p { margin: 2px 0; }
        .signature-space { height: 70px; }
        .signer-name { font-weight: bold; text-decoration: underline; margin: 0; }
        .signer-title { font-size: 10px; color: #555; margin: 0; }

        .stamp-note { margin-top: 4px; font-size: 9px; color: #0d9488; font-style: italic; }

        .doc-number { text-align: center; font-size: 10px; color: #666; margin-top: 30px; padding-top: 8px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="header-logos">
            <div class="logo-left">
                <img src="{{ public_path('images/logo-selidah.png') }}" alt="Logo">
            </div>
            <div class="header-text">
                <h1>PEMERINTAH KABUPATEN BARITO KUALA</h1>
                <p>Dinas Komunikasi dan Informatika</p>
                <p><strong>GANDARIA</strong> — Sistem Pengelolaan Arsip dan Aset</p>
            </div>
            <div class="logo-right">
                <img src="{{ public_path('images/gandaria.png') }}" alt="Gandaria">
            </div>
        </div>
    </div>

    {{-- Judul --}}
    <div class="title-block">
        <h2>BERITA ACARA PEMUSNAHAN ASET</h2>
        <p>Nomor: {{ $destruction->nomor_pemusnahan }}</p>
    </div>

    {{-- Kalimat pembuka --}}
    <div class="intro">
        Pada hari ini, {{ \Carbon\Carbon::parse($destruction->tanggal_persetujuan)->translatedFormat('l') }},
        tanggal {{ \Carbon\Carbon::parse($destruction->tanggal_persetujuan)->translatedFormat('d F Y') }},
        yang bertanda tangan di bawah ini telah melakukan pemeriksaan dan menyetujui pemusnahan
        terhadap aset milik <strong>Dinas Komunikasi dan Informatika Kabupaten Barito Kuala</strong>
        dengan rincian sebagai berikut:
    </div>

    {{-- Data aset --}}
    <table class="data">
        <tr>
            <td class="label">Nama Aset</td>
            <td>{{ $destruction->asset->nama }}</td>
        </tr>
        <tr>
            <td class="label">Kode Aset</td>
            <td>{{ $destruction->asset->kode_asset }}</td>
        </tr>
        <tr>
            <td class="label">Kategori</td>
            <td>{{ $destruction->asset->kategori ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Unit / Lokasi</td>
            <td>{{ $destruction->asset->unit ?? '-' }} / {{ $destruction->asset->lokasi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kondisi Aset</td>
            <td>{{ $destruction->kondisi_aset ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Diajukan Oleh</td>
            <td>{{ $destruction->pengaju->name }} — {{ now()->parse($destruction->tanggal_usulan)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    {{-- Alasan --}}
    <div class="reason-block">
        <p class="label">Alasan Pemusnahan:</p>
        <p>{{ $destruction->alasan_pemusnahan }}</p>
    </div>

    {{-- Penutup --}}
    <div class="closing">
        Demikian Berita Acara Pemusnahan Aset ini dibuat dengan sebenarnya untuk dapat dipergunakan
        sebagaimana mestinya sebagai bukti sah bahwa aset tersebut telah dinyatakan dimusnahkan
        dan dihapus dari daftar inventaris aktif.
    </div>

    {{-- Tanda tangan --}}
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-col">
                <p>Yang Mengajukan,</p>
                <div class="signature-space"></div>
                <p class="signer-name">{{ $destruction->pengaju->name }}</p>
                <p class="signer-title">{{ ucfirst($destruction->pengaju->role) }}</p>
            </div>
            <div class="signature-col">
                <p>Menyetujui / Mengesahkan,</p>
                <div class="signature-space"></div>
                <p class="signer-name">{{ $destruction->penyetuju->name }}</p>
                <p class="signer-title">{{ ucfirst($destruction->penyetuju->role) }}</p>
                <p class="stamp-note">Disetujui secara elektronik melalui sistem GANDARIA
                    pada {{ \Carbon\Carbon::parse($destruction->tanggal_persetujuan)->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </div>

    <div class="doc-number">
        Dokumen ini digenerate otomatis oleh sistem GANDARIA — {{ $destruction->nomor_pemusnahan }}
        — dicetak pada {{ now()->format('d F Y H:i:s') }}
    </div>

</body>
</html>
