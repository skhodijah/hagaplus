<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Reset Password - {{ config('app.name', 'HagaPlus') }}</title>
    <style>
        body {font-family: 'Figtree', sans-serif; background:#f9fafb; color:#111827; padding:2rem;}
        .container {max-width:600px; margin:auto; background:#fff; padding:2rem; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.05);}
        .button {display:inline-block; background:#0EC774; color:#fff; padding:0.75rem 1.5rem; border-radius:6px; text-decoration:none; font-weight:bold;}
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <p>Hello {{ $email }},</p>
        <p>You requested a password reset for your Haga+ account. Click the button below to set a new password. This link will expire in 60 minutes.</p>
        <p style="text-align:center; margin:1.5rem 0;">
            <a href="{{ $url }}" class="button">Reset Password</a>
        </p>
        <p>If you did not request this email, you can safely ignore it.</p>
        <p>Thanks,<br>{{ config('app.name', 'HagaPlus') }} Team</p>
    </div>
</body>
</html>
