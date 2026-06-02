@extends('dashboard.partials.main')

@section('title', 'Rekap Wilayah')

@push('head')
<style>
    body {
        background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
    }

    /* Card utama */
    .card-rekap {
        background: #fff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        overflow: hidden;
    }
    .card-rekap .card-body {
        padding: 1.5rem;
    }

    /* Tab Navigation */
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
        color: #0f766e;
        background: rgba(15,118,110,0.08);
    }
    .nav-pills-rekap .nav-link.active {
        background: #0f766e;
        color: #fff;
        box-shadow: 0 2px 8px rgba(15,118,110,0.35);
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
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15,118,110,0.12);
    }
    .btn-filter-sm {
        padding: 0.35rem 1rem;
        font-size: 0.85rem;
        border-radius: 8px;
        border: none;
        background: #0f766e;
        color: #fff;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-filter-sm:hover {
        background: #115e59;
        box-shadow: 0 2px 8px rgba(15,118,110,0.3);
    }

    /* Summary Cards */
    .summary-card-rekap {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem;
        height: 100%;
        border-left: 4px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
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
    .summary-value-rekap small {
        font-size: 0.85rem;
        font-weight: 500;
        color: #475569;
        letter-spacing: 0;
    }
    .summary-label-rekap {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
        margin-top: 2px;
    }

    /* Search */
    .search-wrapper {
        position: relative;
    }
    .search-wrapper .form-control-sm {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.4rem 0.75rem 0.4rem 2rem;
        font-size: 0.85rem;
        background: #fff;
    }
    .search-wrapper .form-control-sm:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15,118,110,0.12);
    }
    .search-wrapper .search-icon {
        position: absolute;
        left: 0.65rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.8rem;
        pointer-events: none;
    }

    /* ===== PIVOT TABLE ===== */
    .table-pivot-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        max-height: 72vh;
        overflow-y: auto;
    }
    .table-pivot {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        font-size: 13px;
        min-width: max-content;
    }

    /* === THEAD === */
    .table-pivot thead {
        position: sticky;
        top: 0;
        z-index: 30;
    }
    .table-pivot thead th {
        font-weight: 600;
        padding: 0.5rem;
        text-align: center;
        white-space: nowrap;
        border: 1px solid #e5e7eb;
        font-size: 12px;
    }
    .table-pivot thead th.header-wilayah {
        background: #0f766e;
        color: #fff;
        font-size: 13px;
        text-align: center;
        border-color: #0f766e;
        position: sticky;
        left: 0;
        z-index: 32;
    }
    .table-pivot thead th.header-tanggal {
        background: #0f766e;
        color: #fff;
        font-size: 13px;
        text-align: center;
        border-color: #0f766e;
    }
    .table-pivot thead th.header-total {
        background: #0f766e;
        color: #fff;
        font-size: 13px;
        border-color: #0f766e;
        min-width: 80px;
    }

    .table-pivot thead th.sub-header {
        background: #ccfbf1;
        color: #134e4a;
        font-weight: 700;
        font-size: 12px;
        border-color: #e5e7eb;
        padding: 0.45rem 0.35rem;
        min-width: 46px;
    }
    .table-pivot thead th.sub-header-kode {
        background: #ccfbf1;
        color: #134e4a;
        font-weight: 700;
        font-size: 12px;
        border-color: #e5e7eb;
        min-width: 80px;
        text-align: center;
        position: sticky;
        left: 0;
        z-index: 27;
    }
    .table-pivot thead th.sub-header-nama {
        background: #ccfbf1;
        color: #134e4a;
        font-weight: 700;
        font-size: 12px;
        border-color: #e5e7eb;
        min-width: 180px;
        text-align: left;
        padding-left: 0.75rem;
        position: sticky;
        left: 80px;
        z-index: 27;
    }

    /* === TBODY === */
    .table-pivot tbody td {
        padding: 0.45rem 0.35rem;
        text-align: center;
        border: 1px solid #e5e7eb;
        vertical-align: middle;
        font-size: 13px;
        transition: background 0.15s ease;
    }

    /* Sticky columns */
    .table-pivot tbody td.col-kode {
        position: sticky;
        left: 0;
        z-index: 10;
        background: #f8fafc;
        font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
        font-size: 12px;
        color: #475569;
        font-weight: 500;
        text-align: center;
        min-width: 80px;
        width: 80px;
        border-right: 2px solid #e5e7eb;
    }
    .table-pivot tbody td.col-nama {
        position: sticky;
        left: 80px;
        z-index: 10;
        background: #ffffff;
        font-weight: 500;
        color: #1e293b;
        text-align: left;
        padding: 0.45rem 0.75rem;
        min-width: 180px;
        border-right: 2px solid #e5e7eb;
    }

    /* Row colors */
    .table-pivot tbody tr:nth-child(odd) td {
        background: #ffffff;
    }
    .table-pivot tbody tr:nth-child(odd) td.col-kode {
        background: #f8fafc;
    }
    .table-pivot tbody tr:nth-child(odd) td.col-nama {
        background: #ffffff;
    }
    .table-pivot tbody tr:nth-child(even) td {
        background: #f0fdfa;
    }
    .table-pivot tbody tr:nth-child(even) td.col-kode {
        background: #f0fdfa;
    }
    .table-pivot tbody tr:nth-child(even) td.col-nama {
        background: #f0fdfa;
    }

    .table-pivot tbody tr:hover td {
        background: #e6fffa !important;
        transition: background 0.15s ease;
    }
    .table-pivot tbody tr:hover td.col-kode,
    .table-pivot tbody tr:hover td.col-nama {
        background: #e6fffa !important;
    }

    /* Cell values */
    .table-pivot tbody td.cell-zero {
        color: #cbd5e1;
    }
    .table-pivot tbody td.cell-highlight {
        background: #dcfce7 !important;
        color: #15803d;
        font-weight: 600;
        border-radius: 6px;
    }
    .table-pivot tbody tr:nth-child(odd) td.cell-highlight {
        background: #dcfce7 !important;
    }
    .table-pivot tbody tr:nth-child(even) td.cell-highlight {
        background: #dcfce7 !important;
    }
    .table-pivot tbody tr:hover td.cell-highlight {
        background: #bbf7d0 !important;
    }

    /* Total column in body */
    .table-pivot tbody td.col-total {
        background: #f0fdf4 !important;
        color: #15803d;
        font-weight: 700;
        border-left: 2px solid #e5e7eb;
    }
    .table-pivot tbody tr:nth-child(odd) td.col-total {
        background: #f0fdf4 !important;
    }
    .table-pivot tbody tr:nth-child(even) td.col-total {
        background: #f0fdf4 !important;
    }
    .table-pivot tbody tr:hover td.col-total {
        background: #dcfce7 !important;
    }

    /* === TFOOT === */
    .table-pivot tfoot td {
        padding: 0.55rem 0.5rem;
        border: 1px solid #e5e7eb;
        font-weight: 700;
        font-size: 13px;
        text-align: center;
        background: #134e4a;
        color: #fff;
    }
    .table-pivot tfoot td.foot-kode {
        position: sticky;
        left: 0;
        z-index: 10;
        background: #134e4a;
        min-width: 80px;
        font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
    }
    .table-pivot tfoot td.foot-nama {
        position: sticky;
        left: 80px;
        z-index: 10;
        background: #134e4a;
        min-width: 180px;
        text-align: left;
        padding-left: 0.75rem;
    }
    .table-pivot tfoot td.foot-total {
        background: #0f766e;
        color: #fff;
        border-color: #0f766e;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .summary-value-rekap { font-size: 1.3rem; }
        .summary-card-rekap { padding: 1rem; }
        .summary-icon-rekap { width: 38px; height: 38px; font-size: 1rem; }
        .filter-wrapper { flex-wrap: wrap; }
        .card-rekap .card-body { padding: 1rem; }
        .table-pivot tbody td.col-nama { min-width: 140px; }
    }
