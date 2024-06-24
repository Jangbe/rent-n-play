<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP Reset Password</title>
</head>

<body>
    Ini adalah kode OTP anda
    <h2>{{ $otp }}</h2>
    akses ini untuk merubah katasandi anda.
    <a href="{{ url('/reset-password?email=' . $email . '&otp=' . $otp) }}">Reset Katasandi</a>
</body>

</html>
