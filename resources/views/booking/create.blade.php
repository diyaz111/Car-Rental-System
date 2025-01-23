@extends('layouts.app')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Create Booking Car</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK',
        });
    </script>
    @endif
    <form method="POST" id="bookingForm" action="{{ route('booking.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="user_id">User</label>
            <input type="text" class="form-control" id="user_id" name="user_id" value="{{ $user }}" readonly>
        </div>

        <div class="form-group">
            <label for="car_id">Car</label>
            <select class="form-control select-2" id="car_id" name="car_id" required>
                <option value="" disabled selected>Select a car</option>
                @foreach ($cars as $car)
                <option value="{{ $car->id }}" data-price="{{ $car->price_per_day }}">
                    {{ $car->nama }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="startdate">Start Date</label>
            <input type="text" class="form-control datepicker" id="startdate" name="startdate" required>
        </div>

        <div class="form-group">
            <label for="enddate">End Date</label>
            <input type="text" class="form-control datepicker" id="enddate" name="enddate" required>
        </div>

        <div class="form-group">
            <label for="total_price">Total Price</label>
            <input type="text" class="form-control" id="total_price" name="total_price" readonly>
        </div>

        <button type="button" id="submitBooking" class="btn btn-primary">Submit</button>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.select-2').select2();
        // Initialize Flatpickr
        $('.datepicker').flatpickr({
            dateFormat: 'Y-m-d',
            minDate: 'today'
        });

        // Format Rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        // Calculate Total Price
        function calculateTotalPrice() {
            const selectedCar = $('#car_id option:selected');
            const pricePerDay = selectedCar.data('price') || 0;
            const startDate = new Date($('#startdate').val());
            const endDate = new Date($('#enddate').val());

            if (startDate && endDate && endDate > startDate) {
                const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                const totalPrice = days * pricePerDay;
                $('#total_price').val(totalPrice ? formatRupiah(totalPrice) : '');
            } else {
                $('#total_price').val('');
            }
        }
        $('#car_id, #startdate, #enddate').on('change', calculateTotalPrice);

        // Handle form submission with SweetAlert
        $('#submitBooking').on('click', function() {
            const totalPrice = $('#total_price').val();
            console.log(totalPrice)
            if (!totalPrice) {
                Swal.fire({
                    icon: 'error',
                    title: 'Incomplete Data',
                    text: 'Please fill in all the fields correctly!',
                });
                return;
            }

            Swal.fire({
                title: 'Confirm Booking',
                text: 'Are you sure you want to submit this booking?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Submit',
                cancelButtonText: 'No, Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    $('#bookingForm').submit();
                }
            });
        });
    });
</script>

@endsection
