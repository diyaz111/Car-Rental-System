<!DOCTYPE html>
<html>
<head>
    <title>Booking Status Changed</title>
</head>
<body>
    <h1>Your booking status has been updated</h1>

    <p>Dear {{ $data['userName'] }},</p>
    <p>Your booking for the car <strong>{{ $data['carName'] }}</strong> has been updated to the status: <strong>{{ $data['status'] }}</strong>.</p>
    <p>Booking Details:</p>
    <ul>
        <li>Car: {{ $data['carName'] }}</li>
        <li>Brand: {{ $data['brand'] }}</li>
        <li>Start Date: {{ \Carbon\Carbon::parse($data['startDate'])->format('d-m-Y') }}</li>
        <li>End Date: {{ \Carbon\Carbon::parse($data['endDate'])->format('d-m-Y') }}</li>
        <li>Total Days: {{ $data['totalDays'] }}</li>
        <li>Total Price: Rp {{ \App\Helpers\CommonHelpers::formatRupiah($data['totalPrice'], 2) }}</li>
    </ul>
</body>
</html>
