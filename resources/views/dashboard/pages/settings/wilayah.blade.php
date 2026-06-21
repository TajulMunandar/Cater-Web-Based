@extends('dashboard.partials.main')

@section('title', 'Wilayah')

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Wilayah', 'subtitle' => 'Kelola data wilayah dan rute pelanggan', 'icon' => 'map-pins'])
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

    <div class="row">
        <div class="card">
              <div class="row">
                <div class="col">
                    <ul class="nav nav-pills user-profile-tab">
                        <li class="nav-item">
                            <a class="nav-link active position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/wilayah"><i class="fas fa-map-location-dot me-2"></i>Wilayah</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/kondisi"><i class="fas fa-gauge me-2"></i>Kondisi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/petugas"><i class="fas fa-user-tie me-2"></i>Petugas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/golongan"><i class="fas fa-tags me-2"></i>Golongan</a>
                        </li>
                    </ul>
                </div>
                <div class="col pt-3 pe-3">
                    <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#createWilayahModal"><i
                            class="fas fa-plus me-2"></i> Tambah</button>

                </div>
            </div>
            <div class="card-body">
                    <table class="table" id="wilayahTable">
                        <thead>
                            <tr>
                                <th style="width:36px"></th>
                                <th style="width:50px">No</th>
                                <th>Nama Wilayah</th>
                                <th>Jumlah Rute</th>
                                <th style="width:130px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createWilayahModal" tabindex="-1" aria-labelledby="createWilayahModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('wilayah.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createWilayahModalLabel">Tambah Wilayah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="wilayah" class="form-label">Nama Wilayah</label>
                            <input type="text" class="form-control" id="wilayah" name="wilayah" required>
                        </div>
                        <div class="mb-3">
                            <label for="ket" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="ket" name="ket" required>
                        </div>
                        <div class="mb-3">
                            <label for="center_lat" class="form-label">Center Latitude</label>
                            <input type="text" class="form-control" id="center_lat" name="center_lat" readonly required>
                            <small class="text-muted">Klik pada peta untuk menentukan lokasi</small>
                        </div>
                        <div class="mb-3">
                            <label for="center_long" class="form-label">Center Longitude</label>
                            <input type="text" class="form-control" id="center_long" name="center_long" readonly required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Peta</label>
                            <div id="mapCreate" style="height: 300px; width: 100%; border: 1px solid #ddd;"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="createRuteModal" tabindex="-1" aria-labelledby="createRuteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="createRuteForm" method="POST">
                    @csrf
                    <input type="hidden" id="rute_wilayah_id" name="id_wilayah">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createRuteModalLabel">Tambah Rute</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Wilayah</label>
                            <input type="text" class="form-control" id="rute_wilayah_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="rute_name" class="form-label">Nama Rute</label>
                            <input type="text" class="form-control" id="rute_name" name="rute" required>
                        </div>
                        <div class="mb-3">
                            <label for="rute_kode" class="form-label">Kode</label>
                            <input type="text" class="form-control" id="rute_kode" name="kode">
                        </div>
                        <div class="mb-3">
                            <label for="rute_ket" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="rute_ket" name="ket">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRuteModal" tabindex="-1" aria-labelledby="editRuteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editRuteForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRuteModalLabel">Edit Rute</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_rute_name" class="form-label">Nama Rute</label>
                            <input type="text" class="form-control" id="edit_rute_name" name="rute" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_rute_kode" class="form-label">Kode</label>
                            <input type="text" class="form-control" id="edit_rute_kode" name="kode">
                        </div>
                        <div class="mb-3">
                            <label for="edit_rute_ket" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="edit_rute_ket" name="ket">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteRuteModal" tabindex="-1" aria-labelledby="deleteRuteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteRuteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteRuteModalLabel">Hapus Rute</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center py-3">
                            <div class="mb-3" style="font-size:2rem;color:var(--color-danger);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <p>Yakin ingin menghapus rute <strong id="deleteRuteName"></strong>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="dynamicModalContainer"></div>
