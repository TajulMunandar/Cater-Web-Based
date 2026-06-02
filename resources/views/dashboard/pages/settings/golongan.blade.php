@extends('dashboard.partials.main')

@section('title', 'Golongan')

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Golongan', 'subtitle' => 'Kelola kategori golongan pelanggan', 'icon' => 'tags'])
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
                <div class="col">
                    <ul class="nav nav-pills user-profile-tab">
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/wilayah"><i class="fas fa-map-location-dot me-2"></i>Wilayah</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/kondisi"><i class="fas fa-gauge me-2"></i>Kondisi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/petugas"><i class="fas fa-user-tie me-2"></i>Petugas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/golongan"><i class="fas fa-tags me-2"></i>Golongan</a>
                        </li>
                    </ul>
                </div>
                <div class="col pt-3 pe-3">
                    <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#createGolonganModal"><i class="fas fa-plus me-2"></i>Tambah</button>
                </div>
            </div>

            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Tarif per m³</th>
                            <th>Biaya Admin</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <x-modal id="createGolonganModal" title="Tambah Golongan" route="{{ route('golongan.store') }}" method="POST" primaryBtnTitle="Simpan" secondaryBtnTitle="Batal">
        <div class="mb-3">
            <label for="kode" class="form-label">Kode</label>
            <input type="text" class="form-control" name="kode" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama" required>
        </div>
        <div class="mb-3">
            <label for="tarif_per_m3" class="form-label">Tarif per m³</label>
            <input type="number" class="form-control" name="tarif_per_m3" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="biaya_admin" class="form-label">Biaya Admin</label>
            <input type="number" class="form-control" name="biaya_admin" step="0.01">
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" name="keterangan" rows="2"></textarea>
        </div>
    </x-modal>

    <div id="dynamicModalContainer"></div>
@endsection

@push('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var isMobile = window.innerWidth <= 768;
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("golongan.data") }}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'kode', name: 'kode' },
                    { data: 'nama', name: 'nama' },
                    { data: 'tarif_per_m3', name: 'tarif_per_m3' },
                    { data: 'biaya_admin', name: 'biaya_admin' },
                    { data: 'keterangan', name: 'keterangan' },
                    { data: 'action', orderable: false, searchable: false }
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