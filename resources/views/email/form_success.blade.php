<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <p>Dear {{ $name ?? 'User' }},</p>

    <p>Thank you for submitting the <strong>{{ $formType }}</strong> form.</p>

    <p>Our team will contact you shortly.</p>

    <br>
    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>
