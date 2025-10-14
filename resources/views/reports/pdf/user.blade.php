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
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
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
            font-size: 10px;
        }
        table tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-admin { background-color: #e9d5ff; color: #6b21a8; }
        .badge-staff { background-color: #d1fae5; color: #065f46; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN AKTIVITAS USER</h1>
        <p>GANDARIA - Generasi Arsip Nasional Digital Reformasi Indonesia Anda</p>
        <p>Dinas Komunikasi dan Informatika</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td width="150" class="font-bold">Tanggal Cetak</td>
                <td>: {{ now()->format('d F Y H:i:s') }}</td>
            </tr>
            <tr>
                <td class="font-bold">Total User</td>
                <td>: {{ count($users) }} Pengguna</td>
            </tr>
        </table>
    </div>

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

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        <p>GANDARIA - Sistem Arsip Digital</p>
    </div>
</body>
</html>