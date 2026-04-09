@extends('dashboard.partials.main')

@section('content')
    <div class="row">
        <div class="col">

            <h3>Rekap Kondisi</h3>
        </div>
        <div class="col">
            <button class="btn btn-primary float-end">Tambah</button>
        </div>
    </div>
    <div class="row mt-2">

        <div class="card p-3">
            <ul class="nav nav-pills ">
                <li class="nav-item">
                    <a class="nav-link " href="/rekap/index"> Rekap Catat Meter</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/rekap/wilayah">Rekap Wilayah</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/rekap/kondisi">Rekap Kondisi</a>
                </li>
            </ul>
            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kondisi</th>
                            <th>Created At</th>
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
                ajax: '{{ route("rekap.data-kondisi") }}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_kondisi' },
                    { data: 'created_at' }
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
        });
    </script>
@endpush
