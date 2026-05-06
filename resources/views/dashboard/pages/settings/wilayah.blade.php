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

        <div class="card ">
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
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/petugas"><i class="fas fa-user-tie me-2"></i>Petugas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/golongan"><i class="fas fa-tags me-2"></i>Golongan</a>
                        </li>
                    </ul>
                </div>
                <div class="col pt-3 pe-3">
                    <div class="col">
                        <button class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#createWilayahModal"><i class="fas fa-plus me-2"></i>Tambah</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Wilayah</th>
                            <th>Kode Wilayah</th>
                            <th>Cabang</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <x-modal id="createWilayahModal" title="Tambah Wilayah" route="{{ route('wilayah.store') }}" method="POST"
        primaryBtnTitle="Simpan" secondaryBtnTitle="Batal">
        <div class="mb-3">
            <label for="wilayah" class="form-label">Nama Wilayah</label>
            <input type="text" class="form-control" id="wilayah" name="wilayah" required>
        </div>

        <div class="mb-3">
            <label for="kode" class="form-label">Kode</label>
            <input type="text" class="form-control" id="kode" name="kode" required>
        </div>

        <div class="mb-3">
            <label for="ket" class="form-label">Keterangan</label>
            <input type="text" class="form-control" id="ket" name="ket" required>
        </div>

        <div class="mb-3">
            <label for="cabang" class="form-label">Cabang</label>
            <input type="text" class="form-control" id="cabang" name="cabang" required>
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
    </x-modal>
@endsection

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@push('script')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var mapCreate;
        var isMobile = window.innerWidth <= 768;
        
        function initMap() {
            // Initialize map when modal is shown
            $('#createWilayahModal').on('shown.bs.modal', function() {
                if (!mapCreate) {
                    mapCreate = L.map('mapCreate').setView([5.164880647711926, 97.10991371831535], 13);
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors',
                        maxZoom: 19,
                    }).addTo(mapCreate);
                    
                    var marker;
                    
                    mapCreate.on('click', function(e) {
                        if (marker) {
                            mapCreate.removeLayer(marker);
                        }
                        marker = L.marker(e.latlng).addTo(mapCreate);
                        document.getElementById('center_lat').value = e.latlng.lat.toFixed(6);
                        document.getElementById('center_long').value = e.latlng.lng.toFixed(6);
                    });
                }
                setTimeout(function() {
                    mapCreate.invalidateSize();
                }, 100);
            });
        }
        
        function initDataTable() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('wilayah.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'wilayah',
                        name: 'wilayah'
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'cabang',
                        name: 'cabang'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
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

            $('#myTable').on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get(`/settings/wilayah/${id}/modal-edit`, function(html) {
                    $('#dynamicModalContainer').html(html);

                    setTimeout(() => {
                        let modalEl = document.getElementById('editModal' + id);
                        if (modalEl) {
                            let modal = new bootstrap.Modal(modalEl);
                            modal.show();
                            
                            modalEl.addEventListener('shown.bs.modal', function() {
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
                                        if (marker) {
                                            mapEdit.removeLayer(marker);
                                        }
                                        marker = L.marker(e.latlng).addTo(mapEdit);
                                        document.getElementById('center_lat' + id).value = e.latlng.lat.toFixed(6);
                                        document.getElementById('center_long' + id).value = e.latlng.lng.toFixed(6);
                                    });
                                    
                                    setTimeout(function() {
                                        mapEdit.invalidateSize();
                                    }, 100);
                                }
                            });
                        }
                    }, 10);
                });
            });

            $('#myTable').on('click', '.btn-delete', function() {
                let id = $(this).data('id');
                $.get(`/settings/wilayah/${id}/modal-delete`, function(html) {
                    $('#dynamicModalContainer').html(html);

                    setTimeout(() => {
                        let modalEl = document.getElementById('deleteModal' + id);
                        if (modalEl) {
                            let modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        }
                    }, 10);
                });
            });
        }
        
        $(document).ready(function() {
            initMap();
            initDataTable();
        });
    </script>
@endpush
