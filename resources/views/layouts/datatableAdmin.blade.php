<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Data Booking</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="number" id="minPriceFilter" class="form-control form-control-sm" placeholder="Min Price">
                </div>
                <div class="col-md-3">
                    <input type="number" id="maxPriceFilter" class="form-control form-control-sm" placeholder="Max Price">
                </div>
                <div class="col-md-3">
                    <select id="brandFilter" class="form-control form-control-sm">
                        <option value="">All Brands</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('booking.create') }}" class="btn btn-primary btn-icon-split float-end">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Create</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table display dataTable" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Booking Id</th>
                            <th>User Name (ID)</th>
                            <th>Car ID</th>
                            <th>Car Name (Brand)</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
   $(document).ready(function () {
    var dataTable = $('#dataTable').DataTable({
        ajax: "{{ url('booking/datatable') }}",
        paging: true,
        ordering: true,
        pageLength: 10,
        columns: [
            { data: 'booking_id', orderable: true },
            {
                data: 'username',
                render: function (data, type, row) {
                    return data + ' (' + row.user_id + ')';
                }
            },
            {
                data: 'car_id'},
            {
                data: 'car_nama',
                render: function (data, type, row) {
                    return data + ' (' + row.car_brand + ')';
                }
            },
            { data: 'start_date' },
            { data: 'end_date' },
            {
                data: 'total_price',
                render: function (data, type, row) {
                    return 'Rp ' + parseInt(data).toLocaleString('id-ID');
                }
            },
            {
                data: 'status',
                render: function (data, type, row) {
                    @if(Auth::user()->role === 'admin')
                    return `
                        <select class="form-control form-control-sm status-dropdown" data-id="${row.id}" style="appearance: auto;">
                            <option value="pending" ${data === 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="confirmed" ${data === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                            <option value="completed" ${data === 'completed' ? 'selected' : ''}>Completed</option>
                            <option value="canceled" ${data === 'canceled' ? 'selected' : ''}>Canceled</option>
                        </select>
                    `;
                    @else
                    return data;
                    @endif
                },
            },
        ],
    });

    // Update status
    $('#dataTable').on('change', '.status-dropdown', function () {
        var bookingId = $(this).data('id');
        var status = $(this).val();

        $.ajax({
            url: `/booking/${bookingId}/update-status`,
            method: 'PATCH',
            data: {
                status: status,
                _token: '{{ csrf_token() }}',
            },
            success: function (response) {
                Swal.fire('Success', 'Status updated successfully!', 'success');
                dataTable.ajax.reload();
            },
            error: function (error) {
                Swal.fire('Error', 'Failed to update status. Please try again.', 'error');
            },
        });
    });
});

</script>
