@extends('dashboard.partials.main')

@section('title', 'Rekap Kondisi')

@push('head')
<style>
    body {
        background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
    }

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
        color: #4338ca;
        background: rgba(67,56,202,0.08);
    }
    .nav-pills-rekap .nav-link.active {
        background: #4338ca;
        color: #fff;
        box-shadow: 0 2px 8px rgba(67,56,202,0.35);
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
        border-color: #4338ca;
        box-shadow: 0 0 0 3px rgba(67,56,202,0.12);
    }
    .btn-filter-sm {
        padding: 0.35rem 1rem;
        font-size: 0.85rem;
        border-radius: 8px;
        border: none;
        background: #4338ca;
        color: #fff;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-filter-sm:hover {
        background: #3730a3;
        box-shadow: 0 2px 8px rgba(67,56,202,0.3);
    }

    /* Summary Cards */
    .summary-card-rekap {
        border-radius: 12px;
        padding: 1.25rem;
        height: 100%;
        transition: all 0.25s ease;
    }
    .summary-card-rekap:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .summary-icon-rekap {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .summary-value-rekap {
        font-size: 1.65rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.2;
        color: #0f172a;
    }
    .summary-value-rekap .sub-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
        letter-spacing: 0;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 180px;
    }
    .summary-value-rekap .sub-count {
        font-size: 0.75rem;
        font-weight: 500;
        color: #64748b;
        letter-spacing: 0;
    }
    .summary-label-rekap {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
        margin-top: 2px;
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
        padding: 0.5rem 0.4rem;
        text-align: center;
        white-space: nowrap;
        border: 1px solid #e5e7eb;
        font-size: 12px;
    }
    .table-pivot thead th.header-petugas {
        background: #4338ca;
        color: #fff;
        font-size: 13px;
        text-align: center;
        border-color: #4338ca;
        position: sticky;
        left: 0;
        z-index: 32;
        min-width: 160px;
    }
    .table-pivot thead th.header-kondisi {
        text-transform: uppercase;
        letter-spacing: 0.3px;
        word-break: break-word;
        max-width: 90px;
        min-width: 70px;
        line-height: 1.3;
        padding: 0.6rem 0.4rem;
        vertical-align: middle;
    }
    .table-pivot thead th.header-total {
        background: #4338ca;
        color: #fff;
        font-size: 13px;
        border-color: #4338ca;
        min-width: 70px;
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

    /* Sticky petugas column */
    .table-pivot tbody td.col-petugas {
        position: sticky;
        left: 0;
        z-index: 10;
        background: #f8fafc;
        font-weight: 700;
        color: #1e293b;
        text-align: left;
        padding: 0.45rem 0.75rem;
        min-width: 160px;
        border-right: 2px solid #e5e7eb;
    }

    /* Row colors */
    .table-pivot tbody tr:nth-child(odd) td {
        background: #ffffff;
    }
    .table-pivot tbody tr:nth-child(odd) td.col-petugas {
        background: #f8fafc;
    }
    .table-pivot tbody tr:nth-child(even) td {
        background: #f8f9ff;
    }
    .table-pivot tbody tr:nth-child(even) td.col-petugas {
        background: #f8f9ff;
    }

    .table-pivot tbody tr:hover td {
        background: #f0f0ff !important;
        transition: background 0.15s ease;
    }
    .table-pivot tbody tr:hover td.col-petugas {
        background: #f0f0ff !important;
    }

    /* Cell values */
    .table-pivot tbody td.cell-zero {
        color: #e2e8f0;
    }
    .table-pivot tbody td.cell-high {
        font-weight: 700;
        font-size: 14px;
    }

    /* Total column in body */
    .table-pivot tbody td.col-total {
        background: #eef2ff !important;
        color: #4338ca;
        font-weight: 700;
        border-left: 2px solid #e5e7eb;
    }
    .table-pivot tbody tr:nth-child(odd) td.col-total {
        background: #eef2ff !important;
    }
    .table-pivot tbody tr:nth-child(even) td.col-total {
        background: #eef2ff !important;
    }
    .table-pivot tbody tr:hover td.col-total {
        background: #e0e7ff !important;
    }

    /* === TFOOT === */
    .table-pivot tfoot td {
        padding: 0.55rem 0.5rem;
        border: 1px solid #e5e7eb;
        font-weight: 700;
        font-size: 13px;
        text-align: center;
        background: #312e81;
        color: #fff;
    }
    .table-pivot tfoot td.foot-petugas {
        position: sticky;
        left: 0;
        z-index: 10;
        background: #312e81;
        min-width: 160px;
        text-align: left;
        padding-left: 0.75rem;
    }
    .table-pivot tfoot td.foot-total {
        background: #4338ca;
        color: #fff;
        border-color: #4338ca;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .summary-value-rekap { font-size: 1.3rem; }
        .summary-card-rekap { padding: 1rem; }
        .summary-icon-rekap { width: 40px; height: 40px; font-size: 1.1rem; }
        .filter-wrapper { flex-wrap: wrap; }
        .card-rekap .card-body { padding: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="row mb-3 align-items-center">
    <div class="col">
        <h3 class="mb-0" style="font-weight:700;color:#0f172a;">Rekap Kondisi</h3>
        <p class="text-muted mt-1 mb-0" style="font-size:0.85rem;">Ringkasan pencatatan meter per petugas berdasarkan kondisi</p>
    </div>
    <div class="col-auto">
        <form method="GET" action="{{ route('rekap.kondisi') }}" class="filter-wrapper">
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
    @php
        $baikColor = $kondisiColors['meter baik'] ?? '#16a34a';
    @endphp
    <div class="col-lg-3 col-md-6">
        <div class="summary-card-rekap" style="background:linear-gradient(135deg,#f0fdf4,#ffffff);">
            <div class="d-flex align-items-center gap-3">
                <div class="summary-icon-rekap" style="background:rgba(22,163,74,0.12);color:#16a34a;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="summary-value-rekap" style="color:#16a34a;">{{ number_format($totalMeterBaik, 0, ',', '.') }}</div>
                    <div class="summary-label-rekap">Total Meter Baik</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="summary-card-rekap" style="background:linear-gradient(135deg,#fef2f2,#ffffff);">
            <div class="d-flex align-items-center gap-3">
                <div class="summary-icon-rekap" style="background:rgba(220,38,38,0.12);color:#dc2626;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <div class="summary-value-rekap" style="color:#dc2626;">{{ number_format($totalBermasalah, 0, ',', '.') }}</div>
                    <div class="summary-label-rekap">Total Meter Bermasalah</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="summary-card-rekap" style="background:linear-gradient(135deg,#eff6ff,#ffffff);">
            <div class="d-flex align-items-center gap-3">
                <div class="summary-icon-rekap" style="background:rgba(37,99,235,0.12);color:#2563eb;">
                    <i class="fas fa-trophy"></i>
                </div>
                <div>
                    <div class="summary-value-rekap" style="color:#2563eb;">
                        <span class="sub-name">{{ $petugasBaikTerbanyak }}</span>
                        <span class="sub-count">{{ $maxBaik > 0 ? number_format($maxBaik, 0, ',', '.') . ' meter baik' : '' }}</span>
                    </div>
                    <div class="summary-label-rekap">Petugas Meter Baik Terbanyak</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="summary-card-rekap" style="background:linear-gradient(135deg,#eef2ff,#ffffff);">
            <div class="d-flex align-items-center gap-3">
                <div class="summary-icon-rekap" style="background:rgba(67,56,202,0.12);color:#4338ca;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <div class="summary-value-rekap" style="color:#4338ca;">{{ number_format($grandTotal, 0, ',', '.') }}</div>
                    <div class="summary-label-rekap">Total Kondisi Dicatat Bulan Ini</div>
                </div>
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
                        <a class="nav-link" href="{{ route('rekap.wilayah', ['bulan' => $bulan, 'tahun' => $tahun]) }}">
                            <i class="fas fa-map me-1"></i> Rekap Wilayah
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('rekap.kondisi', ['bulan' => $bulan, 'tahun' => $tahun]) }}">
                            <i class="fas fa-check-circle me-1"></i> Rekap Kondisi
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-semibold mb-0" style="font-size:0.9rem;color:#0f172a;">
                        <i class="fas fa-table me-2" style="color:#4338ca;"></i>
                        Data Kondisi {{ \Carbon\Carbon::create()->month((int)$bulan)->format('F') }} {{ $tahun }}
                    </h6>
                    <span class="text-muted" style="font-size:0.75rem;">
                        {{ $petugas->count() }} petugas &middot; {{ $kondisiList->count() }} kondisi
                    </span>
                </div>

                @if($petugas->count() > 0 && $kondisiList->count() > 0)
                <div class="table-pivot-wrapper">
                    <table class="table-pivot" id="rekapTable">
                        <thead>
                            <tr>
                                <th class="header-petugas">Nama Petugas</th>
                                @foreach($kondisiList as $k)
                                    @php
                                        $color = $kondisiColors[strtolower($k->kondisi)] ?? '#6366f1';
                                    @endphp
                                    <th class="header-kondisi" style="background:#4338ca;color:#fff;border-color:#4338ca;border-bottom:3px solid {{ $color }};">
                                        {{ $k->kondisi }}
                                    </th>
                                @endforeach
                                <th class="header-total">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($petugas as $p)
                                @php $totalP = $petugasTotals[$p->id] ?? 0; @endphp
                                <tr>
                                    <td class="col-petugas">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:28px;height:28px;border-radius:50%;background:#e0e0ff;color:#4338ca;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;flex-shrink:0;">
                                                {{ strtoupper(substr($p->nama, 0, 1)) }}
                                            </div>
                                            <span>{{ $p->nama }}</span>
                                        </div>
                                    </td>
                                    @foreach($kondisiList as $k)
                                        @php
                                            $val = $matrix[$p->id][$k->id] ?? 0;
                                        @endphp
                                        <td class="{{ $val === 0 ? 'cell-zero' : '' }} {{ $val > 100 ? 'cell-high' : '' }}">
                                            {{ $val ?: '0' }}
                                        </td>
                                    @endforeach
                                    <td class="col-total">{{ $totalP ?: 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="foot-petugas">Total Keseluruhan</td>
                                @foreach($kondisiList as $k)
                                    <td>{{ $kondisiTotals[$k->id] ?: 0 }}</td>
                                @endforeach
                                <td class="foot-total">{{ $grandTotal ?: 0 }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox text-muted" style="font-size:3rem;opacity:0.4;"></i>
                        <p class="text-muted mt-3">Belum ada data rekap untuk periode ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
