<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi Email</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f3f4f6; padding: 40px;">
    <div style="max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 40px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h1 style="color: #1f2937; font-size: 24px; margin-bottom: 16px;">Verifikasi Email Anda</h1>
        <p style="color: #4b5563; font-size: 16px; margin-bottom: 24px;">Halo {{ $name }},</p>
        <p style="color: #4b5563; font-size: 16px; margin-bottom: 24px;">Gunakan kode berikut untuk memverifikasi email Anda:</p>
        <div style="text-align: center; margin: 32px 0;">
            <span style="display: inline-block; background: #eff6ff; color: #2563eb; font-size: 32px; font-weight: bold; letter-spacing: 12px; padding: 20px 32px; border-radius: 8px; border: 2px solid #bfdbfe;">{{ $code }}</span>
        </div>
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Kode ini berlaku selama 10 menit.</p>
        <p style="color: #6b7280; font-size: 14px;">Jika Anda tidak mendaftar di Web Architect, abaikan email ini.</p>
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 24px 0;">
        <p style="color: #9ca3af; font-size: 12px;">&copy; {{ date('Y') }} Web Architect. All rights reserved.</p>
    </div>
</body>
</html>
