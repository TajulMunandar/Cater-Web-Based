@extends('dashboard.partials.main')

@section('content')
    <div class="card">
        <form action="#">
            <div>
                <div class="card-body">
                    <h4 class="card-title">Data Pelanggan</h4>
                    <div class="row pt-3">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" id="firstName" class="form-control" placeholder="John doe" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea id="firstName" class="form-control" placeholder="John doe"></textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No Sambung</label>
                                <input type="text" id="firstName" class="form-control" placeholder="John doe" />
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No Kontrol</label>
                                <input type="text" id="lastName" class="form-control" placeholder="12n" />
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No Telepon</label>
                                <input type="text" id="firstName" class="form-control" placeholder="John doe" />
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <input type="text" id="lastName" class="form-control" placeholder="12n" />
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
                                <select class="form-select">
                                    <option>--Pilih Petugas--</option>
                                    @foreach ($petugases as $petugas)
                                        <option value="{{ $petugas->id }}">{{ $petugas->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Rute</label>
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kondisi Meter</label>
                                <select class="form-select">
                                    <option>--Pilih Kondisi Meter--</option>
                                    @foreach ($kondisis as $kondisi)
                                        <option value="{{ $kondisi->id }}">{{ $kondisi->kondisi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Stand Terakhir</label>
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Wilayah</label>
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Urutan</label>
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                        <!--/span-->
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea id="firstName" class="form-control" placeholder="John doe"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi (Klik pada peta untuk memilih)</label>
                            <div id="map" style="height: 300px;"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" id="latitude" class="form-control" readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" id="longitude" class="form-control" readonly />
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                </div>
                <div class="form-actions">
                    <div class="card-body border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i> Simpan
                        </button>
                        <button type="button" class="btn bg-danger-subtle text-danger ms-6">
                            Batal
                        </button>
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
        var map = L.map('map').setView([5.164880647711926, 97.10991371831535], 15); // Contoh koordinat Jakarta

        // Tambahkan tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var marker;

        // Event saat klik di peta
        map.on('click', function(e) {
            var lat = e.latlng.lat.toFixed(6);
            var lng = e.latlng.lng.toFixed(6);

            // Tampilkan di input
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Tambah/ubah marker
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
        });
    </script>
@endpush
