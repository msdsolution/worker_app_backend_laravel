<!DOCTYPE html>
<html lang="en">
<head>
    <title>Invoice</title>
</head>
<body>
    <p>Dear {{ $clientName }},</p>
@php
    // Debugging the $message variable
    dd($message);
@endphp
    @if(!empty($message))
        <p>{{ $message }}</p> <!-- Ensure $message is a string -->
    @endif
    <p>Thank you for your business.</p>
</body>
</html>
