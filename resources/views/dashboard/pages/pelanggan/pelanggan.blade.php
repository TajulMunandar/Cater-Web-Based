@extends('dashboard.partials.main')

@section('content')
    <div class="row mt-2">
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
        <div class="card ">
            <div class="row">
                <div class="col col-lg-10">
                    <ul class="nav nav-pills user-profile-tab">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/pelanggan"><i class="fas fa-users me-2"></i>Data Pelanggan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
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
                    <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#createPelangganModal"><i class="fas fa-plus me-2"></i>Tambah</button>
                </div>
            </div>

            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Sambu</th>
                            <th>No Kontrol</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Wilayah</th>
                            <th>Golongan</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <x-modal id="createPelangganModal" title="Tambah Pelanggan" route="{{ route('pelanggan.store') }}" method="POST" modalSize="modal-lg" primaryBtnTitle="Simpan">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="no_sambu" class="form-label">No Sambu</label>
                    <input type="text" class="form-control" name="no_sambu" required>
                </div>
                <div class="mb-3">
                    <label for="no_kontrol" class="form-label">No Kontrol</label>
                    <input type="text" class="form-control" name="no_kontrol" required>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" name="nama" required>
                </div>
                <div class="mb-3">
                    <label for="telepon" class="form-label">Telepon</label>
                    <input type="text" class="form-control" name="telepon">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" name="alamat" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="id_wilayah" class="form-label">Wilayah</label>
                    <select class="form-select" name="id_wilayah">
                        <option value="">Pilih Wilayah</option>
                        @foreach(\App\Models\Wilayah::all() as $w)
                            <option value="{{ $w->id }}">{{ $w->wilayah }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_gol" class="form-label">Golongan</label>
                    <select class="form-select" name="id_gol">
                        <option value="">Pilih Golongan</option>
                        @foreach(\App\Models\Golongan::all() as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="non-aktif">Non-Aktif</option>
                    </select>
                </div>
            </div>
        </div>
    </x-modal>

    <!-- Dynamic container for edit/delete modals -->
    <div id="dynamicModalContainer"></div>
@endsection

@push('script')
    <script>
        var isMobile = window.innerWidth <= 768;
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("pelanggan.index") }}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'no_sambu', name: 'no_sambu' },
                    { data: 'no_kontrol', name: 'no_kontrol' },
                    { data: 'nama', name: 'nama' },
                    { data: 'alamat', name: 'alamat' },
                    { data: 'wilayah', name: 'wilayah' },
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
                "scrollX": isMobile,
            });

            $('.dataTables_filter input[type="search"]').css({
                "marginBottom": "10px"
            });

            // Handle edit button click
            $('#myTable').on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $('#editModal' + id).modal('show');
            });

            // Handle delete button click
            $('#myTable').on('click', '.btn-delete', function() {
                let id = $(this).data('id');
                $('#deleteModal' + id).modal('show');
            });
        });
    </script>
@endpush
