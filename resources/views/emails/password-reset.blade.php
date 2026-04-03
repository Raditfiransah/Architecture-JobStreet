<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f3f4f6; padding: 40px;">
    <div style="max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 40px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h1 style="color: #1f2937; font-size: 24px; margin-bottom: 16px;">Reset Password</h1>
        <p style="color: #4b5563; font-size: 16px; margin-bottom: 24px;">Halo {{ $name }},</p>
        <p style="color: #4b5563; font-size: 16px; margin-bottom: 24px;">Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ $url }}" style="display: inline-block; background: #2563eb; color: #ffffff; font-size: 16px; font-weight: bold; padding: 14px 32px; border-radius: 8px; text-decoration: none;">Reset Password</a>
        </div>
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Link ini berlaku selama 60 menit.</p>
        <p style="color: #6b7280; font-size: 14px;">Jika Anda tidak meminta reset password, abaikan email ini.</p>
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 24px 0;">
        <p style="color: #9ca3af; font-size: 12px;">&copy; {{ date('Y') }} Web Architect. All rights reserved.</p>
    </div>
</body>
</html>
