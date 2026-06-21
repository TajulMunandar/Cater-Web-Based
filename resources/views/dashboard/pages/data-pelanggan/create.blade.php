@extends('dashboard.partials.main')

@section('title', 'Tambah Pelanggan')

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Tambah Pelanggan', 'subtitle' => 'Buat data pelanggan baru', 'icon' => 'plus'])
    <div class="row">
        <div class="col">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <div id="loadingOverlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.85); z-index: 9999;">
        <div style="display: flex; align-items: center; justify-content: center; height: 100%;">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 fw-bold fs-5">Memproses data...</p>
            </div>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('pelanggan.baru.store') }}" method="POST" enctype="multipart/form-data" id="mainForm">
            @csrf

            {{-- Step Indicator --}}
            <div class="card-body pb-0">
                <div class="step-indicator">
                    <div class="step-item active" data-step="1">
                        <div class="step-circle">1</div>
                        <div class="step-label">Identitas</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="2">
                        <div class="step-circle">2</div>
                        <div class="step-label">Penempatan</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="3">
                        <div class="step-circle">3</div>
                        <div class="step-label">Data Meter</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="4">
                        <div class="step-circle">4</div>
                        <div class="step-label">Lokasi &amp; Foto</div>
                    </div>
                </div>
                <div class="step-progress mt-3">
                    <div class="step-progress-bar" id="progressBar" style="width: 25%;"></div>
                </div>
            </div>

            {{-- Step 1: Identitas Pelanggan --}}
            <div class="card-body step-content" id="step1">
                <h5 class="card-title d-flex align-items-center gap-2 mb-0">
                    <span class="step-icon"><i class="fas fa-user"></i></span>
                    Data Pelanggan
                </h5>
                <p class="text-muted small mb-4">Isi informasi dasar pelanggan</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" placeholder="John Doe" value="{{ old('nama') }}" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">No Sambung <span class="text-danger">*</span></label>
                            <input type="text" name="no_sambu" class="form-control" placeholder="Contoh: SR-001" value="{{ old('no_sambu') }}" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">No Telepon</label>
                            <input type="text" name="telepon" class="form-control" placeholder="08123456789" value="{{ old('telepon') }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <input type="text" name="type" class="form-control" placeholder="Contoh: Rumah Tangga" value="{{ old('type') }}" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 2: Penempatan & Status --}}
            <div class="card-body step-content d-none" id="step2">
                <h5 class="card-title d-flex align-items-center gap-2 mb-0">
                    <span class="step-icon"><i class="fas fa-map-pin"></i></span>
                    Penempatan &amp; Status Pelanggan
                </h5>
                <p class="text-muted small mb-4">Tentukan rute, golongan, dan status pelanggan</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Rute</label>
                            <select class="form-select" name="id_rute">
                                <option value="">--Pilih Rute--</option>
                                @foreach ($rutes as $rute)
                                    <option value="{{ $rute->id }}" {{ old('id_rute') == $rute->id ? 'selected' : '' }}>{{ $rute->rute }} ({{ $rute->wilayah?->wilayah ?? '-' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Golongan</label>
                            <select class="form-select" name="id_gol">
                                <option value="">--Pilih Golongan--</option>
                                @foreach ($golongans as $golongan)
                                    <option value="{{ $golongan->id }}" {{ old('id_gol') == $golongan->id ? 'selected' : '' }}>{{ $golongan->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="non-aktif" {{ old('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 3: Data Teknis Meter --}}
            <div class="card-body step-content d-none" id="step3">
                <h5 class="card-title d-flex align-items-center gap-2 mb-0">
                    <span class="step-icon"><i class="fas fa-gauge"></i></span>
                    Data Teknis Meter
                </h5>
                <p class="text-muted small mb-4">Lengkapi informasi pencatatan meter</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Petugas <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_petugas">
                                <option value="">--Pilih Petugas--</option>
                                @foreach ($petugases as $petugas)
                                    <option value="{{ $petugas->id }}" {{ old('id_petugas') == $petugas->id ? 'selected' : '' }}>{{ $petugas->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kondisi Meter <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_kondisi">
                                <option value="">--Pilih Kondisi Meter--</option>
                                @foreach ($kondisis as $kondisi)
                                    <option value="{{ $kondisi->id }}" {{ old('id_kondisi') == $kondisi->id ? 'selected' : '' }}>{{ $kondisi->kondisi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Stand Terakhir <span class="text-danger">*</span></label>
                            <input type="text" name="stand_terakhir" class="form-control" value="0" readonly />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="ket" class="form-control" placeholder="Catatan tambahan">{{ old('ket') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Urutan <span class="text-danger">*</span></label>
                            <input type="text" name="urutan" class="form-control" value="{{ old('urutan') }}" required />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 4: Lokasi & Foto --}}
            <div class="card-body step-content d-none" id="step4">
                <h5 class="card-title d-flex align-items-center gap-2 mb-0">
                    <span class="step-icon"><i class="fas fa-map-marker-alt"></i></span>
                    Lokasi &amp; Dokumentasi
                </h5>
                <p class="text-muted small mb-4">Tentukan titik lokasi pelanggan dan unggah foto</p>
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Klik pada peta untuk memilih lokasi</label>
                            <div id="map" style="height: 320px; border-radius: 8px; border: 1px solid var(--color-border);"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="lat" id="latitude" class="form-control" value="{{ old('lat') }}" readonly />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="long" id="longitude" class="form-control" value="{{ old('long') }}" readonly />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label d-flex align-items-center gap-2">
                                Foto Pelanggan
                                <span id="fileCounter" class="badge bg-primary-subtle text-primary fw-normal">0 foto</span>
                            </label>
                            <div class="upload-zone" id="uploadZone">
                                <div class="upload-zone-placeholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p class="mb-1 fw-medium">Klik atau seret foto ke sini</p>
                                    <small class="text-muted">Maksimal 2MB per foto, format: JPG, PNG, GIF</small>
                                </div>
                                <input type="file" name="foto_pelanggan[]" id="foto_pelanggan" class="form-control d-none" multiple accept="image/*" />
                            </div>
                            <div id="fileInfoList" class="mt-2"></div>
                            <div id="preview_foto" class="row g-2 mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <div class="card-body border-top">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="{{ session('back_to_pelanggan', route('pelanggan.baru.index')) }}" class="btn btn-outline-danger px-3">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary px-4" id="prevBtn" disabled>
                            <i class="fas fa-arrow-left me-2"></i> Sebelumnya
                        </button>
                        <button type="button" class="btn btn-primary px-4" id="nextBtn">
                            Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        <button type="submit" class="btn btn-success px-4 d-none" id="submitBtn">
                            <i class="fas fa-check me-2"></i> Simpan Data
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        /* ── Step Indicator ── */
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            padding: 0 40px;
        }
        .step-indicator .step-item {
            align-items: center;
        }
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            background: var(--color-border-light);
            color: var(--color-text-muted);
            border: 2px solid var(--color-border);
            transition: all 0.3s;
        }
        .step-item.active .step-circle {
            background: var(--color-primary);
            color: #fff;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px rgba(37,99,235,0.15);
        }
        .step-item.completed .step-circle {
            background: var(--color-success);
            color: #fff;
            border-color: var(--color-success);
        }
        .step-label {
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--color-text-muted);
            white-space: nowrap;
            transition: color 0.3s;
        }
        .step-item.active .step-label {
            color: var(--color-primary);
            font-weight: 600;
        }
        .step-item.completed .step-label {
            color: var(--color-success);
        }
        .step-connector {
            flex: 1;
            height: 2px;
            background: var(--color-border);
            min-width: 20px;
            margin: 0 4px;
            margin-top: -12px;
            transition: background 0.3s;
        }
        .step-connector.active {
            background: var(--color-primary);
        }
        .step-connector.completed {
            background: var(--color-success);
        }

        /* ── Progress Bar ── */
        .step-progress {
            height: 4px;
            background: var(--color-border-light);
            border-radius: 4px;
            overflow: hidden;
        }
        .step-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--color-primary), #60A5FA);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        /* ── Step Content ── */
        .step-content {
            animation: fadeInStep 0.35s ease;
        }
        @keyframes fadeInStep {
            from { opacity: 0; transform: translateX(12px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .step-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: var(--color-primary-light);
            color: var(--color-primary);
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        /* ── Upload Zone ── */
        .upload-zone {
            border: 2px dashed var(--color-border);
            border-radius: 12px;
            padding: 36px 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--color-border-light);
            position: relative;
        }
        .upload-zone:hover {
            border-color: var(--color-primary);
            background: var(--color-primary-light);
        }
        .upload-zone.dragover {
            border-color: var(--color-primary);
            background: var(--color-primary-light);
            transform: scale(1.01);
        }
        .upload-zone i {
            font-size: 2.5rem;
            color: var(--color-text-muted);
            margin-bottom: 8px;
        }
        .upload-zone:hover i {
            color: var(--color-primary);
        }

        /* ── Responsive ── */
        @media (max-width: 576px) {
            .step-indicator { padding: 0 8px; }
            .step-circle { width: 34px; height: 34px; font-size: 0.75rem; }
            .step-label { font-size: 0.62rem; }
            .step-connector { min-width: 12px; }
        }
    </style>
@endpush

@push('script')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        (function() {
            var currentStep = 1;
            var totalSteps = 4;
            var form = document.getElementById('mainForm');
            var prevBtn = document.getElementById('prevBtn');
            var nextBtn = document.getElementById('nextBtn');
            var submitBtn = document.getElementById('submitBtn');
            var progressBar = document.getElementById('progressBar');

            function showStep(step) {
                document.querySelectorAll('.step-content').forEach(function(el) {
                    el.classList.add('d-none');
                });
                var activeEl = document.getElementById('step' + step);
                if (activeEl) activeEl.classList.remove('d-none');

                document.querySelectorAll('.step-item').forEach(function(el) {
                    var s = parseInt(el.dataset.step);
                    el.classList.remove('active', 'completed');
                    if (s === step) el.classList.add('active');
                    else if (s < step) el.classList.add('completed');
                });

                document.querySelectorAll('.step-connector').forEach(function(el, idx) {
                    el.classList.remove('active', 'completed');
                    if (idx + 1 < step) el.classList.add('completed');
                    else if (idx + 1 === step) el.classList.add('active');
                });

                prevBtn.disabled = step === 1;
                if (step === totalSteps) {
                    nextBtn.classList.add('d-none');
                    submitBtn.classList.remove('d-none');
                } else {
                    nextBtn.classList.remove('d-none');
                    submitBtn.classList.add('d-none');
                }

                progressBar.style.width = ((step / totalSteps) * 100) + '%';

                if (step === 4) initMap();

                currentStep = step;
            }

            function validateStep(step) {
                var container = document.getElementById('step' + step);
                var required = container.querySelectorAll('[required]');
                var valid = true;
                container.querySelectorAll('.is-invalid').forEach(function(el) {
                    el.classList.remove('is-invalid');
                });
                container.querySelectorAll('.invalid-feedback').forEach(function(el) {
                    el.remove();
                });
                for (var i = 0; i < required.length; i++) {
                    if (!required[i].value.trim()) {
                        required[i].classList.add('is-invalid');
                        var feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        feedback.textContent = 'Bidang ini wajib diisi.';
                        required[i].parentNode.appendChild(feedback);
                        valid = false;
                    }
                }
                if (valid) {
                    container.querySelectorAll('.is-invalid').forEach(function(el) {
                        el.classList.remove('is-invalid');
                    });
                    container.querySelectorAll('.invalid-feedback').forEach(function(el) {
                        el.remove();
                    });
                }
                return valid;
            }

            nextBtn.addEventListener('click', function() {
                if (validateStep(currentStep)) {
                    if (currentStep < totalSteps) {
                        showStep(currentStep + 1);
                    }
                }
            });

            prevBtn.addEventListener('click', function() {
                if (currentStep > 1) {
                    showStep(currentStep - 1);
                }
            });

            form.addEventListener('submit', function() {
                document.getElementById('loadingOverlay').classList.remove('d-none');
                submitBtn.disabled = true;
            });

            // ── Map (init when step 4 shown) ──
            var map = null;
            var mapInitialized = false;

            function initMap() {
                if (mapInitialized) return;
                mapInitialized = true;
                var container = document.getElementById('map');
                if (!container || container._leaflet_id) return;
                map = L.map('map').setView([5.164880647711926, 97.10991371831535], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                setTimeout(function() { map.invalidateSize(); }, 50);
                var marker;
                map.on('click', function(e) {
                    var lat = e.latlng.lat.toFixed(6);
                    var lng = e.latlng.lng.toFixed(6);
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    if (marker) {
                        marker.setLatLng(e.latlng);
                    } else {
                        marker = L.marker(e.latlng).addTo(map);
                    }
                });
            }

            // ── Upload Zone ──
            var selectedFiles = [];
            var uploadZone = document.getElementById('uploadZone');
            var fileInput = document.getElementById('foto_pelanggan');

            uploadZone.addEventListener('click', function() { fileInput.click(); });
            uploadZone.addEventListener('dragover', function(e) { e.preventDefault(); this.classList.add('dragover'); });
            uploadZone.addEventListener('dragleave', function() { this.classList.remove('dragover'); });
            uploadZone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                for (var i = 0; i < e.dataTransfer.files.length; i++) {
                    selectedFiles.push(e.dataTransfer.files[i]);
                }
                rebuildInputFiles();
                renderPreviews();
            });
            fileInput.addEventListener('change', function(e) {
                for (var i = 0; i < e.target.files.length; i++) {
                    selectedFiles.push(e.target.files[i]);
                }
                rebuildInputFiles();
                renderPreviews();
            });

            function rebuildInputFiles() {
                var dt = new DataTransfer();
                for (var i = 0; i < selectedFiles.length; i++) {
                    dt.items.add(selectedFiles[i]);
                }
                fileInput.files = dt.files;
            }

            function updateFileCounter() {
                var el = document.getElementById('fileCounter');
                var n = selectedFiles.length;
                el.textContent = n + ' foto';
                el.className = 'badge ' + (n > 0 ? 'bg-success' : 'bg-primary-subtle text-primary fw-normal');
            }

            function renderPreviews() {
                var preview = document.getElementById('preview_foto');
                var fileInfo = document.getElementById('fileInfoList');
                preview.innerHTML = '';
                fileInfo.innerHTML = '';
                var maxSize = 2 * 1024 * 1024;

                for (var i = 0; i < selectedFiles.length; i++) {
                    var file = selectedFiles[i];
                    var fileSizeKB = (file.size / 1024).toFixed(1);
                    var isValid = true;
                    var errorMsg = '';
                    var isImage = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'].includes(file.type);

                    if (file.size > maxSize) { isValid = false; errorMsg = 'Melebihi 2MB'; }
                    if (!isImage) { isValid = false; errorMsg = 'Format tidak didukung'; }

                    var infoClass = isValid ? 'text-success' : 'text-danger';
                    var iconMark = isValid ? '&#10003;' : '&#10007;';
                    var infoDiv = document.createElement('div');
                    infoDiv.className = infoClass + ' small';
                    infoDiv.innerHTML = iconMark + ' ' + file.name + ' &mdash; ' + fileSizeKB + ' KB' + (errorMsg ? ' (' + errorMsg + ')' : '');
                    fileInfo.appendChild(infoDiv);

                    (function(fileIndex, isValidFile) {
                        if (!isValidFile) return;
                        var reader = new FileReader();
                        reader.onload = function(ev) {
                            var col = document.createElement('div');
                            col.className = 'col-md-3 mb-2';
                            col.innerHTML =
                                '<div class="card position-relative overflow-hidden">' +
                                    '<button type="button" class="btn-close position-absolute top-0 end-0 m-1 bg-white rounded-circle" style="z-index:5;width:24px;height:24px;font-size:12px;box-shadow:0 1px 4px rgba(0,0,0,0.2);" onclick="window.removeFile(' + fileIndex + ')" aria-label="Hapus">&times;</button>' +
                                    '<img src="' + ev.target.result + '" class="card-img-top" alt="Preview" style="height:140px;object-fit:cover;">' +
                                '</div>';
                            preview.appendChild(col);
                        };
                        reader.readAsDataURL(file);
                    })(i, isValid);
                }
                updateFileCounter();
            }

            window.removeFile = function(index) {
                selectedFiles.splice(index, 1);
                rebuildInputFiles();
                renderPreviews();
            };

            showStep(1);
        })();
    </script>
@endpush
