@extends('dashboard.partials.main')

@section('title', 'Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4 animate-fade-in-up">
        <div>
            <h1 class="fw-bold mb-1" style="font-size:1.5rem;letter-spacing:-0.04em;color:var(--color-text);">Dashboard</h1>
            <p class="mb-0 d-flex align-items-center gap-2" style="font-size:0.85rem;color:var(--color-text-muted);">
                <i class="ti ti-calendar" style="font-size:0.9rem;"></i>
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pelanggan.baru.create') }}" class="btn btn-modern btn-modern-primary d-inline-flex align-items-center gap-2">
                <i class="ti ti-plus" style="font-size:1.1rem;"></i>
                Tambah Pelanggan
            </a>
            <form action="{{ route('dashboard.refresh') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-modern btn-modern-outline d-inline-flex align-items-center gap-2" title="Refresh data">
                    <i class="ti ti-refresh" style="font-size:1.1rem;"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Notifikasi --}}
    <div id="notifikasiContainer">
        @if(count($notifikasi) > 0)
            @foreach($notifikasi as $notif)
                <div class="alert d-flex align-items-center gap-3 px-4 py-3 mb-3 animate-fade-in"
                     style="background:var(--bg-card);border:1px solid var(--color-border);border-radius:var(--radius-md);border-left:4px solid var(--color-{{ $notif['type'] }});box-shadow:var(--shadow-sm);">
                    <i class="ti ti-{{ $notif['icon'] }}" style="font-size:1.3rem;color:var(--color-{{ $notif['type'] }});"></i>
                    <span class="flex-grow-1" style="font-size:0.9rem;color:var(--color-text);">{{ $notif['pesan'] }}</span>
                    <a href="{{ $notif['url'] }}" class="btn btn-modern btn-modern-outline flex-shrink-0" style="font-size:0.8rem;padding:0.3rem 0.85rem;">Lihat</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size:0.75rem;"></button>
                </div>
            @endforeach
        @endif
    </div>

    {{-- KPI CARDS --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['label' => 'Total Pelanggan', 'value' => $stats['total_pelanggan'], 'icon' => 'users', 'color' => 'primary',
                 'trend' => $stats['total_pelanggan'] > 0 ? 'Aktif: '.$stats['pelanggan_aktif'].' · Non-aktif: '.$stats['pelanggan_nonaktif'] : null, 'trendUp' => true],
                ['label' => 'Aktif', 'value' => $stats['pelanggan_aktif'], 'icon' => 'user-check', 'color' => 'success',
                 'trend' => $stats['total_pelanggan'] > 0 ? round($stats['pelanggan_aktif'] / $stats['total_pelanggan'] * 100).'% dari total' : null, 'trendUp' => true],
                ['label' => 'Non-Aktif', 'value' => $stats['pelanggan_nonaktif'], 'icon' => 'user-x', 'color' => 'danger',
                 'trend' => $stats['total_pelanggan'] > 0 ? round($stats['pelanggan_nonaktif'] / $stats['total_pelanggan'] * 100).'% dari total' : null, 'trendUp' => false],
                ['label' => 'Baru Bulan Ini', 'value' => $stats['pelanggan_baru_bulan_ini'], 'icon' => 'user-plus', 'color' => 'warning',
                 'trend' => $stats['pelanggan_baru_bulan_ini'] > 0 ? now()->translatedFormat('F Y') : null, 'trendUp' => true],
            ];
        @endphp
        @foreach($cards as $i => $c)
            <div class="col-12 col-sm-6 col-xl-3 animate-fade-in-up stagger-{{ $i + 1 }}">
                <div class="card-modern card-accent-top acc-{{ $c['color'] }} card-lift h-100">
                    <div class="card-body d-flex align-items-start gap-3" style="padding:1.25rem;">
                        <div class="icon-circle" style="background:var(--color-{{ $c['color'] }}-light);color:var(--color-{{ $c['color'] }});">
                            <i class="ti ti-{{ $c['icon'] }}" style="font-size:1.25rem;"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="mb-1" style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--color-text-muted);">{{ $c['label'] }}</p>
                            <h3 class="kpi-value mb-0" data-count="{{ $c['value'] }}">{{ number_format($c['value']) }}</h3>
                            @if($c['trend'])
                                <small class="d-inline-flex align-items-center gap-1 mt-1" style="font-size:0.75rem;color:var(--color-text-muted);">
                                    @if($c['trendUp'])
                                        <i class="ti ti-arrow-up" style="font-size:0.75rem;color:var(--color-success);"></i>
                                    @else
                                        <i class="ti ti-arrow-down" style="font-size:0.75rem;color:var(--color-danger);"></i>
                                    @endif
                                    {{ $c['trend'] }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- OVERVIEW ANALYTICS — 1 CARD 3 KOLOM --}}
    <div class="row g-3 mb-4">
        <div class="col-12 animate-fade-in-up stagger-5">
            <div class="card-modern">
                <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-bottom:1px solid var(--color-border-light);">
                    <h5 class="fw-semibold mb-0" style="font-size:0.9rem;color:var(--color-text);">
                        <i class="ti ti-layout-grid me-2" style="color:var(--color-primary);"></i>
                        Overview Analitik
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge badge-modern" style="background:var(--color-primary-light);color:var(--color-primary);font-size:0.7rem;">
                            {{ $pencatatanBulanIni['total_aktif'] ?? 0 }} Pelanggan Aktif
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        {{-- KIRI: Pertumbuhan Pelanggan --}}
                        <div class="col-md-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-semibold mb-0" style="font-size:0.8rem;color:var(--color-text);">
                                    <i class="ti ti-chart-line me-1" style="color:var(--color-primary);"></i>
                                    Pertumbuhan Pelanggan
                                </h6>
                                <span class="badge badge-modern" style="background:var(--color-border-light);color:var(--color-text-muted);font-size:0.65rem;font-weight:500;">12 Bulan</span>
                            </div>
                            <div class="flex-grow-1" style="min-height:240px;">
                                @if(count($pertumbuhan) > 0)
                                    <div style="position:relative;height:100%;width:100%;">
                                        <canvas id="chartPertumbuhan"></canvas>
                                    </div>
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                        <i class="ti ti-chart-line empty-state-icon" style="font-size:1.5rem;"></i>
                                        <p class="empty-state-text mt-2 mb-0" style="font-size:0.8rem;">Belum cukup data.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- TENGAH: Distribusi Wilayah --}}
                        <div class="col-md-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-semibold mb-0" style="font-size:0.8rem;color:var(--color-text);">
                                    <i class="ti ti-pie-chart me-1" style="color:var(--color-success);"></i>
                                    Distribusi Wilayah
                                </h6>
                            </div>
                            <div class="flex-grow-1 d-flex align-items-center justify-content-center" style="min-height:240px;">
                                @if(count($distribusiWilayah) > 0)
                                    <div style="position:relative;width:100%;max-width:220px;aspect-ratio:1/1;">
                                        <canvas id="chartWilayah"></canvas>
                                    </div>
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <i class="ti ti-pie-chart empty-state-icon" style="font-size:1.5rem;"></i>
                                        <p class="empty-state-text mt-2 mb-0" style="font-size:0.8rem;">Belum ada data.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- KANAN: Pencatatan Meter --}}
                        <div class="col-md-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-semibold mb-0" style="font-size:0.8rem;color:var(--color-text);">
                                    <i class="ti ti-clipboard-check me-1" style="color:var(--color-warning);"></i>
                                    Pencatatan Meter
                                </h6>
                                <span class="badge badge-modern" style="background:var(--color-border-light);color:var(--color-text-muted);font-size:0.65rem;font-weight:500;">
                                    {{ $pencatatanBulanIni['label'] ?? '' }}
                                </span>
                            </div>
                            @if(($pencatatanBulanIni['total_aktif'] ?? 0) > 0)
                                <div class="flex-grow-1 d-flex align-items-center gap-3" style="min-height:240px;">
                                    <div style="width:120px;height:120px;flex-shrink:0;">
                                        <canvas id="chartPencatatan"></canvas>
                                    </div>
                                    <div class="d-flex flex-column gap-3">
                                        <div>
                                            <div class="fw-bold" style="font-size:1.25rem;color:var(--color-text);line-height:1.2;">{{ $pencatatanBulanIni['tercatat'] }}</div>
                                            <div class="d-flex align-items-center gap-1 mt-1">
                                                <span style="width:8px;height:8px;border-radius:2px;background:#059669;flex-shrink:0;"></span>
                                                <small style="color:var(--color-text-muted);font-size:0.75rem;">Tercatat ({{ $pencatatanBulanIni['persentase_tercatat'] }}%)</small>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="font-size:1.25rem;color:var(--color-text);line-height:1.2;">{{ $pencatatanBulanIni['belum_tercatat'] }}</div>
                                            <div class="d-flex align-items-center gap-1 mt-1">
                                                <span style="width:8px;height:8px;border-radius:2px;background:#DC2626;flex-shrink:0;"></span>
                                                <small style="color:var(--color-text-muted);font-size:0.75rem;">Belum Tercatat ({{ $pencatatanBulanIni['persentase_belum'] }}%)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center" style="min-height:240px;">
                                    <i class="ti ti-clipboard-x empty-state-icon" style="font-size:1.5rem;"></i>
                                    <p class="empty-state-text mt-2 mb-0" style="font-size:0.8rem;">Belum ada data.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAP + GOLONGAN ROW --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8 animate-fade-in-up stagger-6">
            <div class="card-modern h-100">
                <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-bottom:1px solid var(--color-border-light);">
                    <h5 class="fw-semibold mb-0" style="font-size:0.9rem;color:var(--color-text);">
                        <i class="ti ti-map-pin me-2" style="color:var(--color-danger);"></i>
                        Peta Sebaran
                    </h5>
                    <small style="font-size:0.75rem;color:var(--color-text-muted);">
                        {{ count($koordinatPelanggan) }}/{{ $stats['total_pelanggan'] }} pelanggan
                        @if($stats['total_pelanggan'] - count($koordinatPelanggan) > 0)
                            · {{ $stats['total_pelanggan'] - count($koordinatPelanggan) }} tanpa koordinat
                        @endif
                    </small>
                </div>
                <div style="border-radius:0 0 var(--radius-lg) var(--radius-lg);overflow:hidden;">
                    @if(count($koordinatPelanggan) > 0)
                        <div id="mapDashboard" style="height:380px;"></div>
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center" style="height:380px;">
                            <i class="ti ti-map-pin empty-state-icon"></i>
                            <p class="empty-state-text mt-3 mb-0">Belum ada data koordinat pelanggan.</p>
                            <a href="{{ route('pelanggan.baru.create') }}" class="btn btn-modern btn-modern-primary btn-sm mt-3">Tambah Pelanggan</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4 animate-fade-in-up stagger-6">
            <div class="card-modern h-100">
                <div class="px-4 py-3" style="border-bottom:1px solid var(--color-border-light);">
                    <h5 class="fw-semibold mb-0" style="font-size:0.9rem;color:var(--color-text);">
                        <i class="ti ti-tag me-2" style="color:var(--color-warning);"></i>
                        Per Golongan
                    </h5>
                </div>
                <div class="card-body p-4 d-flex flex-column gap-4">
                    @if(count($distribusiGolongan) > 0)
                        @foreach($distribusiGolongan as $g)
                            @php $pct = $stats['total_pelanggan'] > 0 ? round($g['total'] / $stats['total_pelanggan'] * 100) : 0; @endphp
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-medium" style="font-size:0.85rem;color:var(--color-text);">{{ $g['kode'] ?: $g['golongan'] }}</span>
                                    <span style="font-size:0.8rem;color:var(--color-text-secondary);">{{ $g['total'] }} · {{ $pct }}%</span>
                                </div>

                                <div class="progress-modern">
                                    <div class="progress-bar-graded" role="progressbar" style="width:{{ $pct }}%;background:linear-gradient(90deg, #2563EB, #0EA5E9);border-radius:100px;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center flex-grow-1">
                            <i class="ti ti-tag empty-state-icon" style="font-size:2rem;"></i>
                            <p class="empty-state-text mt-2 mb-0">Belum ada data golongan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- TABLES ROW --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-5 animate-fade-in-up stagger-7">
            <div class="card-modern h-100">
                <div class="px-4 py-3" style="border-bottom:1px solid var(--color-border-light);">
                    <h5 class="fw-semibold mb-0" style="font-size:0.9rem;color:var(--color-text);">
                        <i class="ti ti-map me-2" style="color:var(--color-info);"></i>
                        Distribusi Wilayah
                    </h5>
                </div>
                <div class="p-0">
                    @if(count($distribusiWilayah) > 0)
                        <div style="max-height:360px;overflow-y:auto;">
                            <table class="table-modern" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th style="width:50%;">Wilayah</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($distribusiWilayah as $w)
                                        <tr>
                                            <td><span class="fw-medium">{{ $w['wilayah'] }}</span></td>
                                            <td class="text-end fw-semibold">{{ $w['total'] }}</td>
                                            <td class="text-end">
                                                <span class="badge badge-modern" style="background:var(--color-primary-light);color:var(--color-primary);">
                                                    {{ $stats['total_pelanggan'] > 0 ? round($w['total'] / $stats['total_pelanggan'] * 100) : 0 }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center py-5">
                            <i class="ti ti-map empty-state-icon"></i>
                            <p class="empty-state-text mt-2 mb-0">Belum ada data wilayah.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-7 animate-fade-in-up stagger-8">
            <div class="card-modern h-100">
                <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-bottom:1px solid var(--color-border-light);">
                    <h5 class="fw-semibold mb-0" style="font-size:0.9rem;color:var(--color-text);">
                        <i class="ti ti-activity me-2" style="color:var(--color-success);"></i>
                        Aktivitas Terbaru
                    </h5>
                    <a href="{{ route('pelanggan.baru.index') }}" class="btn btn-modern btn-modern-outline" style="font-size:0.8rem;padding:0.3rem 0.85rem;">
                        Lihat Semua &rarr;
                    </a>
                </div>
                <div class="p-0">
                    @if(count($aktivitasTerbaru) > 0)
                        <div style="max-height:360px;overflow-y:auto;">
                            <table class="table-modern" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th style="width:36px;">#</th>
                                        <th>Nama</th>
                                        <th>Wilayah</th>
                                        <th>Golongan</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($aktivitasTerbaru as $i => $p)
                                        <tr>
                                            <td style="font-size:0.8rem;color:var(--color-text-muted);">{{ $i + 1 }}</td>
                                            <td>
                                                <span class="fw-medium" style="font-size:0.85rem;color:var(--color-text);">{{ $p->nama }}</span>
                                                <small class="d-block" style="font-size:0.7rem;color:var(--color-text-muted);">{{ $p->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td style="font-size:0.85rem;color:var(--color-text-secondary);">{{ $p->wilayah?->wilayah ?? '-' }}</td>
                                            <td style="font-size:0.85rem;color:var(--color-text-secondary);">{{ $p->golongan?->nama ?? '-' }}</td>
                                            <td>
                                                @if($p->status === 'aktif')
                                                    <span class="badge badge-modern" style="background:var(--color-success-light);color:var(--color-success);">Aktif</span>
                                                @else
                                                    <span class="badge badge-modern" style="background:var(--color-danger-light);color:var(--color-danger);">Non-Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('pelanggan.show', $p->id) }}" class="btn btn-modern btn-modern-outline" style="font-size:0.75rem;padding:0.25rem 0.65rem;">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center py-5">
                            <i class="ti ti-inbox empty-state-icon"></i>
                            <p class="empty-state-text mt-2 mb-0">Belum ada data pelanggan.</p>
                            <a href="{{ route('pelanggan.baru.create') }}" class="btn btn-modern btn-modern-primary btn-sm mt-3">Tambah Pelanggan</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #mapDashboard { height: 380px; border-radius: 0; }
        #mapDashboard .leaflet-control-zoom { border: none !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important; border-radius: 8px !important; overflow: hidden; }
        #mapDashboard .leaflet-control-zoom a { border: none !important; color: #374151; width: 32px; height: 32px; line-height: 32px; font-size: 16px; }
        #mapDashboard .leaflet-control-zoom a:hover { background: #F3F4F6; }
        #mapDashboard .leaflet-tile { max-width: none !important; }
        .leaflet-popup-content-wrapper { border-radius: 14px !important; box-shadow: 0 8px 30px rgba(0,0,0,0.15) !important; }
        .leaflet-popup-content { margin: 20px 24px !important; font-family: var(--font); font-size: 14px; line-height: 1.5; min-width: 300px; }
        .leaflet-popup-tip { box-shadow: none !important; }
        .leaflet-popup-close-button { top: 10px !important; right: 10px !important; width: 24px !important; height: 24px !important; font-size: 16px !important; color: #94A3B8 !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 6px !important; transition: background 0.15s !important; }
        .leaflet-popup-close-button:hover { background: #F1F5F9 !important; color: #0F172A !important; }
        @media (max-width: 768px) { #mapDashboard { height: 260px; } }
    </style>
@endpush

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ==== ANIMATED COUNTER ====
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-count]').forEach(function(el) {
                var target = parseInt(el.getAttribute('data-count'));
                if (isNaN(target)) return;
                var duration = 800;
                var start = performance.now();
                function step(now) {
                    var progress = Math.min((now - start) / duration, 1);
                    var eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * target).toLocaleString();
                    if (progress < 1) requestAnimationFrame(step);
                    else el.textContent = target.toLocaleString();
                }
                requestAnimationFrame(step);
            });
        });

        // ==== LINE CHART ====
        (function() {
            var data = @json($pertumbuhan);
            var ctx = document.getElementById('chartPertumbuhan');
            if (!ctx || data.length === 0) return;
            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.map(function(d) { return d.label; }),
                    datasets: [{
                        label: 'Pelanggan Baru',
                        data: data.map(function(d) { return d.total; }),
                        borderColor: '#2563EB',
                        backgroundColor: function(ctx) {
                            var g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 280);
                            g.addColorStop(0, 'rgba(37,99,235,0.25)');
                            g.addColorStop(0.5, 'rgba(37,99,235,0.08)');
                            g.addColorStop(1, 'rgba(37,99,235,0.01)');
                            return g;
                        },
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#2563EB',
                        pointBorderWidth: 1.5,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: '#2563EB',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 1.5,
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#0F172A',
                            bodyColor: '#64748B',
                            borderColor: '#E2E8F0',
                            borderWidth: 1,
                            cornerRadius: 10,
                            padding: 12,
                            titleFont: { size: 12, weight: '600' },
                            bodyFont: { size: 12 },
                            callbacks: { label: function(ctx) { return ctx.parsed.y + ' pelanggan'; } }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0, color: '#94A3B8', font: { size: 11 } },
                            grid: { color: '#F1F5F9', drawBorder: false }
                        },
                        x: {
                            ticks: { color: '#94A3B8', font: { size: 10 }, maxTicksLimit: 6 },
                            grid: { display: false }
                        }
                    }
                }
            });
        })();

        // ==== DOUGHNUT CHART ====
        (function() {
            var data = @json($distribusiWilayah);
            var ctx = document.getElementById('chartWilayah');
            if (!ctx || data.length === 0) return;
            var colors = ['#2563EB','#059669','#D97706','#DC2626','#0EA5E9','#7C3AED','#F97316','#14B8A6'];
            new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: data.map(function(d) { return d.wilayah; }),
                    datasets: [{
                        data: data.map(function(d) { return d.total; }),
                        backgroundColor: colors.slice(0, data.length),
                        borderWidth: 3,
                        borderColor: '#fff',
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 8,
                                padding: 12,
                                font: { size: 10 },
                                color: '#64748B',
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#0F172A',
                            bodyColor: '#64748B',
                            borderColor: '#E2E8F0',
                            borderWidth: 1,
                            cornerRadius: 10,
                            padding: 12,
                            titleFont: { size: 12, weight: '600' },
                            bodyFont: { size: 12 },
                            callbacks: { label: function(ctx) { return ctx.label + ': ' + ctx.parsed + ' pelanggan'; } }
                        }
                    }
                }
            });
        })();

        // ==== DOUGHNUT CHART - PENCATATAN METER ====
        (function() {
            var data = @json($pencatatanBulanIni);
            var ctx = document.getElementById('chartPencatatan');
            if (!ctx || !data || data.total_aktif === 0) return;
            new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Tercatat', 'Belum Tercatat'],
                    datasets: [{
                        data: [data.persentase_tercatat, data.persentase_belum],
                        backgroundColor: ['#059669', '#DC2626'],
                        borderWidth: 3,
                        borderColor: '#fff',
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#0F172A',
                            bodyColor: '#64748B',
                            borderColor: '#E2E8F0',
                            borderWidth: 1,
                            cornerRadius: 10,
                            padding: 12,
                            titleFont: { size: 12, weight: '600' },
                            bodyFont: { size: 12 },
                            callbacks: {
                                label: function(ctx) {
                                    if (ctx.dataIndex === 0) return 'Tercatat: ' + data.tercatat + ' pelanggan (' + data.persentase_tercatat + '%)';
                                    return 'Belum Tercatat: ' + data.belum_tercatat + ' pelanggan (' + data.persentase_belum + '%)';
                                }
                            }
                        }
                    }
                }
            });
        })();

        // ==== LEAFLET MAP ====
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.getElementById('mapDashboard');
            if (!el) return;

            var map = L.map('mapDashboard', {
                zoomControl: false,
                scrollWheelZoom: false
            }).setView([4.85, 97.0], 9);

            L.control.zoom({ position: 'bottomleft' }).addTo(map);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            map.whenReady(function() {
                map.invalidateSize();
            });

            var data = @json($koordinatPelanggan);

            var iconAktif = L.divIcon({
                className: '',
                html: '<div style="width:14px;height:14px;background:#059669;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,0.25);transition:transform 200ms;"></div>',
                iconSize: [14, 14], iconAnchor: [7, 7]
            });
            var iconNonAktif = L.divIcon({
                className: '',
                html: '<div style="width:14px;height:14px;background:#DC2626;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,0.25);transition:transform 200ms;"></div>',
                iconSize: [14, 14], iconAnchor: [7, 7]
            });

            data.forEach(function(p) {
                var icon = p.status === 'aktif' ? iconAktif : iconNonAktif;
                var marker = L.marker([p.lat, p.long], { icon: icon }).addTo(map);
                marker.bindPopup(
                    '<div style="min-width:320px;">' +
                        '<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:12px;">' +
                            '<div>' +
                                '<h6 style="margin:0 0 2px;font-weight:700;font-size:17px;color:#0F172A;">' + p.nama + '</h6>' +
                                '<span style="font-size:12px;color:#94A3B8;">' + (p.status === 'aktif' ? 'Pelanggan Aktif' : 'Pelanggan Non-Aktif') + '</span>' +
                            '</div>' +
                            '<span style="flex-shrink:0;padding:4px 14px;border-radius:100px;font-size:12px;font-weight:600;' +
                                (p.status === 'aktif'
                                    ? 'background:#ECFDF5;color:#059669;'
                                    : 'background:#FEF2F2;color:#DC2626;') +
                            '">' + (p.status === 'aktif' ? 'Aktif' : 'Non-Aktif') + '</span>' +
                        '</div>' +
                        '<div style="background:#F8FAFC;border:1px solid #F1F5F9;border-radius:10px;padding:12px 14px;margin-bottom:14px;">' +
                            '<p style="margin:0 0 6px;font-size:13px;color:#64748B;"><span style="color:#374151;font-weight:600;">Alamat:</span> ' + (p.alamat || '-') + '</p>' +
                            '<p style="margin:0;font-size:13px;color:#64748B;"><span style="color:#374151;font-weight:600;">Wilayah:</span> ' + p.wilayah + '</p>' +
                        '</div>' +
                        '<a href="' + p.url + '" style="display:block;text-align:center;padding:9px 20px;background:#2563EB;color:#fff;text-decoration:none;border-radius:10px;font-size:13px;font-weight:600;transition:background 0.15s;">Lihat Detail &rarr;</a>' +
                    '</div>',
                    { maxWidth: 380, minWidth: 320 }
                );
                marker.on('mouseover', function() {
                    this.openPopup();
                });
            });

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
        });

        // ==== AUTO-REFRESH NOTIFIKASI ====
        function refreshNotifikasi() {
            fetch('{{ route("dashboard.notifikasi") }}')
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    var container = document.getElementById('notifikasiContainer');
                    if (!container) return;
                    if (data.length === 0) { container.innerHTML = ''; return; }
                    container.innerHTML = data.map(function(n) {
                        return '<div class="alert d-flex align-items-center gap-3 px-4 py-3 mb-3 animate-fade-in" style="background:var(--bg-card);border:1px solid var(--color-border);border-radius:var(--radius-md);border-left:4px solid var(--color-' + n.type + ');box-shadow:var(--shadow-sm);">' +
                            '<i class="ti ti-' + n.icon + '" style="font-size:1.3rem;color:var(--color-' + n.type + ');"></i>' +
                            '<span class="flex-grow-1" style="font-size:0.9rem;color:var(--color-text);">' + n.pesan + '</span>' +
                            '<a href="' + n.url + '" class="btn btn-modern btn-modern-outline flex-shrink-0" style="font-size:0.8rem;padding:0.3rem 0.85rem;">Lihat</a>' +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size:0.75rem;"></button>' +
                        '</div>';
                    }).join('');
                });
        }
        setInterval(refreshNotifikasi, 60000);
    </script>
@endpush