@endsection

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        /* ── Pill-style tabs ── */
        .tab-pills {
            gap: 4px;
        }
        .tab-pills .nav-link {
            font-size: 0.82rem;
            font-weight: 500;
            padding: 0.45rem 1rem;
            border-radius: 100px;
            color: var(--color-text-secondary);
            background: transparent;
            border: 1px solid transparent;
            transition: all 0.2s;
        }
        .tab-pills .nav-link:hover {
            background: var(--color-border-light);
            color: var(--color-text);
        }
        .tab-pills .nav-link.active {
            background: var(--color-primary);
            color: #fff;
            border-color: var(--color-primary);
        }
        .tab-pills .nav-link i {
            font-size: 0.85rem;
        }

        /* ── Expandable rows ── */
        .expand-toggle {
            cursor: pointer;
            transition: transform 0.2s;
            font-size: 0.85rem;
            color: var(--color-text-muted);
            background: none;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .expand-toggle:hover {
            background: var(--color-border-light);
            color: var(--color-primary);
        }
        .expand-toggle.expanded {
            transform: rotate(90deg);
            color: var(--color-primary);
        }
        tr.parent td:first-child {
            position: relative;
        }

        /* ── Child row content ── */
        .rute-child-content {
            padding: 12px 12px 12px 48px;
        }
        .rute-child-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .rute-child-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--color-text);
        }
        .rute-child-title strong {
            color: var(--color-primary);
        }

        .rute-child-table {
            margin: 0;
            font-size: 0.82rem;
        }
        .rute-child-table thead th {
            background: #f8fafc;
            border-bottom: 2px solid var(--color-border);
            color: var(--color-text-secondary);
            font-weight: 600;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 8px 12px;
        }
        .rute-child-table tbody td {
            padding: 8px 12px;
            border-bottom: 1px solid var(--color-border-light);
            vertical-align: middle;
        }
        .rute-child-table tbody tr:hover {
            background: #fafbfc;
        }
        .rute-child-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* ── Empty state ── */
        .rute-empty-state {
            text-align: center;
            padding: 24px 16px;
        }
        .rute-empty-state .empty-icon {
            font-size: 2rem;
            color: var(--color-text-muted);
            margin-bottom: 8px;
            opacity: 0.4;
        }
        .rute-empty-state .empty-text {
            font-size: 0.85rem;
            color: var(--color-text-secondary);
            margin-bottom: 4px;
        }
        .rute-empty-state .empty-hint {
            font-size: 0.78rem;
            color: var(--color-text-muted);
        }

        /* ── Rute count badge ── */
        .badge-rute-count {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 100px;
            background: #eff6ff;
            color: var(--color-primary);
        }
        .badge-rute-count.badge-empty {
            background: #f8fafc;
            color: var(--color-text-muted);
            font-weight: 400;
        }

        /* ── Action buttons ── */
        .btn-action, .btn-action-rute {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px;
            font-size: 0.78rem;
            font-weight: 500;
            border-radius: 6px;
            border: 1px solid var(--color-border);
            background: #fff;
            color: var(--color-text-secondary);
            transition: all 0.15s;
            cursor: pointer;
        }
        .btn-action:hover, .btn-action-rute:hover {
            background: var(--color-border-light);
        }
        .btn-action.btn-edit, .btn-action-rute.btn-edit-rute {
            color: #d97706;
            border-color: #fde68a;
        }
        .btn-action.btn-edit:hover, .btn-action-rute.btn-edit-rute:hover {
            background: #fffbeb;
            border-color: #f59e0b;
        }
        .btn-action.btn-delete, .btn-action-rute.btn-delete-rute {
            color: #dc2626;
            border-color: #fecaca;
        }
        .btn-action.btn-delete:hover, .btn-action-rute.btn-delete-rute:hover {
            background: #fef2f2;
            border-color: #ef4444;
        }
        .btn-action i, .btn-action-rute i {
            font-size: 0.82rem;
        }

        /* ── Wilayah table ── */
        #wilayahTable thead th {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--color-text-secondary);
            background: #f8fafc;
            border-bottom: 2px solid var(--color-border);
            padding: 10px 12px;
        }
        #wilayahTable tbody td {
            padding: 12px;
            vertical-align: middle;
            border-bottom: 1px solid var(--color-border-light);
        }
        #wilayahTable tbody tr:hover {
            background: #fafbfc;
        }

        /* ── DataTable search styling ── */
        .dataTables_filter input {
            border: 1px solid var(--color-border);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.82rem;
            outline: none;
            transition: border-color 0.2s;
        }
        .dataTables_filter input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        /* ── Modal header style ── */
        .modal-header {
            background: var(--bg-card);
            border-bottom: 1px solid var(--color-border);
        }
        .modal-header .modal-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text);
        }
        .modal-header .btn-close:focus {
            box-shadow: none;
        }

        /* ── Responsive card list ── */
        @media (max-width: 768px) {
            #wilayahTable_wrapper .dataTables_length,
            #wilayahTable_wrapper .dataTables_filter {
                text-align: left;
                float: none;
                margin-bottom: 8px;
            }
            #wilayahTable_wrapper .dataTables_filter input {
                width: 100%;
            }
            .table-responsive table {
                min-width: 600px;
            }
        }
    </style>
@endpush

