@extends('dashboard.partials.main')

@section('content')
    <div class="row mt-2">
        <div class="card">
            <div class="row">
                <div class="col col-lg-10">
                    <ul class="nav nav-pills user-profile-tab">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/pelanggan"><i class="fas fa-users me-2"></i>Data Pelanggan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/pelanggan/pelanggan-baru"><i class="fas fa-user me-2"></i>Data Pelanggan Baru</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/pelanggan/peta"><i class="fas fa-map me-2"></i>Peta Pelanggan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                aria-current="page" href="/pelanggan/dsml"><i class="fas fa-list me-2"></i>DSML</a>
                        </li>
                    </ul>
                </div>
                <div class="col pt-3 pe-3">
                    <button class="btn btn-primary float-end"><i class="fas fa-plus me-2"></i>Tambah</button>
                </div>
            </div>

            <div class="card-body">
                <div id="map" style="height: 400px; width: 100%;"></div>
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
        var map = L.map('map').setView([5.164880647711926, 97.10991371831535], 13); // Jakarta

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
        }).addTo(map);

        L.marker([5.164880647711926, 97.10991371831535]).addTo(map)
            .bindPopup('Lokasi Saya')
            .openPopup();
    </script>
@endpush
