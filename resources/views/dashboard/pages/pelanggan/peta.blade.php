@extends('dashboard.partials.main')

@section('title', 'Peta Pelanggan')

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Peta Pelanggan', 'subtitle' => 'Visualisasi lokasi pelanggan di peta', 'icon' => 'map-pins'])
    <div class="row">
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
                <div class="mb-3">
                    <label for="wilayahFilter" class="form-label">Filter berdasarkan Wilayah</label>
                    <select id="wilayahFilter" class="form-select">
                        <option value="">Semua Wilayah</option>
                        @foreach($wilayahs as $wilayah)
                            <option value="{{ $wilayah->id }}">{{ $wilayah->wilayah }}</option>
                        @endforeach
                    </select>
                </div>
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
        var markers = []; // Array to store markers

        // Default center
        var defaultCenter = [5.164880647711926, 97.10991371831535];
        var defaultZoom = 13;

        $(document).ready(function() {
            initMap();
            loadPelangganMarkers();

            // Handle wilayah filter change
            $('#wilayahFilter').change(function() {
                loadPelangganMarkers();
            });
        });

        function initMap() {
            map = L.map('map').setView(defaultCenter, defaultZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);
        }

        function loadPelangganMarkers() {
            var wilayahId = $('#wilayahFilter').val();
            var url = '{{ route("pelanggan.data-peta") }}';
            if (wilayahId) {
                url += '?wilayah=' + wilayahId;
            }

            console.log('Fetching data from:', url);

            // Clear existing markers
            markers.forEach(function(marker) {
                map.removeLayer(marker);
            });
            markers = [];

            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    console.log('Number of pelanggan:', data.length);

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

                        data.forEach(function(pelanggan, index) {
                            console.log('Pelanggan', index, ':', pelanggan.nama, 'lat:', pelanggan.lat, 'long:', pelanggan.long);
                            if (pelanggan.lat && pelanggan.long) {
                                var lat = parseFloat(pelanggan.lat);
                                var lng = parseFloat(pelanggan.long);

                                if (!isNaN(lat) && !isNaN(lng)) {
                                    var marker = L.marker([lat, lng], {icon: customIcon})
                                        .addTo(map);

                                    markers.push(marker); // Store marker

                                    var popupContent = `
                                        <div style="min-width: 200px;">
                                            <h6>${pelanggan.nama}</h6>
                                            <p><strong>No. Sambu:</strong> ${pelanggan.no_sambu || '-'}</p>
                                            <p><strong>No. Kontrol:</strong> ${pelanggan.no_kontrol || '-'}</p>
                                            <p><strong>Alamat:</strong> ${pelanggan.alamat || '-'}</p>
                                            <p><strong>Telepon:</strong> ${pelanggan.telepon || '-'}</p>
                                            <p><strong>Golongan:</strong> ${pelanggan.golongan ? pelanggan.golongan.nama : '-'}</p>
                                            <p><strong>Wilayah:</strong> ${pelanggan.wilayah ? pelanggan.wilayah.wilayah : '-'}</p>
                                            <p><strong>Status:</strong> ${pelanggan.status}</p>
                                            <a href="/pelanggan/${pelanggan.id}" class="btn btn-sm btn-primary">Lihat Detail</a>
                                        </div>
                                    `;

                                    marker.bindPopup(popupContent);
                                } else {
                                    console.warn('Invalid coordinates for pelanggan:', pelanggan.nama);
                                }
                            } else {
                                console.warn('Missing lat/long for pelanggan:', pelanggan.nama);
                            }
                        });

                        // Fit bounds to all markers if any
                        if (markers.length > 0) {
                            var group = L.featureGroup(markers);
                            map.fitBounds(group.getBounds().pad(0.1));
                            console.log('Fitted bounds for', markers.length, 'markers');
                        } else {
                            console.warn('No valid markers to display');
                        }
                    } else {
                        // If no data, reset to default view
                        map.setView(defaultCenter, defaultZoom);
                        console.log('No data, reset to default view');
                    }
                })
                .catch(error => {
                    console.error('Error loading pelanggan:', error);
                    alert('Error loading map data: ' + error.message);
                });
        }
    </script>
@endpush


