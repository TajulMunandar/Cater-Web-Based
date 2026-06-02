@extends('dashboard.partials.main')

@section('title', 'Pelanggan Baru')

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Pelanggan Baru', 'subtitle' => 'Kelola data pelanggan baru sistem', 'icon' => 'user-plus'])
    <div class="row">
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
        <div class="card">
            <div class="row">
                <div class="col col-lg-10">
                    <ul class="nav nav-pills user-profile-tab">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/pelanggan"><i class="fas fa-users me-2"></i>Data Pelanggan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/pelanggan/baru"><i class="fas fa-user me-2"></i>Data Pelanggan Baru</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/pelanggan/peta"><i class="fas fa-map me-2"></i>Peta Pelanggan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                aria-current="page" href="/pelanggan/dsml"><i class="fas fa-list me-2"></i>DSML</a>
                        </li>
                    </ul>
                </div>
                <div class="col pt-3 pe-3">
                    <a href="{{ route('pelanggan.baru.create') }}" class="btn btn-primary float-end"><i
                            class="fas fa-plus me-2"></i>Tambah</a>
                </div>
            </div>

            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No Sambung</th>
                            <th>No Kontrol</th>
                            <th>Golongan</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Global Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModalGlobal" tabindex="-1" aria-labelledby="deleteModalGlobalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-dark">
                        <h5 class="modal-title text-white" id="deleteModalGlobalLabel">Hapus Pelanggan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus pelanggan <strong id="deleteNama"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <style>
        .dataTables_processing {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            color: #495057 !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            padding: 12px 20px !important;
            z-index: 1000 !important;
        }
    </style>
@endpush

@push('script')
    <script>
        var isMobile = window.innerWidth <= 768;
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("pelanggan.baru.index") }}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'alamat', name: 'alamat' },
                    { data: 'no_sambu', name: 'no_sambu' },
                    { data: 'no_kontrol', name: 'no_kontrol' },
                    { data: 'golongan', name: 'golongan' },
                    { data: 'status_badge', name: 'status', searchable: false },
                    { data: 'action', orderable: false, searchable: false }
                ],
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search...",
                    "decimal": ",",
                    "thousands": ".",
                },
            });

            $('.dataTables_filter input[type="search"]').css({
                "marginBottom": "10px"
            });

            // Handle delete button click via global modal
            $(document).on('click', '.btn-delete', function() {
                var btn = $(this);
                $('#deleteNama').text(btn.data('nama'));
                $('#deleteForm').attr('action', btn.data('url'));
                new bootstrap.Modal(document.getElementById('deleteModalGlobal')).show();
            });
        });
    </script>
@endpush