</style>
@endpush

@section('content')
<div class="row mb-3 align-items-center">
    <div class="col">
        <h3 class="mb-0" style="font-weight:700;color:#0f172a;">Rekap Wilayah</h3>
        <p class="text-muted mt-1 mb-0" style="font-size:0.85rem;">Ringkasan pencatatan pelanggan per wilayah per tanggal</p>
    </div>
    <div class="col-auto">
        <form method="GET" action="{{ route('rekap.wilayah') }}" class="filter-wrapper">
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

<div class="row g-3 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="summary-card-rekap d-flex align-items-center gap-3" style="border-left-color:#0f766e;">
            <div class="summary-icon-rekap" style="background:#f0fdfa;color:#0f766e;">
                <i class="fas fa-map-pin"></i>
            </div>
            <div>
                <div class="summary-value-rekap">{{ number_format($totalWilayahAktif, 0, ',', '.') }}</div>
                <div class="summary-label-rekap">Total Wilayah Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="summary-card-rekap d-flex align-items-center gap-3" style="border-left-color:#3b82f6;">
            <div class="summary-icon-rekap" style="background:#eff6ff;color:#3b82f6;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="summary-value-rekap">{{ number_format($totalPelangganDicatat, 0, ',', '.') }}</div>
                <div class="summary-label-rekap">Total Pelanggan Dicatat Bulan Ini</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="summary-card-rekap d-flex align-items-center gap-3" style="border-left-color:#8b5cf6;">
            <div class="summary-icon-rekap" style="background:#f5f3ff;color:#8b5cf6;">
                <i class="fas fa-calendar"></i>
            </div>
            <div>
                <div class="summary-value-rekap">
                    {{ $tanggalTerbanyak !== '-' ? \Carbon\Carbon::create()->day((int)$tanggalTerbanyak)->format('j') : '-' }}
                    <small>{{ $tanggalTerbanyak !== '-' ? '(' . number_format($maxCatatanTanggal, 0, ',', '.') . ' catatan)' : '' }}</small>
                </div>
                <div class="summary-label-rekap">Tanggal Pencatatan Terbanyak</div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col">
        <div class="card-rekap">
            <div class="card-body">
                <ul class="nav nav-pills nav-pills-rekap">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('rekap.index', ['bulan' => $bulan, 'tahun' => $tahun]) }}">
                            <i class="fas fa-calendar-alt me-1"></i> Rekap Catat Meter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('rekap.wilayah', ['bulan' => $bulan, 'tahun' => $tahun]) }}">
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
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchWilayah" class="form-control form-control-sm" placeholder="Cari wilayah..." style="padding-left:2rem;width:240px;">
                    </div>
                    <span class="text-muted" style="font-size:0.75rem;">
                        {{ \Carbon\Carbon::create()->month((int)$bulan)->format('F') }} {{ $tahun }}
                        &middot; {{ $semuaWilayah->count() }} wilayah
                    </span>
                </div>

                @php
                    $highestPerDay = [];
                    foreach (range(1, $daysInMonth) as $day) {
                        $maxVal = 0;
                        foreach ($semuaWilayah as $w) {
                            $val = $matrix[$w->id][$day] ?? 0;
                            if ($val > $maxVal) $maxVal = $val;
                        }
                        $highestPerDay[$day] = $maxVal;
                    }
                    $grandTotal = array_sum($dayTotals);
                @endphp

                @if($semuaWilayah->count() > 0)
                <div class="table-pivot-wrapper">
                    <table class="table-pivot" id="rekapTable">
                        <thead>
                            <tr>
                                <th colspan="2" class="header-wilayah" style="text-align:center;">Wilayah</th>
                                <th colspan="{{ $daysInMonth }}" class="header-tanggal">Tanggal</th>
                                <th rowspan="2" class="header-total">Total</th>
                            </tr>
                            <tr>
                                <th class="sub-header-kode">Kode</th>
                                <th class="sub-header-nama">Nama Wilayah</th>
                                @foreach(range(1, $daysInMonth) as $day)
                                    <th class="sub-header">{{ sprintf('%02d', $day) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($semuaWilayah as $w)
                                @php $totalWil = $wilayahTotals[$w->id] ?? 0; @endphp
                                <tr data-wilayah="{{ strtolower($w->wilayah) }} {{ strtolower($w->kode) }}">
                                    <td class="col-kode">{{ $w->kode }}</td>
                                    <td class="col-nama">{{ $w->wilayah }}</td>
                                    @foreach(range(1, $daysInMonth) as $day)
                                        @php
                                            $val = $matrix[$w->id][$day] ?? 0;
                                            $isHighest = ($highestPerDay[$day] > 0 && $val === $highestPerDay[$day]);
                                        @endphp
                                        <td class="{{ $val === 0 ? 'cell-zero' : '' }} {{ $isHighest ? 'cell-highlight' : '' }}">
                                            {{ $val ?: '0' }}
                                        </td>
                                    @endforeach
                                    <td class="col-total">{{ $totalWil ?: 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="foot-kode"></td>
                                <td class="foot-nama" style="text-align:left;">Total Keseluruhan</td>
                                @foreach(range(1, $daysInMonth) as $day)
                                    <td>{{ $dayTotals[$day] ?: 0 }}</td>
                                @endforeach
                                <td class="foot-total">{{ $grandTotal ?: 0 }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox text-muted" style="font-size:3rem;opacity:0.4;"></i>
                        <p class="text-muted mt-3">Belum ada data wilayah</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchWilayah');
        const tableRows = document.querySelectorAll('#rekapTable tbody tr');

        searchInput.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            tableRows.forEach(function(row) {
                const wil = row.getAttribute('data-wilayah');
                row.style.display = wil.includes(q) ? '' : 'none';
            });
        });
    });
</script>
@endpush
