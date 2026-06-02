@extends('dashboard.partials.main')

@section('title', 'Pelanggan')

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Pelanggan', 'subtitle' => 'Kelola data pelanggan sistem', 'icon' => 'users'])
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
                    <a href="{{ route('pelanggan.baru.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                <table class="table" id="myTable" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No Sambu</th>
                            <th>No Kontrol</th>
                            <th>Wilayah</th>
                            <th>Golongan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for customer details -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary border-0">
                    <h5 class="modal-title text-white" id="detailModalLabel">
                        <i class="fas fa-user me-2"></i>Detail Pelanggan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Photo Section -->
                        <div class="col-md-5">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center p-4 text-center">
                                    <div id="detail-foto-container" class="w-100">
                                        <img id="detail-foto" src="" alt="Foto Rumah"
                                            class="img-fluid rounded shadow-sm"
                                            style="max-height:280px;width:100%;object-fit:cover;display:none;"
                                            onerror="this.style.display='none';document.getElementById('detail-foto-placeholder').style.display='flex';">
                                        <div id="detail-foto-placeholder"
                                            style="width:100%;height:220px;background:#e9ecef;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;">
                                            <i class="fas fa-image text-muted" style="font-size:48px;opacity:0.5;"></i>
                                            <span class="text-muted small">Belum ada foto rumah</span>
                                        </div>
                                    </div>
                                    <div id="detail-foto-nama-container" class="mt-2 small text-muted" style="display:none;">
                                        <i class="fas fa-file-image me-1"></i>
                                        <span id="detail-foto-nama" class="fw-medium"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Info Section -->
                        <div class="col-md-7">
                            <div class="card border-0 h-100">
                                <div class="card-body p-0">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-3" style="background:#f8fafc;">
                                                <small class="text-muted text-uppercase fw-semibold" style="font-size:10px;letter-spacing:0.5px;">No Sambu</small>
                                                <p class="mb-0 fw-bold mt-1" id="detail-no-sambu" style="font-size:15px;">-</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-3" style="background:#f8fafc;">
                                                <small class="text-muted text-uppercase fw-semibold" style="font-size:10px;letter-spacing:0.5px;">No Kontrol</small>
                                                <p class="mb-0 fw-bold mt-1" id="detail-no-kontrol" style="font-size:15px;">-</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-3" style="background:#f8fafc;">
                                                <small class="text-muted text-uppercase fw-semibold" style="font-size:10px;letter-spacing:0.5px;">Nama</small>
                                                <p class="mb-0 fw-bold mt-1" id="detail-nama" style="font-size:15px;">-</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-3" style="background:#f8fafc;">
                                                <small class="text-muted text-uppercase fw-semibold" style="font-size:10px;letter-spacing:0.5px;">Telepon</small>
                                                <p class="mb-0 fw-bold mt-1" id="detail-telepon" style="font-size:15px;">-</p>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="p-3 rounded-3" style="background:#f8fafc;">
                                                <small class="text-muted text-uppercase fw-semibold" style="font-size:10px;letter-spacing:0.5px;">Alamat</small>
                                                <p class="mb-0 fw-bold mt-1" id="detail-alamat" style="font-size:15px;">-</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="p-3 rounded-3" style="background:#f8fafc;">
                                                <small class="text-muted text-uppercase fw-semibold" style="font-size:10px;letter-spacing:0.5px;">Wilayah</small>
                                                <p class="mb-0 fw-bold mt-1" id="detail-wilayah" style="font-size:15px;">-</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="p-3 rounded-3" style="background:#f8fafc;">
                                                <small class="text-muted text-uppercase fw-semibold" style="font-size:10px;letter-spacing:0.5px;">Golongan</small>
                                                <p class="mb-0 fw-bold mt-1" id="detail-golongan" style="font-size:15px;">-</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="p-3 rounded-3" style="background:#f8fafc;">
                                                <small class="text-muted text-uppercase fw-semibold" style="font-size:10px;letter-spacing:0.5px;">Status</small>
                                                <p class="mb-0 fw-bold mt-1" id="detail-status" style="font-size:15px;">-</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Tutup
                    </button>
                    <a href="#" id="detail-link" class="btn btn-primary px-4">
                        <i class="fas fa-external-link-alt me-1"></i>Lihat Detail Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('head')
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}">
    <style>
        #myTable tbody tr:hover {
            background-color: #f8f9fa !important;
            cursor: pointer;
        }

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

        .modal-content {
            border-radius: 16px;
            overflow: hidden;
        }
        .modal-header {
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }
        .modal-header .modal-title {
            font-weight: 600;
            font-size: 1.1rem;
        }
        .modal-body {
            padding: 1.5rem !important;
        }
        .modal-footer {
            border-top: none;
            padding: 0 1.5rem 1.5rem;
        }
        #detail-foto {
            transition: opacity 0.3s ease;
            border-radius: 8px;
        }
        #detail-foto-container {
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #myTable td:nth-child(5) {
            min-width: 160px;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("pelanggan.index") }}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'foto', name: 'foto', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'alamat', name: 'alamat' },
                    { data: 'no_sambu', name: 'no_sambu' },
                    { data: 'no_kontrol', name: 'no_kontrol' },
                    { data: 'wilayah', name: 'wilayah' },
                    { data: 'golongan', name: 'golongan' },
                    { data: 'status_badge', name: 'status', searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search...",
                    "decimal": ",",
                    "thousands": ".",
                },
            });
            // Handle row click to show customer details (skip when clicking action button)
            $('#myTable tbody').on('click', 'tr', function(e) {
                if ($(e.target).closest('.btn-toggle-status').length) return;

                let data = $('#myTable').DataTable().row(this).data();

                if (data) {
                    // Populate modal with customer data
                    $('#detail-no-sambu').text(data.no_sambu || '-');
                    $('#detail-no-kontrol').text(data.no_kontrol || '-');
                    $('#detail-nama').text(data.nama || '-');
                    $('#detail-telepon').text(data.telepon || '-');
                    $('#detail-alamat').text(data.alamat || '-');
                    $('#detail-wilayah').text(data.wilayah || '-');
                    $('#detail-golongan').text(data.golongan || '-');
                    $('#detail-status').text(data.status_text === 'aktif' ? 'Aktif' : 'Non-Aktif');

                    // Set link to full detail page
                    $('#detail-link').attr('href', '{{ url("pelanggan") }}/' + data.id);

                    // Populate photo
                    if (data.foto_url) {
                        $('#detail-foto').attr('src', data.foto_url).show();
                        $('#detail-foto-placeholder').hide();
                        $('#detail-foto-nama').text(data.foto_nama || '');
                        $('#detail-foto-nama-container').show();
                    } else {
                        $('#detail-foto').hide();
                        $('#detail-foto-placeholder').show();
                        $('#detail-foto-nama-container').hide();
                    }

                    // Show modal
                    $('#detailModal').modal('show');
                }
            });

            // Handle toggle status button click
            $(document).on('click', '.btn-toggle-status', function() {
                let btn = $(this);
                let id = btn.data('id');
                let nama = btn.data('nama');
                let statusSaatIni = btn.data('status');
                let jadiAktif = statusSaatIni === 'non-aktif';
                let title = jadiAktif ? 'Aktifkan Pelanggan' : 'Non-Aktifkan Pelanggan';
                let icon = jadiAktif ? 'question' : 'warning';
                let confirmBtn = jadiAktif ? 'Ya, Aktifkan' : 'Ya, Non-Aktifkan';
                let confirmBtnClass = jadiAktif ? 'btn-success' : 'btn-danger';

                Swal.fire({
                    title: title,
                    html: `Yakin ingin ${jadiAktif ? 'mengaktifkan' : 'menonaktifkan'} pelanggan <strong>${nama}</strong>?`,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: confirmBtn,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: jadiAktif ? '#059669' : '#DC2626',
                    cancelButtonColor: '#6B7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url("pelanggan") }}/' + id + '/toggle-status',
                            type: 'PATCH',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    $('#myTable').DataTable().ajax.reload(null, false);
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Gagal mengubah status pelanggan.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // Add cursor pointer to table rows to indicate they're clickable
            $('#myTable tbody').css('cursor', 'pointer');


        });
    </script>
@endpush
