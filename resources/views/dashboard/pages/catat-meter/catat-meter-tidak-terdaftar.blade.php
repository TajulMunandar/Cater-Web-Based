@extends('dashboard.partials.main')

@section('title', 'Tidak Terdaftar')

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Catat Meter Tidak Terdaftar', 'subtitle' => 'Pencatatan meter pelanggan tidak terdaftar', 'icon' => 'clipboard-x'])

    <div class="row mt-2">
        <div class="card p-3">
            <ul class="nav nav-pills ">
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('cater.index') }}">Catat Meter</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('cater.tidak-terdaftar') }}">Catat Meter Tidak Terdaftar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cater.urutan') }}">Urutan Catat Meter</a>
                </li>
            </ul>
            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Photo</th>
                            <th>Stand Meter</th>
                            <th>Waktu</th>
                            <th>Nama</th>
                            <th>No Kontrol</th>
                            <th>No Sambung</th>
                            <th>Wilayah</th>
                            <th>Kondisi Meter</th>
                            <th>Petugas</th>
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
                ajax: '{{ route("cater.data-tidak-terdaftar") }}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'foto', orderable: false, searchable: false },
                    { data: 'stand' },
                    { data: 'waktu' },
                    { data: 'pelanggan' },
                    { data: 'no_kontrol' },
                    { data: 'no_sambung' },
                    { data: 'wilayah' },
                    { data: 'kondisi' },
                    { data: 'petugas' },
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
