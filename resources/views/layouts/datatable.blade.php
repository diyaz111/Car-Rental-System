<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tables Car Available</h1>
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
                <table class="table display nowrap dataTable no-footer" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Car Name</th>
                            <th>Brand</th>
                            <th>Price Per Day</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        var dataTable = $('#dataTable').DataTable({
            ajax: "{{ url('cars/datatable') }}",
            "bLengthChange": false,
            paging: true,
            ordering: true,
            pageLength: 10,
            order: [
                [1, 'desc']
            ],
            columns: [{
                    "data": "nama"
                },
                {
                    "data": "brand"
                },
                {
                    "data": "price_per_day"
                }
            ],
            initComplete: function() {
                var api = this.api();

                api.columns(1).data().eq(0).unique().sort().each(function(d) {
                    $('#brandFilter').append('<option value="' + d + '">' + d + '</option>');
                });

                $('#brandFilter').on('change', function() {
                    api.column(1).search($(this).val()).draw();
                });

                $('#minPriceFilter, #maxPriceFilter').on('keyup change', function() {
                    api.draw();
                });
            }
        });

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var minPrice = parseInt($('#minPriceFilter').val(), 10);
            var maxPrice = parseInt($('#maxPriceFilter').val(), 10);
            var price = parseFloat(data[2]);

            if ((isNaN(minPrice) && isNaN(maxPrice)) ||
                (isNaN(minPrice) && price <= maxPrice) ||
                (minPrice <= price && isNaN(maxPrice)) ||
                (minPrice <= price && price <= maxPrice)) {
                return true;
            }
            return false;
        });
    });
</script>
