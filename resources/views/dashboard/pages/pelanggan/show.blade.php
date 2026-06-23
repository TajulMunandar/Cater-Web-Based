@extends('dashboard.partials.main')

@section('title', 'Detail Pelanggan')

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Detail Pelanggan', 'subtitle' => 'Informasi lengkap data pelanggan', 'icon' => 'user'])
    <div class="row">
        <div class="card">
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
                    </ul>
                </div>
                <div class="col pt-3 pe-3">
                    <a href="{{ $back_url ?? route('pelanggan.index') }}" class="btn btn-secondary float-end"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
                </div>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Informasi Dasar Pelanggan</h4>
                    <div class="d-flex gap-2 align-items-center">
                        <span id="saveIndicator" style="font-size:12px;font-weight:600;display:none;"></span>
                        <button id="toggleEdit" class="btn btn-primary"><i class="fas fa-pen me-1"></i> Ubah</button>
                    </div>
                </div>

                <form id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama</label>
                                <input type="text" class="form-control" name="nama" value="{{ $pelanggan->nama }}" readonly>
                                <div class="invalid-feedback" data-field="nama"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat</label>
                                <textarea class="form-control" name="alamat" rows="3" readonly>{{ $pelanggan->alamat }}</textarea>
                                <div class="invalid-feedback" data-field="alamat"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">No Sambungan</label>
                                <input type="text" class="form-control" name="no_sambu" value="{{ $pelanggan->no_sambu }}" readonly>
                                <div class="invalid-feedback" data-field="no_sambu"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Telepon</label>
                                <input type="text" class="form-control" name="telepon" value="{{ $pelanggan->telepon ?? '' }}" readonly>
                                <div class="invalid-feedback" data-field="telepon"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Type</label>
                                <input type="text" class="form-control" name="type" value="{{ $pelanggan->type ?? '' }}" readonly>
                                <div class="invalid-feedback" data-field="type"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rute</label>
                                <select class="form-control" name="id_rute" disabled>
                                    <option value="">-- Pilih Rute --</option>
                                    @foreach($rutes as $r)
                                        <option value="{{ $r->id }}" {{ $pelanggan->id_rute == $r->id ? 'selected' : '' }}>
                                            {{ $r->rute }} {{ $r->wilayah ? '- ' . $r->wilayah->wilayah : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" data-field="id_rute"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Golongan</label>
                                <select class="form-control" name="id_gol" disabled>
                                    <option value="">-- Pilih Golongan --</option>
                                    @foreach($golongans as $g)
                                        <option value="{{ $g->id }}" {{ $pelanggan->id_gol == $g->id ? 'selected' : '' }}>
                                            {{ $g->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" data-field="id_gol"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select class="form-control" name="status" disabled>
                                    <option value="aktif" {{ $pelanggan->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="non-aktif" {{ $pelanggan->status == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                                <div class="invalid-feedback" data-field="status"></div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Operational Details -->
                <hr class="my-4">
                <h4 class="card-title mb-4">Detail Operasional</h4>

                <div id="operationalView">
                    @if($pelanggan->PelangganDetail && $pelanggan->PelangganDetail->count() > 0)
                        @foreach($pelanggan->PelangganDetail as $detail)
                            <div class="card border mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Data Operasional</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Petugas</label>
                                                <input type="text" class="form-control" value="{{ $detail->Petugas?->nama ?? '-' }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Kondisi Meter</label>
                                                <input type="text" class="form-control" value="{{ $detail->Kondisi?->kondisi ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Stand Terakhir</label>
                                                <input type="text" class="form-control" value="{{ $detail->stand_terakhir }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Keterangan</label>
                                                <input type="text" class="form-control" value="{{ $detail->ket ?? '-' }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Urutan</label>
                                                <input type="text" class="form-control" value="{{ $detail->urutan }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info" id="noOperationalMsg">
                            <i class="fas fa-info-circle me-2"></i>Belum ada data operasional untuk pelanggan ini.
                        </div>
                    @endif
                </div>

                <div id="operationalEditForm" style="display:none;">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">{{ $pelanggan->PelangganDetail && $pelanggan->PelangganDetail->count() > 0 ? 'Edit Data Operasional' : 'Tambah Data Operasional' }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Petugas</label>
                                        <select class="form-control" id="detail_id_petugas">
                                            <option value="">-- Pilih Petugas --</option>
                                            @foreach($petugasList as $p)
                                                <option value="{{ $p->id }}" {{ ($pelanggan->PelangganDetail->first()->id_petugas ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" data-field="id_petugas"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kondisi Meter</label>
                                        <select class="form-control" id="detail_id_kondisi">
                                            <option value="">-- Pilih Kondisi --</option>
                                            @foreach($kondisiList as $k)
                                                <option value="{{ $k->id }}" {{ ($pelanggan->PelangganDetail->first()->id_kondisi ?? '') == $k->id ? 'selected' : '' }}>{{ $k->kondisi }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" data-field="id_kondisi"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Stand Terakhir</label>
                                        <input type="number" class="form-control" id="detail_stand_terakhir" value="{{ $pelanggan->PelangganDetail->first()->stand_terakhir ?? '' }}" placeholder="0">
                                        <div class="invalid-feedback" data-field="stand_terakhir"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Keterangan</label>
                                        <input type="text" class="form-control" id="detail_ket" value="{{ $pelanggan->PelangganDetail->first()->ket ?? '' }}" placeholder="Opsional">
                                        <div class="invalid-feedback" data-field="ket"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Urutan</label>
                                        <input type="number" class="form-control" id="detail_urutan" value="{{ $pelanggan->PelangganDetail->first()->urutan ?? '' }}" placeholder="1">
                                        <div class="invalid-feedback" data-field="urutan"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Koordinat -->
                <hr class="my-4">
                <h4 class="card-title mb-4">Lokasi</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Latitude</label>
                            <input type="text" class="form-control" name="lat" id="latitude" value="{{ $pelanggan->lat ?? '' }}" readonly>
                            <div class="invalid-feedback" data-field="lat"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Longitude</label>
                            <input type="text" class="form-control" name="long" id="longitude" value="{{ $pelanggan->long ?? '' }}" readonly>
                            <div class="invalid-feedback" data-field="long"></div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div id="map" style="height: 300px;"></div>
                </div>

                <!-- Photos -->
                <hr class="my-4">
                <h4 class="card-title mb-4">Foto Pelanggan</h4>

                @if($pelanggan->FotoPelanggan && $pelanggan->FotoPelanggan->count() > 0)
                <div class="row" id="existingPhotos">
                    @foreach($pelanggan->FotoPelanggan as $foto)
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img src="{{ str_starts_with($foto->foto, 'http') ? $foto->foto : \Illuminate\Support\Facades\Storage::url($foto->foto) }}" class="card-img-top" alt="Foto" style="height: 150px; object-fit: cover;" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22200%22><rect fill=%22%23f8f9fa%22 width=%22300%22 height=%22200%22/><text fill=%22%236c757d%22 font-size=%2216%22 x=%2250%%22 y=%2250%%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22>Gambar Tidak Tersedia</text></svg>'; this.onerror=null;">
                            <div class="card-body text-center p-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hapus_foto[]" value="{{ $foto->id }}" id="hapus_foto_{{ $foto->id }}">
                                    <label class="form-check-label text-danger small" for="hapus_foto_{{ $foto->id }}">Hapus</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-info" id="noPhotoMsg">
                    <i class="fas fa-info-circle me-2"></i>Belum ada foto untuk pelanggan ini.
                </div>
                @endif

                <div class="col-md-12 mt-3">
                    <div class="mb-3">
                        <label class="form-label">Tambah Foto Baru <span id="fileCounter" class="text-muted ms-2 small">0 foto dipilih</span></label>
                        <input type="file" name="foto_pelanggan[]" id="foto_pelanggan" class="form-control" multiple accept="image/*" />
                        <small class="text-muted">Maksimal 2MB per foto, format: JPG, PNG, GIF</small>
                        <div id="fileInfoList" class="mt-2"></div>
                    </div>
                    <div id="preview_foto" class="row"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@push('script')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
$(document).ready(function() {
    var isEditing = false;
    var $btn = $('#toggleEdit');
    var $indicator = $('#saveIndicator');
    var $form = $('#editForm');
    var selectedFiles = [];
    var fieldMap = { 'id_petugas': '#detail_id_petugas', 'id_kondisi': '#detail_id_kondisi', 'stand_terakhir': '#detail_stand_terakhir', 'ket': '#detail_ket', 'urutan': '#detail_urutan' };
    var idToField = { 'detail_id_petugas': 'id_petugas', 'detail_id_kondisi': 'id_kondisi', 'detail_stand_terakhir': 'stand_terakhir', 'detail_ket': 'ket', 'detail_urutan': 'urutan' };

    // ---- Map ----
    var defaultLat = {{ $pelanggan->lat ?: -6.2088 }};
    var defaultLng = {{ $pelanggan->long ?: 106.8456 }};
    var map = L.map('map').setView([defaultLat, defaultLng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);
    var marker = L.marker([defaultLat, defaultLng], { draggable: false }).addTo(map);
    $('#latitude').val(defaultLat.toFixed(6));
    $('#longitude').val(defaultLng.toFixed(6));
    function updateCoord(lat, lng) {
        $('#latitude').val(lat.toFixed(6));
        $('#longitude').val(lng.toFixed(6));
        if (marker) marker.setLatLng([lat, lng]);
    }
    map.on('click', function(e) { if (isEditing) updateCoord(e.latlng.lat, e.latlng.lng); });

    // ---- Error helpers ----
    function clearErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('').hide();
        $indicator.html('').hide();
    }
    function showFieldError(field, msg) {
        var input = fieldMap[field] ? $(fieldMap[field]) : $('[name="' + field + '"], #' + field);
        input.addClass('is-invalid');
        var fb = $('.invalid-feedback[data-field="' + field + '"]');
        fb.text(msg).show();
    }
    function showErrors(errors) {
        $.each(errors, function(field, msgs) {
            showFieldError(field, Array.isArray(msgs) ? msgs[0] : msgs);
        });
    }
    function clearFieldError(e) {
        $(e.target).removeClass('is-invalid');
        var field = idToField[e.target.id] || e.target.name || e.target.id;
        $('.invalid-feedback[data-field="' + field + '"]').text('').hide();
    }

    // ---- Mode toggle ----
    function setMode(edit) {
        isEditing = edit;
        clearErrors();
        $form.find('input[name], textarea').prop('readonly', !edit);
        $form.find('select').prop('disabled', !edit);
        if (marker) marker.dragging[edit ? 'enable' : 'disable']();
        if (edit) {
            $('#operationalView').hide();
            $('#operationalEditForm').show();
            $('#noOperationalMsg').hide();
            $btn.html('<i class="fas fa-save me-1"></i> Simpan').removeClass('btn-primary').addClass('btn-success');
            $indicator.hide();
        } else {
            $('#operationalEditForm').hide();
            $('#operationalView').show();
            $('#noOperationalMsg').show();
            $btn.html('<i class="fas fa-pen me-1"></i> Ubah').removeClass('btn-success').addClass('btn-primary');
        }
    }
    if (marker) marker.on('dragend', function() {
        var ll = marker.getLatLng();
        updateCoord(ll.lat, ll.lng);
    });

    // Clear error on input change
    $form.on('change input', 'input, select, textarea', clearFieldError);
    $(document).on('change input', '#detail_id_petugas, #detail_id_kondisi, #detail_stand_terakhir, #detail_ket, #detail_urutan', clearFieldError);

    // ---- File helpers ----
    function updateFileCounter() {
        var el = document.getElementById('fileCounter');
        el.textContent = selectedFiles.length + ' foto dipilih';
        el.className = 'ms-2 small' + (selectedFiles.length > 0 ? ' text-primary fw-bold' : ' text-muted');
    }
    function rebuildInputFiles() {
        var dt = new DataTransfer();
        for (var i = 0; i < selectedFiles.length; i++) dt.items.add(selectedFiles[i]);
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
            var isValid = file.size <= maxSize && ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'].includes(file.type);
            var err = !isValid ? (file.size > maxSize ? 'Melebihi 2MB' : 'Format tidak didukung') : '';
            fileInfo.innerHTML += '<div class="' + (isValid ? 'text-success' : 'text-danger') + ' small">' + (isValid ? '\u2713' : '\u2717') + ' ' + file.name + ' \u2014 ' + (file.size / 1024).toFixed(1) + ' KB' + (err ? ' (' + err + ')' : '') + '</div>';
            if (!isValid) continue;
            (function(idx) {
                var reader = new FileReader();
                reader.onload = function(ev) {
                    var col = document.createElement('div');
                    col.className = 'col-md-3 mb-2';
                    col.innerHTML = '<div class="card position-relative">' +
                        '<button type="button" class="btn-close position-absolute top-0 end-0 m-1 bg-white rounded-circle d-flex align-items-center justify-content-center" style="z-index:5;width:26px;height:26px;font-size:14px;box-shadow:0 1px 4px rgba(0,0,0,0.25);" onclick="removeFile(' + idx + ')" aria-label="Hapus foto">&times;</button>' +
                        '<img src="' + ev.target.result + '" class="card-img-top" alt="Preview" style="height:150px;object-fit:cover;">' +
                        '</div>';
                    preview.appendChild(col);
                };
                reader.readAsDataURL(file);
            })(i);
        }
        updateFileCounter();
    }
    window.removeFile = function(index) { selectedFiles.splice(index, 1); rebuildInputFiles(); renderPreviews(); };
    document.getElementById('foto_pelanggan').addEventListener('change', function(e) {
        for (var i = 0; i < e.target.files.length; i++) selectedFiles.push(e.target.files[i]);
        rebuildInputFiles();
        renderPreviews();
    });

    // ---- Save ----
    $btn.on('click', function() {
        if (!isEditing) { setMode(true); return; }
        clearErrors();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

        var petugas = $('#detail_id_petugas').val();
        var kondisi = $('#detail_id_kondisi').val();
        var stand = $('#detail_stand_terakhir').val();
        var ket = $('#detail_ket').val();
        var urutan = $('#detail_urutan').val();

        var detailPromise = $.Deferred().resolve();
        if (petugas && kondisi && stand && urutan) {
            detailPromise = $.ajax({
                url: '/pelanggan/{{ $pelanggan->id }}/detail',
                method: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content'), id_petugas: petugas, id_kondisi: kondisi, stand_terakhir: stand, ket: ket, urutan: urutan }
            });
        }

        $.when(detailPromise).then(function(detailRes) {
            if (detailRes && detailRes.data) {
                var d = detailRes.data;
                $('#operationalView').html(
                    '<div class="card border mb-3"><div class="card-header bg-light"><h6 class="mb-0">Data Operasional</h6></div><div class="card-body"><div class="row"><div class="col-md-6">' +
                    '<div class="mb-3"><label class="form-label fw-bold">Petugas</label><input type="text" class="form-control" value="' + d.petugas_label + '" readonly></div>' +
                    '<div class="mb-3"><label class="form-label fw-bold">Kondisi Meter</label><input type="text" class="form-control" value="' + d.kondisi_label + '" readonly></div></div>' +
                    '<div class="col-md-6"><div class="mb-3"><label class="form-label fw-bold">Stand Terakhir</label><input type="text" class="form-control" value="' + d.stand_terakhir + '" readonly></div>' +
                    '<div class="mb-3"><label class="form-label fw-bold">Keterangan</label><input type="text" class="form-control" value="' + (d.ket || '-') + '" readonly></div>' +
                    '<div class="mb-3"><label class="form-label fw-bold">Urutan</label><input type="text" class="form-control" value="' + d.urutan + '" readonly></div></div></div></div></div>'
                );
            }
            var fd = new FormData($form[0]);
            fd.set('_method', 'PUT');
            return $.ajax({
                url: '/pelanggan/{{ $pelanggan->id }}',
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
        }).then(function(res) {
            if (res.data) {
                var d = res.data;
                $form.find('[name="nama"]').val(d.nama);
                $form.find('[name="alamat"]').val(d.alamat);
                $form.find('[name="no_sambu"]').val(d.no_sambu);
                $form.find('[name="telepon"]').val(d.telepon || '');
                $form.find('[name="type"]').val(d.type || '');
                $form.find('[name="id_rute"]').val(d.id_rute || '');
                $form.find('[name="id_gol"]').val(d.id_gol || '');
                $form.find('[name="status"]').val(d.status);
                $form.find('[name="lat"]').val(d.lat || '');
                $form.find('[name="long"]').val(d.long || '');
            }
            $indicator.html('<i class="fas fa-check-circle"></i> Tersimpan').css('color', '#10b981');
            setMode(false);
        }, function(xhr) {
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                showErrors(xhr.responseJSON.errors);
            } else {
                var msg = 'Gagal menyimpan';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                $indicator.html('<i class="fas fa-exclamation-circle"></i> ' + msg).css('color', '#dc2626').fadeIn(200).delay(3000).fadeOut(500);
            }
        }).always(function() {
            $btn.prop('disabled', false);
            if (isEditing) $btn.html('<i class="fas fa-save me-1"></i> Simpan').removeClass('btn-primary').addClass('btn-success');
        });
    });
});
</script>
@endpush
