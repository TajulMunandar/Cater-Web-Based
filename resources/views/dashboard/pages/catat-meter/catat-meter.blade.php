@extends('dashboard.partials.main')

@section('content')
    <div class="row">
        <div class="col">

            <h3>Catat Meter</h3>
        </div>
        <div class="col">
            <button class="btn btn-primary float-end">Tambah</button>
        </div>
    </div>
    <div class="row mt-2">

        <div class="card p-3">
            <ul class="nav nav-pills ">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('cater.index') }}">Catat Meter</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/cater/tidak-terdaftar">Catat Meter Tidak Terdaftar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/cater/urutan">Urutan Catat Meter</a>
                </li>
            </ul>
            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Petugas</th>
                            <th>Kondisi</th>
                            <th>Waktu</th>
                            <th>Stand</th>
                            <th>GPS</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
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
                ajax: '{{ route("cater.data") }}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'pelanggan' },
                    { data: 'petugas' },
                    { data: 'kondisi' },
                    { data: 'waktu' },
                    { data: 'stand' },
                    { data: 'gps' },
                    { data: 'status' },
                    { data: 'action', orderable: false, searchable: false }
                ],
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search...",
                    "decimal": ",",
                    "thousands": ".",
                },
                "scrollX": true,
            });

            $('.dataTables_filter input[type="search"]').css({
                "marginBottom": "10px"
            });
        });
    </script>
@endpush
