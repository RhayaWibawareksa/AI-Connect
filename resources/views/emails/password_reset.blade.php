<div style="font-family:system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color:#111;">
    <h2>Reset Password</h2>
    <p>Hai {{ $user->name ?? 'Pengguna' }},</p>
    <p>Kami menerima permintaan untuk mereset password akun Anda. Klik tautan di bawah untuk mengganti password Anda:</p>
    <p><a href="{{ $resetUrl }}">Reset Password</a></p>
    <p>Jika Anda tidak meminta perubahan ini, abaikan email ini.</p>
    <p>Salam,<br>Tim AI-Connect</p>
</div>
