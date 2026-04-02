<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Application Status</title>
</head>
<body>

    <h2>SDO HR Department</h2>

<p>Dear <strong>{{ $application->name }}</strong>,</p>

<p>Thank you for submitting your application.</p>

<p>
    <strong>Status:</strong> {!! $finalResultText !!}
</p>

<p>
    Position Applied: {{ $application->position_applied }}<br>
    School: {{ $application->school_name }}
</p>

@if($password)

<hr>

<h3>🔐 Your Account Details</h3>

<p>
    <strong>Login Link:</strong><br>
    <a href="{{ url('/login') }}">{{ url('/login') }}</a>
</p>

<p>
    <strong>Username (Email):</strong><br>
    {{ $application->email }}
</p>

<p>
    <strong>Temporary Password:</strong><br>
    {{ $password }}
</p>

<p style="color:red;">
    ⚠ Please login and change your password immediately.
</p>

@endif

<p>Regards,<br>SDO HR Department</p>

</body>
</html>