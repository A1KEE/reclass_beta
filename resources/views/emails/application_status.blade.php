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

<p>Regards,<br>SDO HR Department</p>

</body>
</html>