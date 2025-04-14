@extends('dashboard.partials.main')

@section('content')
    <div class="row">
        <div class="col">

            <h3>Urutan Catat Meter</h3>
        </div>
        <div class="col">
            <button class="btn btn-primary float-end">Tambah</button>
        </div>
    </div>
    <div class="row mt-2">

        <div class="card p-3">
            <ul class="nav nav-pills ">
                <li class="nav-item">
                    <a class="nav-link" href="/cater/index">Catat Meter</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/cater/tidak-terdaftar">Catat Meter Tidak Terdaftar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/cater/urutan">Urutan Catat Meter</a>
                </li>
            </ul>
            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>Jl. Raya</td>
                            <td>08123456789</td>
                            <td>
                                <button class="btn btn-warning">Edit</button>
                                <button class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                    </tbody>
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
        });
    </script>
@endpush
