@extends('dashboard.partials.main')

@section('content')
    <div class="row">
        <div class="col">

            <h3>Wilayah</h3>
        </div>
        <div class="col">
            <button class="btn btn-primary float-end">Tambah</button>
        </div>
    </div>
    <div class="row mt-2">

        <div class="card p-3">
            <ul class="nav nav-pills ">
                <li class="nav-item">
                    <a class="nav-link active" href="/settings/wilayah">Wilayah</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/settings/kondisi">Kondisi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  " href="/settings/petugas">Petugas</a>
                </li>
            </ul>
            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Wilayah</th>
                            <th>Nama Wilayah</th>
                            <th>Cabang</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div id="dynamicModalContainer"></div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var isMobile = window.innerWidth <= 768;
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('wilayah.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'wilayah',
                        name: 'wilayah'
                    },
                    {
                        data: 'cabang',
                        name: 'cabang'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search...",
                    "decimal": ",",
                    "thousands": ".",
                },
                "scrollX": isMobile,
            });

            $('.dataTables_filter input[type="search"]').css({
                "marginBottom": "10px"
            });

            $('#myTable').on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get(`/settings/wilayah/${id}/modal-edit`, function(html) {
                    $('#dynamicModalContainer').html(html);

                    // Tunggu DOM update, lalu inisialisasi modal
                    setTimeout(() => {
                        let modalEl = document.getElementById('editModal' + id);
                        if (modalEl) {
                            let modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        }
                    }, 10); // Tunggu sedikit untuk memastikan modal sudah ter-render
                });
            });

            $('#myTable').on('click', '.btn-delete', function() {
                let id = $(this).data('id');
                $.get(`/settings/wilayah/${id}/modal-delete`, function(html) {
                    $('#dynamicModalContainer').html(html);

                    setTimeout(() => {
                        let modalEl = document.getElementById('deleteModal' + id);
                        if (modalEl) {
                            let modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        }
                    }, 10); // Tunggu sedikit untuk memastikan modal sudah ter-render
                });
            });

        });
    </script>
@endpush
