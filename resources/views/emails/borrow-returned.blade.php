<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .info-box { background: white; padding: 20px; margin: 20px 0; border-left: 4px solid #8b5cf6; border-radius: 5px; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔄 Aset Telah Dikembalikan</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $ownerName }}</strong>,</p>
            
            <p>Aset Anda telah dikembalikan oleh peminjam.</p>
            
            <div class="info-box">
                <h3 style="margin-top: 0;">Detail Pengembalian:</h3>
                <table style="width: 100%;">
                    <tr>
                        <td><strong>Kode:</strong></td>
                        <td>{{ $kodePeminjaman }}</td>
                    </tr>
                    <tr>
                        <td><strong>Peminjam:</strong></td>
                        <td>{{ $borrowerName }} ({{ $borrowerUnit }})</td>
                    </tr>
                    <tr>
                        <td><strong>Aset:</strong></td>
                        <td>{{ $assetName }} ({{ $assetCode }})</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Kembali:</strong></td>
                        <td>{{ $tanggalKembali }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kondisi:</strong></td>
                        <td><strong>{{ $kondisiKembali }}</strong></td>
                    </tr>
                </table>
            </div>
            
            <p>Silakan lakukan pengecekan kondisi aset dan verifikasi pengembalian melalui sistem.</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis dari GANDARIA - Sistem Arsip Digital Diskominfo Batola</p>
            <p>&copy; {{ date('Y') }} Diskominfo Batola. All rights reserved.</p>
        </div>
    </div>
</body>
</html>