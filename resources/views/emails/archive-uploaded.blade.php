<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Baru Diunggah</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            padding: 20px;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .email-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1f2937;
        }
        .message {
            color: #4b5563;
            margin-bottom: 25px;
            font-size: 15px;
        }
        .info-box {
            background-color: #dbeafe;
            border-left: 4px solid #3B82F6;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .info-box h3 {
            color: #1e40af;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #bfdbfe;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #1e40af;
            width: 140px;
            flex-shrink: 0;
        }
        .info-value {
            color: #1e3a8a;
            flex: 1;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            margin: 25px 0;
            transition: transform 0.2s;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(59, 130, 246, 0.4);
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 25px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .email-footer .app-name {
            color: #3B82F6;
            font-weight: 700;
            font-size: 18px;
            margin-top: 10px;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e5e7eb, transparent);
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>📄 Arsip Baru Diunggah</h1>
            <p>GANDARIA - Arsip Digital Diskominfo</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Halo, <strong>{{ $recipientName }}</strong>! 👋
            </div>

            <div class="message">
                <strong>{{ $uploader }}</strong> telah mengunggah arsip baru ke dalam sistem. Berikut adalah detail arsip yang diunggah:
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <h3>📋 Detail Arsip</h3>
                
                <div class="info-row">
                    <div class="info-label">Nomor Surat:</div>
                    <div class="info-value"><strong>{{ $archive->nomor_surat }}</strong></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Judul:</div>
                    <div class="info-value"><strong>{{ $archive->judul }}</strong></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Kategori:</div>
                    <div class="info-value">{{ $category }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Tanggal Surat:</div>
                    <div class="info-value">{{ $tanggalSurat }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Diunggah Oleh:</div>
                    <div class="info-value">{{ $uploader }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Unit:</div>
                    <div class="info-value">{{ $archive->unit ?? '-' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Waktu Upload:</div>
                    <div class="info-value">{{ $archive->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- CTA Button -->
            <center>
                <a href="{{ $url }}" class="cta-button">
                    📂 Lihat Detail Arsip
                </a>
            </center>

            <div class="message" style="margin-top: 20px; text-align: center; font-size: 13px; color: #9ca3af;">
                Atau copy link berikut ke browser Anda:<br>
                <a href="{{ $url }}" style="color: #3B82F6; word-break: break-all;">{{ $url }}</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>Email ini dikirim secara otomatis oleh sistem GANDARIA.</p>
            <p>Jangan balas email ini. Untuk pertanyaan, hubungi administrator sistem.</p>
            <div class="divider"></div>
            <div class="app-name">GANDARIA</div>
            <p style="margin-top: 5px;">Generasi Arsip Nasional Digital Reformasi Indonesia Anda</p>
            <p style="margin-top: 10px;">&copy; {{ date('Y') }} Diskominfo Kabupaten Barito Kuala</p>
        </div>
    </div>
</body>
</html>