@push('script')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var isMobile = window.innerWidth <= 768;
        var wilayahTable;
        var expandedRowMap = {};

        function initMap() {
            $('#createWilayahModal').on('shown.bs.modal', function() {
                var container = document.getElementById('mapCreate');
                if (!container) return;

                if (!window.mapCreate || typeof window.mapCreate.invalidateSize !== 'function') {
                    window.mapCreate = L.map(container).setView([5.164880647711926, 97.10991371831535], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors',
                        maxZoom: 19,
                    }).addTo(window.mapCreate);
                    var marker;
                    window.mapCreate.on('click', function(e) {
                        if (marker) window.mapCreate.removeLayer(marker);
                        marker = L.marker(e.latlng).addTo(window.mapCreate);
                        document.getElementById('center_lat').value = e.latlng.lat.toFixed(6);
                        document.getElementById('center_long').value = e.latlng.lng.toFixed(6);
                    });
                }
                setTimeout(function() { if (window.mapCreate && typeof window.mapCreate.invalidateSize === 'function') window.mapCreate.invalidateSize(); }, 100);
            });
        }

        // ── Wilayah DataTable ──
        function initWilayahTable() {
            wilayahTable = $('#wilayahTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('wilayah.data') }}',
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function() {
                            return '<button class="expand-toggle"><i class="fas fa-chevron-right"></i></button>';
                        },
                        width: '36px'
                    },
                    { data: 'DT_RowIndex', orderable: false, searchable: false, width: '50px' },
                    { data: 'wilayah', name: 'wilayah' },
                    { data: 'rute_count', name: 'rute_count', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false, width: '130px' }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Cari wilayah...",
                    decimal: ",",
                    thousands: ".",
                    emptyTable: "Belum ada data wilayah",
                    zeroRecords: "Tidak ditemukan wilayah yang cocok",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                },
                scrollX: isMobile,
                drawCallback: function() {
                    // Reset expand states on table redraw
                    expandedRowMap = {};
                }
            });

            // ── Expand/Collapse on chevron click ──
            $('#wilayahTable tbody').on('click', '.expand-toggle', function() {
                var tr = $(this).closest('tr');
                var row = wilayahTable.row(tr);
                var rowData = row.data();

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                    $(this).removeClass('expanded');
                } else {
                    var wilayahId = rowData.id;
                    var $toggle = $(this);
                    var $cell = $(this).closest('td');

                    $cell.html('<div class="expand-spinner" style="text-align:center;padding:4px;"><div class="spinner-border spinner-border-sm" role="status"></div></div>');

                    $.get('/settings/rute/list/' + wilayahId, function(html) {
                        row.child(html).show();
                        tr.addClass('shown');
                        $toggle.addClass('expanded');

                        // Restore chevron in cell
                        $cell.html('<button class="expand-toggle expanded"><i class="fas fa-chevron-right"></i></button>');

                        // Initialize edit/delete buttons for child row rutes
                        initChildRuteActions();
                    });
                }
            });

            $('.dataTables_filter input[type="search"]').css({ "marginBottom": "10px" });
        }

        // ── Child row rute actions ──
        function initChildRuteActions() {
            $('.btn-edit-rute').off('click').on('click', function() {
                var id = $(this).data('id');
                var rute = $(this).data('rute');
                var kode = $(this).data('kode');
                var ket = $(this).data('ket');
                $('#editRuteForm').attr('action', '/settings/rute/' + id);
                $('#edit_rute_name').val(rute);
                $('#edit_rute_kode').val(kode);
                $('#edit_rute_ket').val(ket);
                var modal = new bootstrap.Modal(document.getElementById('editRuteModal'));
                modal.show();
            });

            $('.btn-delete-rute').off('click').on('click', function() {
                var id = $(this).data('id');
                var rute = $(this).data('rute');
                $('#deleteRuteForm').attr('action', '/settings/rute/' + id);
                $('#deleteRuteName').text(rute);
                var modal = new bootstrap.Modal(document.getElementById('deleteRuteModal'));
                modal.show();
            });
        }

        // ── Open Add Rute modal from child row ──
        function openAddRute(wilayahId) {
            $.get('/settings/wilayah/' + wilayahId, function(data) {
                $('#createRuteForm').attr('action', '{{ route('rute.store') }}');
                $('#rute_wilayah_id').val(wilayahId);
                $('#rute_wilayah_name').val(data.wilayah);
                $('#rute_name').val('');
                $('#rute_ket').val('');
                var modal = new bootstrap.Modal(document.getElementById('createRuteModal'));
                modal.show();
            });
        }

        // ── Wilayah edit/delete (using inline modals with data-bs-toggle) ──
        function initWilayahEditMap() {
            $(document).on('shown.bs.modal', '[id^="editModal"]', function() {
                var id = this.id.replace('editModal', '');
                var mapDiv = document.getElementById('mapEdit' + id);
                if (mapDiv && !mapDiv._leaflet_id) {
                    var lat = parseFloat(mapDiv.dataset.lat) || 5.164880647711926;
                    var lng = parseFloat(mapDiv.dataset.lng) || 97.10991371831535;
                    var mapEdit = L.map('mapEdit' + id).setView([lat, lng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors',
                        maxZoom: 19,
                    }).addTo(mapEdit);
                    var marker = L.marker([lat, lng]).addTo(mapEdit);
                    mapEdit.on('click', function(e) {
                        if (marker) mapEdit.removeLayer(marker);
                        marker = L.marker(e.latlng).addTo(mapEdit);
                        document.getElementById('center_lat' + id).value = e.latlng.lat.toFixed(6);
                        document.getElementById('center_long' + id).value = e.latlng.lng.toFixed(6);
                    });
                    setTimeout(function() { mapEdit.invalidateSize(); }, 100);
                }
            });
        }

        $(document).ready(function() {
            initMap();
            initWilayahTable();
            initWilayahEditMap();
        });
    </script>
@endpush
