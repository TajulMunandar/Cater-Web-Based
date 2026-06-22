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
                    </ul>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-2 align-items-center mb-3">
                    <div class="col-md-5">
                        <label for="wilayahFilter" class="form-label mb-1" style="font-size:0.85rem;">Filter Wilayah</label>
                        <select id="wilayahFilter" class="form-select">
                            <option value="">Semua Wilayah</option>
                            @foreach($wilayahs as $wilayah)
                                <option value="{{ $wilayah->id }}">{{ $wilayah->wilayah }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-7 d-flex align-items-end justify-content-md-end gap-3">
                        <small id="petaStats" style="font-size:0.8rem;color:var(--color-text-muted);">Memuat data…</small>
                    </div>
                </div>
                <div style="position:relative;border-radius:12px;overflow:hidden;">
                    <div id="map" style="height:560px;width:100%;"></div>
                    <div id="mapLoading"
                         style="position:absolute;inset:0;background:#f8fafc;z-index:1000;display:none;flex-direction:column;align-items:center;justify-content:center;transition:opacity .25s ease;">
                        <div class="spinner-border text-primary mb-2" role="status"></div>
                        <small style="color:var(--color-text-muted);">Memuat data peta…</small>
                    </div>
                    <div id="mapLoadingMini"
                         style="position:absolute;top:12px;right:12px;z-index:999;display:none;align-items:center;gap:8px;
                                background:rgba(255,255,255,0.92);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
                                padding:6px 12px;border-radius:100px;box-shadow:0 2px 10px rgba(0,0,0,0.08);
                                font-size:11px;color:#64748B;font-weight:500;
                                opacity:0;transform:translateY(-4px);
                                transition:opacity .2s ease, transform .2s ease;">
                        <span class="map-mini-spinner" style="width:12px;height:12px;display:inline-block;
                              border:2px solid #E2E8F0;border-top-color:#2563EB;border-radius:50%;
                              animation:mapSpin 0.8s linear infinite;"></span>
                        <span>Memuat…</span>
                    </div>
                </div>
                <style>
                    @keyframes mapSpin { to { transform: rotate(360deg); } }
                </style>
            </div>
        </div>
    </div>
@endsection


@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <style>
        #map { background: #f1f5f9; }
        #map .leaflet-control-zoom { border:none!important; box-shadow:0 2px 8px rgba(0,0,0,.1)!important; border-radius:8px!important; overflow:hidden; }
        #map .leaflet-control-zoom a { border:none!important; color:#374151; width:32px; height:32px; line-height:32px; font-size:16px; }
        #map .leaflet-control-zoom a:hover { background:#F3F4F6; }
        .leaflet-popup-content-wrapper { border-radius:14px!important; box-shadow:0 8px 30px rgba(0,0,0,.15)!important; }
        .leaflet-popup-content { margin:18px 22px!important; font-family:inherit; font-size:13px; line-height:1.5; min-width:280px; }
        .leaflet-popup-tip { box-shadow:none!important; }
    </style>
@endpush

@push('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script>
        (function() {
            var defaultCenter = [5.164880647711926, 97.10991371831535];
            var defaultZoom = 13;

            var iconAktif = L.divIcon({
                className: '',
                html: '<div style="width:14px;height:14px;background:#059669;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,0.25);"></div>',
                iconSize: [14, 14], iconAnchor: [7, 7]
            });
            var iconNonAktif = L.divIcon({
                className: '',
                html: '<div style="width:14px;height:14px;background:#DC2626;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,0.25);"></div>',
                iconSize: [14, 14], iconAnchor: [7, 7]
            });

            var markerGroup = L.markerClusterGroup({
                chunkedLoading: true,
                chunkInterval: 150,
                chunkDelay: 30,
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                maxClusterRadius: function(zoom) {
                    if (zoom <= 10) return 140;
                    if (zoom <= 12) return 100;
                    if (zoom <= 13) return 60;
                    return 40;
                },
                disableClusteringAtZoom: 14,
                animate: false,
                animateAddingMarkers: false,
                iconCreateFunction: function(cluster) {
                    var childCount = 0;
                    cluster.getAllChildMarkers().forEach(function(m) {
                        childCount += (m.options && m.options.pelangganCount) ? m.options.pelangganCount : 1;
                    });
                    var size = childCount < 10 ? 36 : childCount < 100 ? 44 : childCount < 500 ? 52 : 60;
                    var bg = childCount < 100 ? '#2563EB' : childCount < 500 ? '#D97706' : '#DC2626';
                    var html = '<div style="background:' + bg + ';color:#fff;width:' + size + 'px;height:' + size + 'px;' +
                        'border-radius:50%;display:flex;align-items:center;justify-content:center;' +
                        'font-weight:700;font-size:' + (size < 44 ? 11 : size < 52 ? 12 : 13) + 'px;' +
                        'border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.25);">' + childCount + '</div>';
                    return L.divIcon({ html: html, className: '', iconSize: L.point(size, size) });
                }
            });

            var map = L.map('map', { zoomControl: false, scrollWheelZoom: false })
                .setView(defaultCenter, defaultZoom);

            L.control.zoom({ position: 'bottomleft' }).addTo(map);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
            }).addTo(map);

            setTimeout(function() { map.invalidateSize(); }, 300);

            // Legend
            var legend = L.control({ position: 'bottomright' });
            legend.onAdd = function() {
                var div = L.DomUtil.create('div', '');
                div.style.cssText = 'background:#fff;padding:10px 14px;font-size:12px;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,0.1);';
                div.innerHTML =
                    '<div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">' +
                        '<span style="display:inline-block;width:10px;height:10px;background:#059669;border-radius:50%;"></span>' +
                        '<span style="color:#374151;">Aktif</span>' +
                    '</div>' +
                    '<div style="display:flex;align-items:center;gap:8px;">' +
                        '<span style="display:inline-block;width:10px;height:10px;background:#DC2626;border-radius:50%;"></span>' +
                        '<span style="color:#374151;">Non-Aktif</span>' +
                    '</div>';
                return div;
            };
            legend.addTo(map);

            var isFittingBounds = false;
            var reloadTimer = null;
            var boundsFittedForWilayah = null;
            var currentFetchController = null;
            var mapLoadedOnce = false;

            function showMapLoading() {
                if (!mapLoadedOnce) {
                    var full = document.getElementById('mapLoading');
                    if (full) {
                        full.innerHTML = '<div class="spinner-border text-primary mb-2" role="status"></div>' +
                            '<small style="color:var(--color-text-muted);">Memuat data peta…</small>';
                        full.style.display = 'flex';
                    }
                } else {
                    var mini = document.getElementById('mapLoadingMini');
                    if (mini) {
                        mini.style.display = 'inline-flex';
                        requestAnimationFrame(function() {
                            mini.style.opacity = '1';
                            mini.style.transform = 'translateY(0)';
                        });
                    }
                }
            }

            function hideMapLoading() {
                var full = document.getElementById('mapLoading');
                if (full && full.style.display !== 'none') {
                    full.style.opacity = '0';
                    setTimeout(function() {
                        full.style.display = 'none';
                        full.style.opacity = '1';
                    }, 260);
                }
                var mini = document.getElementById('mapLoadingMini');
                if (mini) {
                    mini.style.opacity = '0';
                    mini.style.transform = 'translateY(-4px)';
                    setTimeout(function() {
                        if (mini.style.opacity === '0') mini.style.display = 'none';
                    }, 220);
                }
                mapLoadedOnce = true;
            }

            function updateStats(count) {
                var el = document.getElementById('petaStats');
                if (el) el.textContent = count.toLocaleString('id-ID') + ' pelanggan di viewport';
            }

            map.on('moveend zoomend', function() {
                if (isFittingBounds) return;
                clearTimeout(reloadTimer);
                reloadTimer = setTimeout(loadMapData, 250);
            });

            document.getElementById('wilayahFilter').addEventListener('change', function() {
                boundsFittedForWilayah = null;
                loadMapData();
            });

            function loadMapData(skipFitBounds) {
                showMapLoading();
                try {
                    var zoom = map.getZoom();
                    var bounds = map.getBounds();
                    var wilayahId = document.getElementById('wilayahFilter').value;
                    var url = '{{ route("dashboard.koordinat") }}' +
                        '?zoom=' + zoom +
                        '&detail=1' +
                        '&neLat=' + bounds.getNorthEast().lat.toFixed(6) +
                        '&neLng=' + bounds.getNorthEast().lng.toFixed(6) +
                        '&swLat=' + bounds.getSouthWest().lat.toFixed(6) +
                        '&swLng=' + bounds.getSouthWest().lng.toFixed(6) +
                        (wilayahId ? '&wilayah_id=' + wilayahId : '');

                    if (currentFetchController) {
                        try { currentFetchController.abort(); } catch (e) {}
                    }
                    currentFetchController = typeof AbortController !== 'undefined' ? new AbortController() : null;

                    fetch(url, currentFetchController ? { signal: currentFetchController.signal } : {})
                        .then(function(r) {
                            if (!r.ok) throw new Error('HTTP ' + r.status);
                            return r.json();
                        })
                        .then(function(data) {
                            currentFetchController = null;
                            try {
                                var newMarkers = [];
                                data.forEach(function(p) {
                                    try {
                                        var icon = p.status === 'aktif' ? iconAktif : iconNonAktif;
                                        var lat = Number(p.lat);
                                        var lng = Number(p.lng);
                                        if (isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0) return;
                                        var d = p.detail || {};
                                        var statusAktif = p.status === 'aktif';
                                        var statusBadge = statusAktif
                                            ? '<span style="padding:3px 10px;border-radius:100px;font-size:11px;font-weight:600;background:#ECFDF5;color:#059669;">Aktif</span>'
                                            : '<span style="padding:3px 10px;border-radius:100px;font-size:11px;font-weight:600;background:#FEF2F2;color:#DC2626;">Non-Aktif</span>';
                                        var popupHtml =
                                            '<div style="min-width:280px;">' +
                                                '<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:10px;">' +
                                                    '<div>' +
                                                        '<h6 style="margin:0 0 2px;font-weight:700;font-size:15px;color:#0F172A;">' + (p.nama || '-') + '</h6>' +
                                                        '<span style="font-size:12px;color:#94A3B8;">' + (d.no_sambu ? 'No. Sambu: ' + d.no_sambu : 'Pelanggan') + '</span>' +
                                                    '</div>' +
                                                    statusBadge +
                                                '</div>' +
                                                '<div style="display:flex;flex-direction:column;gap:6px;font-size:12.5px;color:#334155;margin-bottom:12px;">' +
                                                    '<div><strong style="color:#64748B;font-weight:500;">Alamat:</strong> ' + (d.alamat || '-') + '</div>' +
                                                    '<div><strong style="color:#64748B;font-weight:500;">Telepon:</strong> ' + (d.telepon || '-') + '</div>' +
                                                    '<div style="display:flex;gap:16px;">' +
                                                        '<span><strong style="color:#64748B;font-weight:500;">Golongan:</strong> ' + (d.golongan || '-') + '</span>' +
                                                        '<span><strong style="color:#64748B;font-weight:500;">Wilayah:</strong> ' + (d.wilayah || '-') + '</span>' +
                                                    '</div>' +
                                                    '<div style="color:#94A3B8;font-size:11px;">Koordinat: ' + lat.toFixed(5) + ', ' + lng.toFixed(5) + '</div>' +
                                                '</div>' +
                                                '<a href="/pelanggan/' + p.any_id + '" style="display:block;text-align:center;padding:8px;background:#2563EB;color:#fff;text-decoration:none;border-radius:8px;font-size:12px;font-weight:600;">Lihat Detail &rarr;</a>' +
                                            '</div>';

                                        var m = L.marker([lat, lng], { icon: icon, pelangganCount: 1 });
                                        m.bindPopup(popupHtml, { maxWidth: 360, minWidth: 280 });
                                        m.on('mouseover', function() { this.openPopup(); });
                                        newMarkers.push(m);
                                    } catch (e) { /* skip single */ }
                                });

                                markerGroup.clearLayers();
                                markerGroup.addLayers(newMarkers);
                                if (!map.hasLayer(markerGroup)) markerGroup.addTo(map);

                                updateStats(newMarkers.length);

                                var curWilayah = document.getElementById('wilayahFilter').value;
                                if (!skipFitBounds && curWilayah && newMarkers.length > 0 && boundsFittedForWilayah !== curWilayah) {
                                    boundsFittedForWilayah = curWilayah;
                                    isFittingBounds = true;
                                    var group = L.featureGroup(newMarkers);
                                    map.fitBounds(group.getBounds().pad(0.15), { animate: true, duration: 0.5, maxZoom: 15 });
                                    setTimeout(function() { isFittingBounds = false; }, 600);
                                }

                            } catch (err) {
                                console.error('Error render marker peta:', err);
                            } finally {
                                hideMapLoading();
                            }
                        })
                        .catch(function(err) {
                            if (err && err.name === 'AbortError') { hideMapLoading(); return; }
                            console.error('Fetch peta gagal:', err);
                            hideMapLoading();
                            var mapEl = document.getElementById('map');
                            if (mapEl) {
                                var errDiv = document.createElement('div');
                                errDiv.style.cssText = 'position:absolute;bottom:12px;left:50%;transform:translateX(-50%);background:#FEF2F2;color:#DC2626;padding:6px 14px;border-radius:8px;font-size:12px;z-index:999;box-shadow:0 2px 8px rgba(0,0,0,0.1);';
                                errDiv.textContent = 'Gagal memuat data peta. Coba refresh.';
                                mapEl.parentElement.appendChild(errDiv);
                                setTimeout(function() { errDiv.remove(); }, 5000);
                            }
                        });
                } catch (err) {
                    console.error('loadMapData error:', err);
                    hideMapLoading();
                }
            }

            map.whenReady(function() { loadMapData(); });
            setTimeout(function() { hideMapLoading(); }, 8000);
        })();
    </script>
@endpush
