<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details</title>
</head>
<body>
    <h1>Item Details</h1>

    <div>
        <h2>{{ $item->name }}</h2>
        <p>{{ $item->description }}</p>
        <p><strong>Price:</strong> ${{ number_format($item->price, 2) }}</p>
        <p><strong>UUID:</strong> {{ $item->uuid }}</p>
    </div>

    <div>
        <h3>QR Code</h3>
        @if($item->qrCode)
            <img src="{{ asset('storage/qrcodes/'.$item->uuid.'.png') }}" alt="QR Code">
        @else
            <p>No QR code available.</p>
        @endif
    </div>

    <a href="/items">Back to Item List</a>
</body>
</html>
