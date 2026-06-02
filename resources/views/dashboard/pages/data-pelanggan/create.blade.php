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

    <!-- Loading Overlay -->
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
            <div>
                <div class="card-body">
                    <h4 class="card-title">Data Pelanggan</h4>
                    <div class="row pt-3">
                         <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">Nama</label>
                                  <input type="text" name="nama" class="form-control" placeholder="John Doe" value="{{ old('nama') }}" required />
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">Alamat</label>
                                  <textarea name="alamat" class="form-control" placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">No Sambung</label>
                                  <input type="text" name="no_sambu" class="form-control" placeholder="No Sambung" value="{{ old('no_sambu') }}" required />
                              </div>
                          </div>
                          <!--/span-->
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">No Kontrol</label>
                                  <input type="text" name="no_kontrol" class="form-control" placeholder="No Kontrol" value="{{ old('no_kontrol') }}" required />
                              </div>
                          </div>
                          <!--/span-->
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">No Telepon</label>
                                  <input type="text" name="telepon" class="form-control" placeholder="No Telepon" value="{{ old('telepon') }}" />
                              </div>
                          </div>
                          <!--/span-->
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">Type</label>
                                  <input type="text" name="type" class="form-control" placeholder="Type" value="{{ old('type') }}" />
                              </div>
                          </div>
                         <!--/span-->
                     </div>
                 </div>
                 <hr />
                 <div class="card-body">
                     <!--/row-->
                     <h4 class="card-title mb-4">Detail Pelanggan</h4>
                     <div class="row">
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">Petugas</label>
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
                                  <label class="form-label">Rute</label>
                                  <input type="text" name="rute" class="form-control" value="{{ old('rute') }}" required />
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">Kondisi Meter</label>
                                  <select class="form-select" name="id_kondisi">
                                      <option value="">--Pilih Kondisi Meter--</option>
                                      @foreach ($kondisis as $kondisi)
                                          <option value="{{ $kondisi->id }}" {{ old('id_kondisi') == $kondisi->id ? 'selected' : '' }}>{{ $kondisi->kondisi }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>
                          <!--/span-->
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">Waktu Catat Meter</label>
                                  <input type="datetime-local" name="waktu_catat_meter" class="form-control" value="{{ old('waktu_catat_meter') }}" required />
                              </div>
                          </div>
                          <!--/span-->
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">Stand Terakhir</label>
                                  <input type="text" name="stand_terakhir" class="form-control" value="{{ old('stand_terakhir') }}" required />
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">Keterangan</label>
                                  <textarea name="ket" class="form-control" placeholder="Keterangan">{{ old('ket') }}</textarea>
                              </div>
                          </div>
                         <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">Urutan</label>
                                  <input type="text" name="urutan" class="form-control" value="{{ old('urutan') }}" required />
                              </div>
                          </div>
                          <!--/span-->
                     </div>
                     <!--/row-->
                     <div class="row">
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">Wilayah Domisili</label>
                                  <select class="form-select" name="id_wilayah">
                                      <option value="">--Pilih Wilayah Domisili--</option>
                                      @foreach ($wilayahs as $wilayah)
                                          <option value="{{ $wilayah->id }}" {{ old('id_wilayah') == $wilayah->id ? 'selected' : '' }}>{{ $wilayah->wilayah }}</option>
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
                                  <label class="form-label">Status</label>
                                  <select class="form-select" name="status" required>
                                      <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                      <option value="non-aktif" {{ old('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">Wilayah Operasional</label>
                                  <select class="form-select" name="id_wilayah_detail">
                                      <option value="">--Pilih Wilayah Operasional--</option>
                                      @foreach ($wilayahs as $wilayah)
                                          <option value="{{ $wilayah->id }}" {{ old('id_wilayah_detail') == $wilayah->id ? 'selected' : '' }}>{{ $wilayah->wilayah }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="mb-3">
                                  <label class="form-label">Lokasi (Klik pada peta untuk memilih)</label>
                                  <div id="map" style="height: 300px;"></div>
                              </div>
                          </div>
                         <div class="row">
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
                         </div>
                          <div class="col-md-12">
                              <div class="mb-3">
                                  <label class="form-label">Foto Pelanggan (Multiple) <span id="fileCounter" class="text-muted ms-2 small">0 foto dipilih</span></label>
                                  <input type="file" name="foto_pelanggan[]" id="foto_pelanggan" class="form-control" multiple accept="image/*" />
                                  <small class="text-muted">Maksimal 2MB per foto, format: JPG, PNG, GIF</small>
                                  <div id="fileInfoList" class="mt-2"></div>
                              </div>
                              <div id="preview_foto" class="row"></div>
                          </div>
                     </div>
                 </div>
                 <div class="form-actions">
                     <div class="card-body border-top">
                         <button type="submit" class="btn btn-primary" id="submitBtn">
                             <i class="fa fa-save me-2"></i> Simpan
                         </button>
                         <a href="{{ session('back_to_pelanggan', route('pelanggan.baru.index')) }}" class="btn bg-danger-subtle text-danger ms-6">
                             Batal
                         </a>
                     </div>
                 </div>
            </div>
        </form>
    </div>
@endsection

@push('head')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@push('script')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([5.164880647711926, 97.10991371831535], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

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

        // Loading overlay on form submit
        document.getElementById('mainForm').addEventListener('submit', function() {
            document.getElementById('loadingOverlay').classList.remove('d-none');
            document.getElementById('submitBtn').disabled = true;
        });

        // Enhanced file upload with per-file cancel, validation, preview, and counter
        var selectedFiles = [];

        function updateFileCounter() {
            var el = document.getElementById('fileCounter');
            var n = selectedFiles.length;
            el.textContent = n + ' foto dipilih';
            el.className = 'ms-2 small' + (n > 0 ? ' text-primary fw-bold' : ' text-muted');
        }

        function rebuildInputFiles() {
            var dt = new DataTransfer();
            for (var i = 0; i < selectedFiles.length; i++) {
                dt.items.add(selectedFiles[i]);
            }
            document.getElementById('foto_pelanggan').files = dt.files;
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

                if (file.size > maxSize) {
                    isValid = false;
                    errorMsg = 'Melebihi 2MB';
                }
                if (!isImage) {
                    isValid = false;
                    errorMsg = 'Format tidak didukung';
                }

                var infoClass = isValid ? 'text-success' : 'text-danger';
                var iconMark = isValid ? '✓' : '✗';
                var infoDiv = document.createElement('div');
                infoDiv.className = infoClass + ' small';
                infoDiv.textContent = iconMark + ' ' + file.name + ' — ' + fileSizeKB + ' KB' + (errorMsg ? ' (' + errorMsg + ')' : '');
                fileInfo.appendChild(infoDiv);

                (function(fileIndex, isValidFile) {
                    if (!isValidFile) return;
                    var reader = new FileReader();
                    reader.onload = function(ev) {
                        var col = document.createElement('div');
                        col.className = 'col-md-3 mb-2';
                        col.innerHTML =
                            '<div class="card position-relative">' +
                                '<button type="button" class="btn-close position-absolute top-0 end-0 m-1 bg-white rounded-circle d-flex align-items-center justify-content-center" style="z-index:5;width:26px;height:26px;font-size:14px;box-shadow:0 1px 4px rgba(0,0,0,0.25);" onclick="removeFile(' + fileIndex + ')" aria-label="Hapus foto">&times;</button>' +
                                '<img src="' + ev.target.result + '" class="card-img-top" alt="Preview" style="height:150px;object-fit:cover;">' +
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

        document.getElementById('foto_pelanggan').addEventListener('change', function(e) {
            var input = e.target;
            for (var i = 0; i < input.files.length; i++) {
                selectedFiles.push(input.files[i]);
            }
            rebuildInputFiles();
            renderPreviews();
        });
    </script>
@endpush
