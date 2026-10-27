
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Pendaftaran</title>
</head>
<body style="margin:0; padding:24px; background:#FAF7F2; font-family:Arial,sans-serif; color:#30251F;">
    <div style="max-width:480px; margin:auto; padding:32px; background:#ffffff; border-radius:16px;">
        <h1 style="margin-top:0; text-align:center;">Kode Verifikasi Pendaftaran</h1>

        <p>Halo!</p>

        <p>Gunakan kode berikut untuk memverifikasi email dan melanjutkan pendaftaran akun anda.</p>

        <div style="margin:28px 0; padding:20px; background:#FAF7F2; border-radius:12px; text-align:center;">
            <div style="font-size:32px; font-weight:bold; letter-spacing:8px;">
                {{ $code }}
            </div>
        </div>

        <p>Kode ini berlaku selama <strong>5 menit</strong>.</p>

        <p style="color:#777777; font-size:13px;">
            Jangan bagikan kode ini kepada siapa pun. Jika kamu tidak merasa melakukan pendaftaran, abaikan email ini.
        </p>
    </div>
</body>
</html>
