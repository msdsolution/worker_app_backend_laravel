<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<body>
    <p>Dear {{ $clientName }},</p>
    @if(!empty($message))
        <p>{{ $message }}</p> <!-- Ensure $message is a string -->
    @endif
    <p>Thank you for your business.</p>
</body>
</html>
