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
                            <a class="nav-link active position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/petugas"><i class="fas fa-user-tie me-2"></i>Petugas</a>
                        </li>
                    </ul>
                </div>
                <div class="col pt-3 pe-3">
                    <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#createPetugasModal"><i
                            class="fas fa-plus me-2"></i> Tambah</button>

                </div>
            </div>
            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Photo</th>
                            <th>Nama Lengkap</th>
                            <th>NIP</th>
                            <th>NO Hp</th>
                            <th>Email</th>
                            <th>Jenis Pekerjaan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal id="createPetugasModal" title="Tambah Petugas" route="{{ route('petugas.store') }}" method="POST"
        primaryBtnTitle="Simpan" secondaryBtnTitle="Batal" modalSize="modal-lg">

        <div class="row mb-3 align-items-center">
            <div class="col-md-12" id="photoInputCol">
                <div class="mb-3">
                    <label for="photo" class="form-label">Foto (opsional)</label>
                    <input type="file" class="form-control" name="photo" id="photo" accept="image/*"
                        onchange="previewPhoto(event)">
                </div>
            </div>
            <div class="col-md-4 d-none" id="photoPreviewCol">
                <div class="mb-3 position-relative d-inline-block">
                    <img id="photoPreview" src="#" alt="Preview Foto" class="img-thumbnail rounded-circle"
                        style="width: 150px; height: 150px; object-fit: cover; object-position: center;">

                    <button type="button"
                        class="btn btn-danger rounded-circle border border-dark position-absolute top-0 end-0 m-1 d-flex justify-content-center align-items-center"
                        style="width: 30px; height: 30px; font-size: 15px; line-height: 1; padding: 0;"
                        onclick="removePreview()">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" name="nama" id="nama" required>
                </div>

                <div class="mb-3">
                    <label for="nip" class="form-label">NIP</label>
                    <input type="text" class="form-control" name="nip" id="nip" required>
                </div>

                <div class="mb-3">
                    <label for="no_hp1" class="form-label">No HP 1</label>
                    <input type="text" class="form-control" name="no_hp1" id="no_hp1" required>
                </div>

                <div class="mb-3">
                    <label for="no_hp2" class="form-label">No HP 2 (opsional)</label>
                    <input type="text" class="form-control" name="no_hp2" id="no_hp2">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>

                <div class="mb-3">
                    <label for="tipe_pekerjaan" class="form-label">Tipe Pekerjaan</label>
                    <input type="text" class="form-control" name="tipe_pekerjaan" id="tipe_pekerjaan" required>
                </div>

                <div class="mb-3">
                    <label for="level" class="form-label">Level</label>
                    <select name="level" id="level" class="form-select" required>
                        <option value="">-- Pilih Level --</option>
                        <option value="0">Admin</option>
                        <option value="1">Petugas</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jenis_pekerjaan" class="form-label">Jenis Pekerjaan</label>
                    <input type="text" class="form-control" name="jenis_pekerjaan" id="jenis_pekerjaan" required>
                </div>
            </div>
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
                ajax: '{{ route('petugas.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'photo',
                        name: 'photo'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'nip',
                        name: 'nip'
                    },
                    {
                        data: 'no_hp1',
                        name: 'no_hp1'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'jenis_pekerjaan',
                        name: 'jenis_pekerjaan'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [{
                    targets: -1, // kolom terakhir (action)
                    width: '120px' // atau sesuaikan lebih besar jika perlu
                }],
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search...",
                    "decimal": ",",
                    "thousands": ".",
                },
                "scrollX": isMobile,
            });

            $('#myTable').on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get(`/settings/petugas/${id}/modal-edit`, function(html) {
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
                $.get(`/settings/petugas/${id}/modal-delete`, function(html) {
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

        function previewPhoto(event) {
            const input = event.target;
            const preview = document.getElementById('photoPreview');
            const previewCol = document.getElementById('photoPreviewCol');
            const inputCol = document.getElementById('photoInputCol');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;

                    previewCol.classList.remove('d-none');
                    setTimeout(() => previewCol.classList.add('show'), 10);

                    inputCol.classList.remove('col-md-12');
                    inputCol.classList.add('col-md-8');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePreview() {
            const preview = document.getElementById('photoPreview');
            const previewCol = document.getElementById('photoPreviewCol');
            const inputCol = document.getElementById('photoInputCol');
            const fileInput = document.getElementById('photo');

            // Hide preview with animation
            previewCol.classList.remove('show');
            setTimeout(() => previewCol.classList.add('d-none'), 500); // match transition

            // Reset preview image
            preview.src = '#';

            // Reset file input
            fileInput.value = "";

            // Reset column width
            inputCol.classList.remove('col-md-8');
            inputCol.classList.add('col-md-12');
        }

        function previewPhoto1(event, id) {
            const input = event.target;
            const preview = document.getElementById('photoPreview1' + id);
            const previewCol = document.getElementById('photoPreviewCol1' + id);
            const inputCol = document.getElementById('photoInputCol1' + id);

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;

                    previewCol.classList.remove('d-none');
                    setTimeout(() => previewCol.classList.add('show'), 10);

                    if (inputCol) {
                        inputCol.classList.remove('col-md-12');
                        inputCol.classList.add('col-md-8');
                    }
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePreview1(id) {
            const preview = document.getElementById('photoPreview1' + id);
            const previewCol = document.getElementById('photoPreviewCol1' + id);
            const inputCol = document.getElementById('photoInputCol1' + id);
            const fileInput = document.getElementById('photo1' + id);

            // Hide preview with animation
            previewCol.classList.remove('show');
            setTimeout(() => previewCol.classList.add('d-none'), 500); // match transition

            // Reset preview image
            preview.src = '#';

            // Reset file input
            fileInput.value = "";

            // Reset column width
            if (inputCol) {
                inputCol.classList.remove('col-md-8');
                inputCol.classList.add('col-md-12');
            }
        }
    </script>
@endpush

@push('head')
    <style>
        #photoPreviewCol {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: opacity 0.5s ease, max-height 0.5s ease;
        }

        /* Saat tampil */
        #photoPreviewCol.show {
            opacity: 1;
            max-height: 200px;
            /* atau sesuaikan tinggi kontennya */
            transition: opacity 0.5s ease, max-height 0.5s ease;
        }

        [id^="photoPreviewCol1"] {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: opacity 0.5s ease, max-height 0.5s ease;
        }

        [id^="photoPreviewCol1"].show {
            opacity: 1;
            max-height: 200px;
            /* Atur sesuai tinggi konten */
        }
    </style>
@endpush
