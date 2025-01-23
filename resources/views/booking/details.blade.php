@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Booking Details</h1>

    @if(session('dataBooking'))
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Booking Information</h6>
            </div>
            <div class="card-body">
                <p><strong>User ID:</strong> {{ session('dataBooking')['user_id'] }}</p>
                <p><strong>Car Name:</strong> {{ session('dataBooking')['carName'] }}</p>
                <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse(session('dataBooking')['startDate'])->format('Y-m-d') }}</p>
                <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse(session('dataBooking')['endDate'])->format('Y-m-d') }}</p>
                <p><strong>Total Days:</strong> {{ session('dataBooking')['totalDays'] }}</p>
                <p><strong>Total Price:</strong>{{ \App\Helpers\CommonHelpers::formatRupiah(session('dataBooking')['totalPrice']) }}</p>
                <p><strong>Status:</strong> {{ session('dataBooking')['status'] }}</p>
            </div>
        </div>
    @else
        <div class="alert alert-warning" role="alert">
            No booking details found.
        </div>
    @endif
</div>

@endsection
