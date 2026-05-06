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
                                href="/pelanggan/baru"><i class="fas fa-user me-2"></i>Data Pelanggan Baru</a>
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
            </div>

            <div class="card-body">
                <div id="map" style="height: 500px; width: 100%;"></div>
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
        var map;
        
        // Default center (can be overridden by wilayah)
        var defaultCenter = [5.164880647711926, 97.10991371831535];
        var defaultZoom = 13;

        $(document).ready(function() {
            initMap();
            loadPelangganMarkers();
        });

        function initMap() {
            map = L.map('map').setView(defaultCenter, defaultZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);
        }

        function loadPelangganMarkers() {
            fetch('/pelanggan/peta/data')
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        // Create custom icon
                        var customIcon = L.icon({
                            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        });

                        data.forEach(function(pelanggan) {
                            if (pelanggan.lat && pelanggan.long) {
                                var marker = L.marker([pelanggan.lat, pelanggan.long], {icon: customIcon})
                                    .addTo(map);
                                
                                var popupContent = `
                                    <div style="min-width: 200px;">
                                        <h6>${pelanggan.nama}</h6>
                                        <p><strong>No. Sambu:</strong> ${pelanggan.no_sambu || '-'}</p>
                                        <p><strong>No. Kontrol:</strong> ${pelanggan.no_kontrol || '-'}</p>
                                        <p><strong>Alamat:</strong> ${pelanggan.alamat || '-'}</p>
                                        <p><strong>Telepon:</strong> ${pelanggan.telepon || '-'}</p>
                                        <p><strong>Golongan:</strong> ${pelanggan.golongan ? pelanggan.golongan.nama : '-'}</p>
                                        <p><strong>Wilayah:</strong> ${pelanggan.wilayah ? pelanggan.wilayah.wilayah : '-'}</p>
                                        <a href="/pelanggan/${pelanggan.id}" class="btn btn-sm btn-primary">Lihat Detail</a>
                                    </div>
                                `;
                                
                                marker.bindPopup(popupContent);
                            }
                        });

                        // Fit bounds to all markers
                        var group = L.featureGroup(data.map(function(p) {
                            return L.marker([p.lat, p.long]);
                        }));
                        map.fitBounds(group.getBounds().pad(0.1));
                    }
                })
                .catch(error => console.error('Error loading pelanggan:', error));
        }
    </script>
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
