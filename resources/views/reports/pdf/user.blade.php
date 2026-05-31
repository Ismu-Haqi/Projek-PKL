<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Aktivitas User</title>
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
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 3px 5px;
            font-size: 11px;
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
            font-size: 11px;
            color: #666;
        }
        .stat-box .number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
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
            font-size: 9px;
        }
        table tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-admin { background-color: #e9d5ff; color: #6b21a8; }
        .badge-staff { background-color: #d1fae5; color: #065f46; }
        .badge-pimpinan { background-color: #fee2e2; color: #991b1b; }
        
        
        
        
        
                /* ── TTE & Signature ── */
        .signature-section { margin-top: 40px; text-align: right; } /* ✅ DIUBAH: tambah text-align right */
        .signature-wrapper { display: inline-block; text-align: center; min-width: 200px; } /* ✅ TAMBAHAN BARU */
        .qr-block { margin: 8px auto; text-align: center; }                                  /* ✅ TAMBAHAN BARU */
        .qr-block img { width: 110px; height: 110px; display: block; margin: 0 auto; }       /* ✅ TAMBAHAN BARU */
        .qr-label { font-size: 7.5px; color: #6b7280; margin: 3px 0 0 0; }                   /* ✅ TAMBAHAN BARU */
        .qr-url { font-size: 6.5px; color: #9ca3af; word-break: break-all; margin: 2px 0; }  /* ✅ TAMBAHAN BARU */
        .signer-space { height: 8px; }                                                        /* ✅ TAMBAHAN BARU */
        .signer-name { font-weight: bold; font-size: 11px; margin: 4px 0 2px 0; }            /* ✅ TAMBAHAN BARU */
        .signer-title { font-size: 10px; color: #555; margin: 0; } 
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
    <!-- Header with Logos -->
    <div class="header">
        <div class="header-logos">
            <div class="logo-left">
                <img src="{{ public_path('images/logo-selidah.png') }}" alt="Logo Selidah">
            </div>
            <div class="header-text">
                <h1>LAPORAN AKTIVITAS USER</h1>
                <p><strong>GANDARIA</strong></p>
                <p>Pengelolaan arsip dan data aset terstruktur, informatif, dan akuntabel</p>
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
                <td>: {{ $start_date->format('d M Y') }} s/d {{ $end_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="font-bold">Total User</td>
                <td>: {{ count($users) }} Pengguna</td>
            </tr>
        </table>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-box">
            <h3>Total Pengguna</h3>
            <div class="number">{{ count($users) }}</div>
        </div>
        <div class="stat-box">
            <h3>Admin</h3>
            <div class="number">{{ collect($users)->where('role', 'admin')->count() }}</div>
        </div>
        <div class="stat-box">
            <h3>Staff</h3>
            <div class="number">{{ collect($users)->where('role', 'staff')->count() }}</div>
        </div>
        <div class="stat-box">
            <h3>Total Arsip</h3>
            <div class="number">{{ collect($users)->sum('archives_count') }}</div>
        </div>
    </div>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama</th>
                <th width="20%">Email</th>
                <th width="10%">Role</th>
                <th width="15%">Unit</th>
                <th width="10%">Arsip</th>
                <th width="10%">Disp. Terkirim</th>
                <th width="10%">Disp. Diterima</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $user->role }}">{{ strtoupper($user->role) }}</span>
                </td>
                <td>{{ $user->unit ?? '-' }}</td>
                <td class="text-center">{{ $user->archives_count }}</td>
                <td class="text-center">{{ $user->sent_dispositions_count }}</td>
                <td class="text-center">{{ $user->received_dispositions_count }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TTE Signature -->
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
    <div class="footer">
        Dicetak: {{ now()->format('d F Y H:i:s') }} &nbsp;|&nbsp; GANDARIA  &nbsp;|&nbsp; Halaman 1 dari 1
    </div>
</body>
</html>