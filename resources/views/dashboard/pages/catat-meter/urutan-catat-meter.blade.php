@extends('dashboard.partials.main')

@section('title', 'Urutan Catat Meter')

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Urutan Catat Meter', 'subtitle' => 'Urutan pencatatan meter pelanggan', 'icon' => 'sort-ascending'])

    <div class="row mt-2">
        <div class="card p-3">
            <ul class="nav nav-pills ">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cater.index') }}">Catat Meter</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cater.tidak-terdaftar') }}">Catat Meter Tidak Terdaftar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('cater.urutan') }}">Urutan Catat Meter</a>
                </li>
            </ul>
            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>No Sambung</th>
                            <th>No Kontrol</th>
                            <th>Alamat</th>
                            <th>Wilayah</th>
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
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("cater.data-urutan") }}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama' },
                    { data: 'no_sambu' },
                    { data: 'no_kontrol' },
                    { data: 'alamat' },
                    { data: 'wilayah' },
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
