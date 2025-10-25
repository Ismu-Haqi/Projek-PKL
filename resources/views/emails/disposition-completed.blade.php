<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disposisi Diselesaikan</title>
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
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
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
        .success-badge {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            text-align: center;
            margin: 25px 0;
            font-size: 18px;
            font-weight: 600;
        }
        .info-box {
            background-color: #d1fae5;
            border-left: 4px solid #10B981;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .info-box h3 {
            color: #065f46;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #a7f3d0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #065f46;
            width: 140px;
            flex-shrink: 0;
        }
        .info-value {
            color: #047857;
            flex: 1;
        }
        .notes-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .notes-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .notes-text {
            color: #6b7280;
            line-height: 1.6;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            margin: 25px 0;
            transition: transform 0.2s;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(16, 185, 129, 0.4);
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
            color: #10B981;
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
            <h1>✅ Disposisi Diselesaikan</h1>
            <p>GANDARIA - Arsip Digital Diskominfo</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Halo, <strong>{{ $disposition->fromUser->name }}</strong>! 👋
            </div>

            <div class="success-badge">
                ✨ Disposisi Telah Selesai
            </div>

            <div class="message">
                <strong>{{ $completedBy }}</strong> telah menyelesaikan disposisi yang Anda berikan. Berikut adalah detail penyelesaiannya:
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <h3>📋 Detail Disposisi</h3>
                
                <div class="info-row">
                    <div class="info-label">Nomor:</div>
                    <div class="info-value"><strong>{{ $disposition->nomor_disposisi }}</strong></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Subjek:</div>
                    <div class="info-value"><strong>{{ $disposition->subject }}</strong></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Diselesaikan Oleh:</div>
                    <div class="info-value">{{ $completedBy }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Waktu Selesai:</div>
                    <div class="info-value"><strong>{{ $completedAt }}</strong></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span style="background-color: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            ✓ SELESAI
                        </span>
                    </div>
                </div>
            </div>

            <!-- Notes Box -->
            @if($notes != '-')
            <div class="notes-box">
                <div class="notes-label">📝 Catatan Penyelesaian:</div>
                <div class="notes-text">{{ $notes }}</div>
            </div>
            @endif

            <div class="divider"></div>

            <!-- CTA Button -->
            <center>
                <a href="{{ $url }}" class="cta-button">
                    📂 Lihat Detail Disposisi
                </a>
            </center>

            <div class="message" style="margin-top: 20px; text-align: center; font-size: 13px; color: #9ca3af;">
                Atau copy link berikut ke browser Anda:<br>
                <a href="{{ $url }}" style="color: #10B981; word-break: break-all;">{{ $url }}</a>
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