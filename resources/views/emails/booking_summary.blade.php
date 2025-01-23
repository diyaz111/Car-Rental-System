<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Summary</title>
</head>
<body>
    <h1>Booking Summary</h1>

    <p>Total Bookings: {{ $totalBookings }}</p>

    <h3>Bookings by Status:</h3>
    <ul>
        @foreach($bookingsByStatus as $status)
            <li>{{ $status->status }}: {{ $status->count }}</li>
        @endforeach
    </ul>

    <p>Total Revenue for Completed Bookings: Rp{{ number_format($totalRevenue, 2) }}</p>
</body>
</html>
