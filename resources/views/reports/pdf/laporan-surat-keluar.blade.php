<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Surat Keluar</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
        .header { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #333; }
        .header-logos { display: table; width: 100%; margin-bottom: 15px; }
        .logo-left, .logo-right { display: table-cell; width: 15%; vertical-align: middle; text-align: center; }
        .header-text { display: table-cell; width: 70%; text-align: center; vertical-align: middle; }
        .logo-left img, .logo-right img { width: 80px; height: 80px; object-fit: contain; display: block; margin: 0 auto; }
        .header-text h1 { margin: 0; font-size: 18px; color: #333; font-weight: bold; }
        .header-text p { margin: 5px 0; font-size: 11px; color: #666; }
        .info table { width: 100%; }
        .info td { padding: 3px 0; font-size: 11px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.data th { background-color: #f3f4f6; padding: 8px; text-align: left; border: 1px solid #ddd; font-size: 10px; font-weight: bold; }
        table.data td { padding: 6px 8px; border: 1px solid #ddd; font-size: 9px; }
        .text-gray { color: #4b5563; }
        .text-yellow { color: #d97706; font-weight: bold; }
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
                <h1>LAPORAN SURAT KELUAR</h1>
                <p><strong>GANDARIA</strong></p>
                <p>Rekap surat keluar beserta status Tanda Tangan Elektronik</p>
            </div>
            <div class="logo-right"><img src="{{ public_path('images/gandaria.png') }}"></div>
        </div>
    </div>

    <div class="info">
        <table>
            <tr><td width="20%"><strong>Total Surat Keluar:</strong></td><td>{{ count($letters) }} Surat</td></tr>
            <tr><td><strong>Dicetak oleh:</strong></td><td>{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</td></tr>
            <tr><td><strong>Tanggal Cetak:</strong></td><td>{{ now()->format('d F Y H:i:s') }}</td></tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="12%">No. Agenda</th>
                <th width="10%">Tanggal</th>
                <th width="18%">Tujuan</th>
                <th width="22%">Perihal</th>
                <th width="14%">Dibuat Oleh</th>
                <th width="12%">Status</th>
                <th width="12%">Ditandatangani Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($letters as $letter)
            <tr>
                <td>{{ $letter->nomor_agenda }}</td>
                <td>{{ $letter->tanggal_surat->format('d/m/Y') }}</td>
                <td>{{ $letter->tujuan }}</td>
                <td>{{ $letter->perihal }}</td>
                <td>{{ $letter->pembuat->name ?? '-' }}</td>
                <td class="{{ $letter->status === 'ditandatangani' ? 'text-green' : ($letter->status === 'ditolak' ? 'text-red' : ($letter->status === 'menunggu_tte' ? 'text-yellow' : 'text-gray')) }}">
                    {{ strtoupper(str_replace('_', ' ', $letter->status)) }}
                </td>
                <td>{{ $letter->penandatangan->name ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center; padding: 20px;">Belum ada data surat keluar.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
    <div class="footer">
        Dicetak: {{ now()->format('d F Y H:i:s') }} &nbsp;|&nbsp; GANDARIA &nbsp;|&nbsp; Halaman 1 dari 1
    </div>
</body>
</html>
