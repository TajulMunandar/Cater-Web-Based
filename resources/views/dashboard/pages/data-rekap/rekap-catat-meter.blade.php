@extends('dashboard.partials.main')

@section('title', 'Rekap Catat Meter')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
    body {
        background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
    }
    .card-rekap {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        overflow: hidden;
    }
    .card-rekap .card-body {
        padding: 1.5rem;
    }

    /* Tabs */
    .nav-pills-rekap {
        display: flex;
        gap: 0.5rem;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 12px;
        margin: -0.5rem 0 1.25rem 0;
    }
    .nav-pills-rekap .nav-link {
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        color: #64748b;
        transition: all 0.2s ease;
    }
    .nav-pills-rekap .nav-link:hover {
        color: #3b82f6;
        background: rgba(59,130,246,0.08);
    }
    .nav-pills-rekap .nav-link.active {
        background: #3b82f6;
        color: #fff;
        box-shadow: 0 2px 8px rgba(59,130,246,0.35);
    }

    /* Filter */
    .filter-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .filter-wrapper label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        white-space: nowrap;
    }
    .filter-wrapper .form-select-sm {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.35rem 2rem 0.35rem 0.75rem;
        font-size: 0.85rem;
        background-color: #fff;
        min-width: 100px;
    }
    .filter-wrapper .form-select-sm:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
    }
    .btn-filter-sm {
        padding: 0.35rem 1rem;
        font-size: 0.85rem;
        border-radius: 8px;
        border: none;
        background: #3b82f6;
        color: #fff;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-filter-sm:hover {
        background: #2563eb;
        box-shadow: 0 2px 8px rgba(59,130,246,0.3);
    }

    /* Summary Cards */
    .summary-card-rekap {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 1.25rem;
        height: 100%;
        transition: all 0.25s ease;
    }
    .summary-card-rekap:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .summary-icon-rekap {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .summary-value-rekap {
        font-size: 1.65rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.2;
        color: #0f172a;
    }
    .summary-label-rekap {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
        margin-top: 2px;
    }

    /* Chart */
    .chart-wrapper {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 1.25rem;
    }
    .chart-container-rekap {
        position: relative;
        height: 260px;
        width: 100%;
    }

    /* Pivot Table */
    .table-pivot-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }
    .table-pivot {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        font-size: 13px;
        min-width: max-content;
    }
    .table-pivot thead th {
        position: sticky;
        top: 0;
        z-index: 20;
        background: #1e3a8a;
        color: #fff;
        font-weight: 600;
        padding: 0.65rem 0.5rem;
        text-align: center;
        border: 1px solid #1e3a8a;
        white-space: nowrap;
    }
    .table-pivot thead th.col-header-day {
        background: #3b82f6;
        border-color: #3b82f6;
        font-weight: 500;
        font-size: 12px;
        min-width: 48px;
    }
    .table-pivot thead th.col-header-total {
        background: #1e3a8a;
        border-color: #1e3a8a;
        min-width: 70px;
    }
    .table-pivot tbody td {
        padding: 0.5rem 0.4rem;
        text-align: center;
        border: 1px solid #e5e7eb;
        vertical-align: middle;
        font-size: 13px;
        transition: background 0.15s ease;
    }
    .table-pivot tbody tr:nth-child(odd) td {
        background: #ffffff;
    }
    .table-pivot tbody tr:nth-child(even) td {
        background: #f8faff;
    }
    .table-pivot tbody tr:hover td {
        background: #fffbeb !important;
    }
    .table-pivot tbody td.cell-empty {
        color: #d1d5db;
    }
    .table-pivot tbody td.cell-highlight {
        background: #dcfce7 !important;
        color: #166534;
        font-weight: 600;
    }
    .table-pivot tbody td.cell-petugas {
        position: sticky;
        left: 0;
        z-index: 10;
        background: #fff !important;
        font-weight: 600;
        text-align: left;
        padding: 0.5rem 0.75rem;
        min-width: 160px;
        border-right: 2px solid #e5e7eb;
    }
    .table-pivot tbody tr:hover td.cell-petugas {
        background: #fffbeb !important;
    }
    .table-pivot tbody td.cell-total-row {
        font-weight: 700;
        color: #1e3a8a;
        background: #eff6ff !important;
        border-left: 2px solid #bfdbfe;
    }
    .table-pivot tbody tr:hover td.cell-total-row {
        background: #fffbeb !important;
    }
    .table-pivot tfoot td {
        padding: 0.5rem 0.75rem;
        border: 1px solid #e5e7eb;
        font-weight: 700;
        font-size: 13px;
        text-align: center;
        background: #f0f4ff;
        color: #1e3a8a;
    }
    .table-pivot tfoot td.foot-label {
        position: sticky;
        left: 0;
        z-index: 10;
        background: #f0f4ff !important;
        text-align: left;
    }
    .table-pivot tfoot td.foot-total-all {
        background: #1e3a8a;
        color: #fff;
        border-color: #1e3a8a;
    }

    @media (max-width: 768px) {
        .summary-value-rekap { font-size: 1.3rem; }
        .summary-card-rekap { padding: 1rem; }
        .summary-icon-rekap { width: 38px; height: 38px; font-size: 1rem; }
        .filter-wrapper { flex-wrap: wrap; }
        .card-rekap .card-body { padding: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="row mb-3 align-items-center">
    <div class="col">
        <h3 class="mb-0" style="font-weight:700;color:#0f172a;">Rekap Catat Meter</h3>
        <p class="text-muted mt-1 mb-0" style="font-size:0.85rem;">Ringkasan pencatatan meter per petugas per tanggal</p>
    </div>
    <div class="col-auto">
        <form method="GET" action="{{ route('rekap.index') }}" class="filter-wrapper">
            <label for="bulan">Bulan</label>
            <select name="bulan" id="bulan" class="form-select form-select-sm">
                @foreach(range(1,12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == sprintf('%02d', $m) ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month((int)$m)->format('F') }}
                    </option>
                @endforeach
            </select>
            <label for="tahun">Tahun</label>
            <select name="tahun" id="tahun" class="form-select form-select-sm">
                @foreach(range(now()->year - 2, now()->year + 1) as $th)
                    <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>{{ $th }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-filter-sm">
                <i class="fas fa-filter me-1"></i>Tampilkan
            </button>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-lg-4 col-6">
        <div class="summary-card-rekap d-flex align-items-center gap-3">
            <div class="summary-icon-rekap" style="background:#eff6ff;color:#3b82f6;">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <div class="summary-value-rekap">{{ number_format($totalCatatan, 0, ',', '.') }}</div>
                <div class="summary-label-rekap">Total Catatan Bulan Ini</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="summary-card-rekap d-flex align-items-center gap-3">
            <div class="summary-icon-rekap" style="background:#f0f9ff;color:#0ea5e9;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="summary-value-rekap">{{ $totalPetugas }}</div>
                <div class="summary-label-rekap">Total Petugas Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="summary-card-rekap d-flex align-items-center gap-3">
            <div class="summary-icon-rekap" style="background:#ecfdf5;color:#059669;">
                <i class="fas fa-house-user"></i>
            </div>
            <div>
                <div class="summary-value-rekap">{{ number_format($totalPelanggan, 0, ',', '.') }}</div>
                <div class="summary-label-rekap">Total Pelanggan Dicatat</div>
            </div>
        </div>
    </div>
</div>



<!-- Tabs + Table -->
<div class="row mb-4">
    <div class="col">
        <div class="card-rekap">
            <div class="card-body">
                <ul class="nav nav-pills nav-pills-rekap">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('rekap.index', ['bulan' => $bulan, 'tahun' => $tahun]) }}">
                            <i class="fas fa-calendar-alt me-1"></i> Rekap Catat Meter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('rekap.wilayah') }}">
                            <i class="fas fa-map me-1"></i> Rekap Wilayah
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('rekap.kondisi') }}">
                            <i class="fas fa-check-circle me-1"></i> Rekap Kondisi
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-semibold mb-0" style="font-size:0.9rem;color:#0f172a;">
                        <i class="fas fa-table me-2" style="color:#3b82f6;"></i>
                        Data Pencatatan {{ \Carbon\Carbon::create()->month((int)$bulan)->format('F') }} {{ $tahun }}
                    </h6>
                    <span class="text-muted" style="font-size:0.75rem;">
                        @if($petugas->count() > 0)
                            {{ $petugas->count() }} petugas
                        @endif
                    </span>
                </div>

                @if($petugas->count() > 0)
                <div class="table-pivot-wrapper">
                    <table class="table-pivot">
                        <thead>
                            <tr>
                                <th style="min-width:160px;text-align:left;padding-left:0.75rem;">Nama Petugas</th>
                                @foreach(range(1, $daysInMonth) as $day)
                                    <th class="col-header-day">{{ sprintf('%02d', $day) }}</th>
                                @endforeach
                                <th class="col-header-total">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $highestPerDay = [];
                                foreach (range(1, $daysInMonth) as $day) {
                                    $maxVal = 0;
                                    foreach ($petugas as $p) {
                                        $val = $matrix[$p->id][$day] ?? 0;
                                        if ($val > $maxVal) $maxVal = $val;
                                    }
                                    $highestPerDay[$day] = $maxVal;
                                }
                            @endphp
                            @foreach($petugas as $p)
                                <tr>
                                    <td class="cell-petugas">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:28px;height:28px;border-radius:50%;background:#e0e7ff;color:#3b82f6;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;flex-shrink:0;">
                                                {{ strtoupper(substr($p->nama, 0, 1)) }}
                                            </div>
                                            <span>{{ $p->nama }}</span>
                                        </div>
                                    </td>
                                    @foreach(range(1, $daysInMonth) as $day)
                                        @php $val = $matrix[$p->id][$day] ?? 0; @endphp
                                        <td class="{{ $val === 0 ? 'cell-empty' : '' }} {{ ($highestPerDay[$day] > 0 && $val === $highestPerDay[$day]) ? 'cell-highlight' : '' }}">
                                            {{ $val ?: '-' }}
                                        </td>
                                    @endforeach
                                    <td class="cell-total-row">{{ $petugasTotals[$p->id] ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="foot-label"><strong>Total</strong></td>
                                @foreach(range(1, $daysInMonth) as $day)
                                    <td>{{ $dayTotals[$day] ?: '-' }}</td>
                                @endforeach
                                <td class="foot-total-all">{{ array_sum($dayTotals) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox text-muted" style="font-size:3rem;opacity:0.4;"></i>
                        <p class="text-muted mt-3">Belum ada data pencatatan untuk periode ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="row  mt-2">
    <div class="col">
        <div class="chart-wrapper">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-semibold mb-0" style="font-size:0.9rem;color:#0f172a;">
                    <i class="fas fa-chart-line me-2" style="color:#3b82f6;"></i>Tren Pencatatan Harian
                </h6>
                <span class="text-muted" style="font-size:0.75rem;">
                    {{ \Carbon\Carbon::create()->month((int)$bulan)->format('F') }} {{ $tahun }}
                </span>
            </div>
            <div class="chart-container-rekap">
                <canvas id="chartTren"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('chartTren').getContext('2d');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.map(function(d) { return 'Tgl ' + d.hari; }),
                datasets: [{
                    label: 'Total Catatan',
                    data: chartData.map(function(d) { return d.total; }),
                    borderColor: '#3b82f6',
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const { ctx, chartArea } = chart;
                        if (!chartArea) return 'transparent';
                        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                        gradient.addColorStop(0, 'rgba(59,130,246,0.35)');
                        gradient.addColorStop(1, 'rgba(59,130,246,0.02)');
                        return gradient;
                    },
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    borderWidth: 2.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { size: 12 },
                        bodyFont: { size: 13 },
                        cornerRadius: 8,
                        padding: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { size: 11 },
                            color: '#94a3b8'
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: {
                            font: { size: 10 },
                            color: '#94a3b8',
                            maxRotation: 45
                        },
                        grid: { display: false }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    });
</script>
@endpush
