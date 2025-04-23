@extends('dashboard.partials.main')

@section('content')
    <div class="row mt-3">
        <div class="col">
            @if (session()->has('success'))
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('delete'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('delete') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('update'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('update') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>
    <div class="row mt-2">

        <div class="card p-3">
            <div class="row">
                <div class="col">
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
                </div>
                <div class="col">
                    <div class="col">
                        <button class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#createWilayahModal"><i class="fas fa-plus me-2"></i>Tambah</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Wilayah</th>
                            <th>Kode Wilayah</th>
                            <th>Cabang</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <x-modal id="createWilayahModal" title="Tambah Wilayah" route="{{ route('wilayah.store') }}" method="POST"
        primaryBtnTitle="Simpan" secondaryBtnTitle="Batal">
        <div class="mb-3">
            <label for="wilayah" class="form-label">Nama Wilayah</label>
            <input type="text" class="form-control" id="wilayah" name="wilayah" required>
        </div>

        <div class="mb-3">
            <label for="kode" class="form-label">Kode</label>
            <input type="text" class="form-control" id="kode" name="kode" required>
        </div>

        <div class="mb-3">
            <label for="ket" class="form-label">Keterangan</label>
            <input type="text" class="form-control" id="ket" name="ket" required>
        </div>

        <div class="mb-3">
            <label for="cabang" class="form-label">Cabang</label>
            <input type="text" class="form-control" id="cabang" name="cabang" required>
        </div>

        <div class="mb-3">
            <label for="center_lat" class="form-label">Center Latitude</label>
            <input type="text" class="form-control" id="center_lat" name="center_lat" required>
        </div>

        <div class="mb-3">
            <label for="center_long" class="form-label">Center Longitude</label>
            <input type="text" class="form-control" id="center_long" name="center_long" required>
        </div>
    </x-modal>
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
                        data: 'wilayah',
                        name: 'wilayah'
                    },
                    {
                        data: 'kode',
                        name: 'kode'
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
