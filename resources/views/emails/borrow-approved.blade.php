<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .info-box { background: white; padding: 20px; margin: 20px 0; border-left: 4px solid #10b981; border-radius: 5px; }
        .button { display: inline-block; padding: 12px 30px; background: #10b981; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Peminjaman Disetujui!</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $borrowerName }}</strong>,</p>
            
            <p>Kabar baik! Pengajuan peminjaman aset Anda telah <strong>disetujui</strong>.</p>
            
            <div class="info-box">
                <h3 style="margin-top: 0;">Detail Peminjaman:</h3>
                <table style="width: 100%;">
                    <tr>
                        <td><strong>Kode Peminjaman:</strong></td>
                        <td>{{ $kodePeminjaman }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Aset:</strong></td>
                        <td>{{ $assetName }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pengambilan:</strong></td>
                        <td>{{ $tanggalPinjam }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pengembalian:</strong></td>
                        <td>{{ $tanggalKembali }}</td>
                    </tr>
                </table>
            </div>
            
            @if($borrow->catatan_admin)
            <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <strong>Catatan Admin:</strong>
                <p style="margin: 5px 0 0 0;">{{ $borrow->catatan_admin }}</p>
            </div>
            @endif
            
            <p><strong>Langkah Selanjutnya:</strong></p>
            <ul>
                <li>Ambil aset sesuai jadwal yang ditentukan</li>
                <li>Lakukan pengecekan kondisi aset saat pengambilan</li>
                <li>Kembalikan aset tepat waktu dalam kondisi baik</li>
            </ul>
            
            <center>
                <a href="{{ route('staff.peminjaman.show', $borrow->id) }}" class="button">
                    Lihat Detail Peminjaman
                </a>
            </center>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis dari GANDARIA - Sistem Arsip Digital Diskominfo Batola</p>
            <p>&copy; {{ date('Y') }} Diskominfo Batola. All rights reserved.</p>
        </div>
    </div>
</body>
</html>