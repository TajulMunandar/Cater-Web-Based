@extends('dashboard.partials.main')

@section('title', 'Catat Meter')

@php
$pelanggans = App\Models\Pelanggan::all();
$bulanList = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
];
$tahunList = range(date('Y'), date('Y') - 5);
@endphp

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Catat Meter', 'subtitle' => 'Pencatatan meter pelanggan', 'icon' => 'clipboard-text'])

<div class="row">
    <div class="col">
        @if (session()->has('success'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
</div>

<div class="row mt-2">
    <div class="col">
        <div class="card" style="border:none;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.06);overflow:hidden;">
            <!-- Tab Navigation -->
            <div style="padding:16px 20px 0;background:#fff;border-bottom:1px solid #e2e8f0;">
                <ul class="nav nav-pills" style="gap:6px;">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('cater.index') }}" style="background:#3b82f6;color:#fff;border-radius:8px;padding:8px 20px;font-weight:700;font-size:13px;">Catat Meter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cater.tidak-terdaftar') }}" style="color:#64748b;border-radius:8px;padding:8px 20px;font-size:13px;">Catat Meter Tidak Terdaftar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cater.urutan') }}" style="color:#64748b;border-radius:8px;padding:8px 20px;font-size:13px;">Urutan Catat Meter</a>
                    </li>
                </ul>
            </div>

            <!-- Filter Bar -->
            <div style="padding:16px 20px;background:#fff;">
                <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
                    <div style="flex:1;min-width:140px;">
                        <label style="display:block;font-size:12px;color:#64748b;margin-bottom:4px;font-weight:500;">Petugas</label>
                        <select id="filter_petugas" style="width:100%;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;color:#1e293b;background:#fff;outline:none;">
                            <option value="">-- SEMUA PETUGAS --</option>
                            @foreach($petugasList as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex:1;min-width:140px;">
                        <label style="display:block;font-size:12px;color:#64748b;margin-bottom:4px;font-weight:500;">Wilayah</label>
                        <select id="filter_wilayah" style="width:100%;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;color:#1e293b;background:#fff;outline:none;">
                            <option value="">-- SEMUA WILAYAH --</option>
                            @foreach($wilayahList as $w)
                                <option value="{{ $w->id }}">{{ $w->wilayah }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex:1;min-width:140px;">
                        <label style="display:block;font-size:12px;color:#64748b;margin-bottom:4px;font-weight:500;">Kondisi Meter</label>
                        <select id="filter_kondisi" style="width:100%;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;color:#1e293b;background:#fff;outline:none;">
                            <option value="">-- SEMUA KONDISI --</option>
                            @foreach($kondisiList as $k)
                                <option value="{{ $k->id }}">{{ $k->kondisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex:1;min-width:120px;">
                        <label style="display:block;font-size:12px;color:#64748b;margin-bottom:4px;font-weight:500;">Bulan</label>
                        <select id="filter_bulan" style="width:100%;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;color:#1e293b;background:#fff;outline:none;">
                            <option value="">-- SEMUA BULAN --</option>
                            @foreach($bulanList as $key => $bln)
                                <option value="{{ $key }}">{{ $bln }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex:1;min-width:100px;">
                        <label style="display:block;font-size:12px;color:#64748b;margin-bottom:4px;font-weight:500;">Tahun</label>
                        <select id="filter_tahun" style="width:100%;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;color:#1e293b;background:#fff;outline:none;">
                            <option value="">-- SEMUA TAHUN --</option>
                            @foreach($tahunList as $thn)
                                <option value="{{ $thn }}">{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex;gap:6px;align-items:flex-end;padding-bottom:1px;">
                        <button id="filter_apply" style="background:#3b82f6;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-search" style="font-size:12px;"></i> Filter
                        </button>
                        <button id="filter_reset" style="background:#f1f5f9;color:#64748b;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-undo" style="font-size:12px;"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Toolbar & Table -->
            <div style="padding:0 20px 20px;background:#fff;">
                <!-- Toolbar -->
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px;padding-top:8px;">
                    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                        <div style="display:flex;align-items:center;gap:6px;">
                            <span style="font-size:13px;color:#64748b;">Tampilkan</span>
                            <select id="pageLengthSelect" style="border:1px solid #e2e8f0;border-radius:8px;padding:6px 8px;font-size:13px;color:#1e293b;outline:none;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span style="font-size:13px;color:#64748b;">data per halaman</span>
                        </div>
                        <button id="downloadExcel" style="background:#10b981;color:#fff;border:none;border-radius:8px;padding:6px 14px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="position:relative;">
                            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:13px;"></i>
                            <input type="text" id="tableSearch" placeholder="Cari..." style="border:1px solid #e2e8f0;border-radius:8px;padding:7px 12px 7px 34px;font-size:13px;color:#1e293b;outline:none;width:200px;">
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal" style="background:#3b82f6;border:none;border-radius:8px;padding:7px 16px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div style="overflow-x:auto;">
                    <table class="table" id="myTable" style="width:100%;">
                        <thead>
                            <tr>
                                <th style="font-weight:700;font-size:13px;text-align:center;">No ⇅</th>
                                <th style="font-weight:700;font-size:13px;text-align:center;">Photo ⇅</th>
                                <th style="font-weight:700;font-size:13px;text-align:center;">Stand Meter ⇅</th>
                                <th style="font-weight:700;font-size:13px;text-align:center;">Waktu ⇅</th>
                                <th style="font-weight:700;font-size:13px;text-align:center;">Nama ⇅</th>
                                <th style="font-weight:700;font-size:13px;text-align:center;">No Kontrol ⇅</th>
                                <th style="font-weight:700;font-size:13px;text-align:center;">No Sambung ⇅</th>
                                <th style="font-weight:700;font-size:13px;text-align:center;">Wilayah ⇅</th>
                                <th style="font-weight:700;font-size:13px;text-align:center;">Kondisi Meter ⇅</th>
                                <th style="font-weight:700;font-size:13px;text-align:center;">Petugas ⇅</th>
                                <th style="font-weight:700;font-size:13px;text-align:center;">Proses ⇅</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;">
            <form id="createForm" enctype="multipart/form-data">
                <div style="background:#3b82f6;padding:16px 24px;display:flex;justify-content:space-between;align-items:center;">
                    <h5 style="color:#fff;font-weight:700;font-size:16px;margin:0;display:flex;align-items:center;gap:8px;">
                        <i class="fas fa-plus-circle"></i> Tambah Catat Meter
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter:brightness(10);"></button>
                </div>
                @csrf
                <div style="padding:20px 24px;">
                    <div class="mb-3">
                        <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Pelanggan</label>
                        <select class="form-select" id="id_pelanggan" name="id_pelanggan" required style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                            <option value="">Pilih Pelanggan</option>
                            @foreach($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Petugas</label>
                        <select class="form-select" id="id_petugas" name="id_petugas" required style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                            <option value="">Pilih Petugas</option>
                            @foreach($petugasList as $petugas)
                                <option value="{{ $petugas->id }}">{{ $petugas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Kondisi</label>
                        <select class="form-select" id="id_kondisi" name="id_kondisi" required style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                            <option value="">Pilih Kondisi</option>
                            @foreach($kondisiList as $kondisi)
                                <option value="{{ $kondisi->id }}">{{ $kondisi->kondisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Waktu</label>
                            <input type="datetime-local" class="form-control" id="waktu" name="waktu" required style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Stand</label>
                            <input type="number" class="form-control" id="stand" name="stand" required style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">GPS</label>
                        <input type="text" class="form-control" id="gps" name="gps" style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Status</label>
                        <select class="form-select" id="status" name="status" required style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Foto Meter</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewFoto(event)" style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                        <img id="fotoPreview" src="#" alt="Preview" style="max-height:160px;display:none;width:100%;object-fit:cover;border-radius:8px;margin-top:8px;">
                    </div>
                </div>
                <div style="padding:16px 24px;border-top:1px solid #e2e8f0;display:flex;justify-content:flex-end;gap:8px;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px;padding:8px 20px;font-size:13px;font-weight:600;">Batal</button>
                    <button type="submit" style="background:#3b82f6;color:#fff;border:none;border-radius:8px;padding:8px 20px;font-size:13px;font-weight:600;cursor:pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;">
            <form id="editForm" enctype="multipart/form-data">
                <div style="background:#3b82f6;padding:16px 24px;display:flex;justify-content:space-between;align-items:center;">
                    <h5 style="color:#fff;font-weight:700;font-size:16px;margin:0;display:flex;align-items:center;gap:8px;">
                        <i class="fas fa-edit"></i> Edit Catat Meter
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_id" name="id">
                <div style="padding:20px 24px;">
                    <div class="mb-3">
                        <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Pelanggan</label>
                        <select class="form-select" id="edit_id_pelanggan" name="id_pelanggan" required style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                            <option value="">Pilih Pelanggan</option>
                            @foreach($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Petugas</label>
                        <select class="form-select" id="edit_id_petugas" name="id_petugas" required style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                            <option value="">Pilih Petugas</option>
                            @foreach($petugasList as $petugas)
                                <option value="{{ $petugas->id }}">{{ $petugas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Kondisi</label>
                        <select class="form-select" id="edit_id_kondisi" name="id_kondisi" required style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                            <option value="">Pilih Kondisi</option>
                            @foreach($kondisiList as $kondisi)
                                <option value="{{ $kondisi->id }}">{{ $kondisi->kondisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Waktu</label>
                            <input type="datetime-local" class="form-control" id="edit_waktu" name="waktu" required style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Stand</label>
                            <input type="number" class="form-control" id="edit_stand" name="stand" required style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">GPS</label>
                        <input type="text" class="form-control" id="edit_gps" name="gps" style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Status</label>
                        <select class="form-select" id="edit_status" name="status" required style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;">Foto Meter Saat Ini</label>
                        <div id="edit_foto_existing" class="mb-2" style="display:none;">
                            <img id="edit_foto_preview" src="#" alt="Foto" style="max-height:140px;width:100%;object-fit:cover;border-radius:8px;">
                        </div>
                        <label class="form-label" style="font-size:11px;color:#94a3b8;font-weight:400;">Ganti Foto</label>
                        <input type="file" class="form-control" id="edit_foto" name="foto" accept="image/*" onchange="previewEditFoto(event)" style="border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
                    </div>
                </div>
                <div style="padding:16px 24px;border-top:1px solid #e2e8f0;display:flex;justify-content:flex-end;gap:8px;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px;padding:8px 20px;font-size:13px;font-weight:600;">Batal</button>
                    <button type="submit" style="background:#3b82f6;color:#fff;border:none;border-radius:8px;padding:8px 20px;font-size:13px;font-weight:600;cursor:pointer;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;">
            <div style="background:#ef4444;padding:16px 24px;display:flex;justify-content:space-between;align-items:center;">
                <h5 style="color:#fff;font-weight:700;font-size:16px;margin:0;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-trash"></i> Hapus Catat Meter
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div style="padding:32px 24px;text-align:center;">
                <i class="fas fa-exclamation-triangle text-warning" style="font-size:48px;"></i>
                <p style="margin-top:16px;margin-bottom:4px;font-weight:600;font-size:15px;color:#1e293b;">Apakah Anda yakin ingin menghapus data ini?</p>
                <p style="color:#64748b;font-size:13px;margin:0;">Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div style="padding:16px 24px;border-top:1px solid #e2e8f0;display:flex;justify-content:center;gap:8px;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px;padding:8px 20px;font-size:13px;font-weight:600;">Batal</button>
                <button type="button" id="confirmDelete" style="background:#ef4444;color:#fff;border:none;border-radius:8px;padding:8px 20px;font-size:13px;font-weight:600;cursor:pointer;">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:700px;" role="document">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;">
            <div style="background:#3b82f6;padding:16px 24px;display:flex;justify-content:space-between;align-items:center;">
                <h5 style="color:#fff;font-weight:700;font-size:16px;margin:0;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-file-alt"></i> Detail Catat Meter
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div style="padding:24px;">
                <!-- Photos Row -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:6px;display:block;">Photo Rumah</label>
                        <div id="detail_foto_rumah_container" style="width:100%;height:180px;border-radius:12px;overflow:hidden;background:#f1f5f9;display:flex;align-items:center;justify-content:center;cursor:pointer;" onclick="openDetailRumahZoom()">
                            <img id="detail_foto_rumah" src="" alt="Foto Rumah" style="width:100%;height:100%;object-fit:cover;display:none;">
                            <div id="detail_foto_rumah_placeholder" style="display:flex;flex-direction:column;align-items:center;gap:8px;color:#94a3b8;">
                                <i class="fas fa-image" style="font-size:36px;"></i>
                                <span style="font-size:13px;">Tidak ada foto</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:6px;display:block;">Photo Meter</label>
                        <div id="detail_foto_meter_container" style="width:100%;height:180px;border-radius:12px;overflow:hidden;background:#f1f5f9;display:flex;align-items:center;justify-content:center;cursor:pointer;" onclick="openPhotoModalFromDetail()">
                            <img id="detail_foto_meter" src="" alt="Foto Meter" style="width:100%;height:100%;object-fit:cover;display:none;">
                            <div id="detail_foto_meter_placeholder" style="display:flex;flex-direction:column;align-items:center;gap:8px;color:#94a3b8;">
                                <i class="fas fa-image" style="font-size:36px;"></i>
                                <span style="font-size:13px;">Tidak ada foto</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Detail Info Grid -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;display:block;">Nama</label>
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:14px;color:#1e293b;" id="detail_nama">-</div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;display:block;">Wilayah</label>
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:14px;color:#1e293b;" id="detail_wilayah">-</div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;display:block;">No Telepon</label>
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:14px;color:#1e293b;" id="detail_telepon">-</div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;display:block;">Kondisi Meter</label>
                        <div id="detail_kondisi" style="padding:6px 0;">-</div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;display:block;">Alamat</label>
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:14px;color:#1e293b;" id="detail_alamat">-</div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;display:block;">Stand Meter</label>
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:14px;color:#1e293b;" id="detail_stand">-</div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;display:block;">No Sambung</label>
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:14px;color:#1e293b;" id="detail_no_sambu">-</div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;display:block;">Waktu Catat</label>
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:14px;color:#1e293b;" id="detail_waktu">-</div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;display:block;">No Kontrol</label>
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:14px;color:#1e293b;" id="detail_no_kontrol">-</div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:4px;display:block;">Petugas</label>
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:14px;color:#1e293b;" id="detail_petugas">-</div>
                    </div>
                </div>
            </div>
            <div style="padding:16px 24px;border-top:1px solid #e2e8f0;display:flex;justify-content:flex-end;gap:8px;">
                <button type="button" onclick="printDetail()" style="background:#ef4444;color:#fff;border:none;border-radius:8px;padding:10px 20px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-print"></i> Print
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="background:#10b981;color:#fff;border:none;border-radius:8px;padding:10px 20px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;" role="document">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;background:#1e293b;">
            <div style="background:#1e293b;padding:16px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,0.1);">
                <h5 style="color:#fff;font-weight:700;font-size:15px;margin:0;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-camera"></i> Photo Catat Meter
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Toolbar -->
            <div style="padding:10px 20px;display:flex;gap:8px;flex-wrap:wrap;border-bottom:1px solid rgba(255,255,255,0.08);">
                <button onclick="rotatePhoto(90)" style="background:#ef4444;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-rotate-right"></i> Putar Kanan
                </button>
                <button onclick="rotatePhoto(-90)" style="background:#f59e0b;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-rotate-left"></i> Putar Kiri
                </button>
                <button onclick="zoomPhoto(0.25)" style="background:#3b82f6;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-search-plus"></i> Perbesar (+)
                </button>
                <button onclick="zoomPhoto(-0.25)" style="background:#f97316;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-search-minus"></i> Perkecil (-)
                </button>
            </div>
            <!-- Photo Area -->
            <div id="photoViewer" style="background:#000;height:400px;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;">
                <img id="photoViewImage" src="" alt="Photo" style="max-width:100%;max-height:100%;object-fit:contain;transition:transform 0.3s ease;cursor:grab;">
            </div>
            <!-- Footer -->
            <div style="padding:12px 20px;background:#1e293b;border-top:1px solid rgba(255,255,255,0.08);display:flex;justify-content:space-between;align-items:center;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="color:#94a3b8;font-size:13px;">Stand Meter</span>
                    <input type="number" id="photoStandInput" style="width:80px;border:1px solid #334155;border-radius:8px;padding:6px 10px;font-size:13px;color:#fff;background:#0f172a;outline:none;">
                    <button onclick="updateStandFromPhoto()" style="background:#3b82f6;color:#fff;border:none;border-radius:8px;padding:6px 14px;font-size:13px;font-weight:600;cursor:pointer;">Update</button>
                </div>
                <button type="button" class="btn" data-bs-dismiss="modal" style="background:#10b981;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:6px;cursor:pointer;">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden detail data -->
<div id="detailDataStore" style="display:none;"></div>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}">
<style>
body { background:#f1f5f9; }

/* Filter focus */
#filter_petugas:focus, #filter_wilayah:focus, #filter_kondisi:focus,
#filter_bulan:focus, #filter_tahun:focus, #filter_search:focus,
#tableSearch:focus, #pageLengthSelect:focus {
    border-color:#3b82f6 !important;
    box-shadow:0 0 0 3px rgba(59,130,246,0.1) !important;
}

/* Tabs hover */
.nav-pills .nav-link:not(.active):hover {
    background:#f1f5f9 !important;
}

/* Table styles */
th { background:#1e3a8a !important; color:#fff !important; font-weight:700 !important; font-size:13px !important; padding:12px 16px !important; text-align:center !important; border:none !important; }
#myTable tbody td { padding:12px 16px !important; font-size:13px !important; color:#334155 !important; border-bottom:1px solid #f1f5f9 !important; vertical-align:middle !important; }
#myTable tbody tr:nth-child(odd) td { background:#ffffff; }
#myTable tbody tr:nth-child(even) td { background:#f8faff; }
#myTable tbody tr:hover td { background:#eff6ff !important; cursor:pointer; transition:background 0.15s ease; }

/* Action buttons */
.btn-action { display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border:none;border-radius:6px;cursor:pointer;font-size:13px;transition:opacity 0.15s; }
.btn-action:hover { opacity:0.85; }
.btn-edit { background:#f59e0b;color:#fff; }
.btn-delete { background:#ef4444;color:#fff;margin-left:4px; }

/* Photo thumb hover */
.photo-thumb { transition:opacity 0.15s, box-shadow 0.15s; border-radius:8px;display:inline-block; }
.photo-thumb:hover { opacity:0.85; box-shadow:0 0 0 2px #3b82f6; }
.photo-thumb.no-photo:hover { opacity:0.7; }

/* Override DataTables search (we use custom) */
.dataTables_filter { display:none !important; }
.dataTables_length { display:none !important; }

/* Modal styles */
.modal-content { border-radius:16px;overflow:hidden;border:none; }
.modal-header { border-bottom:none;padding:1.25rem 1.5rem; }
.modal-body { padding:1.5rem !important; }
.modal-footer { border-top:none;padding:0 1.5rem 1.5rem; }

/* Photo viewer drag */
#photoViewImage { user-select:none; -webkit-user-drag:none; }

/* Responsive */
@media (max-width:768px) {
    #myTable { font-size:12px !important; }
    #myTable thead th, #myTable tbody td { padding:8px 10px !important; }
}
</style>
@endpush

@push('script')
<script src="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script>
var photoState = { rotation: 0, scale: 1, currentUrl: '', currentId: null };
var isDragging = false, dragStartX, dragStartY, dragOffsetX = 0, dragOffsetY = 0;

$(document).ready(function() {
    var table = $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("cater.data") }}',
            data: function(d) {
                d.id_petugas = $('#filter_petugas').val();
                d.id_wilayah = $('#filter_wilayah').val();
                d.id_kondisi = $('#filter_kondisi').val();
                d.bulan = $('#filter_bulan').val();
                d.tahun = $('#filter_tahun').val();
                d.search = $('#filter_search').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'foto', orderable: false, searchable: false },
            { data: 'stand_meter', name: 'stand' },
            { data: 'waktu', name: 'waktu' },
            { data: 'nama', name: 'nama' },
            { data: 'no_kontrol', name: 'no_kontrol' },
            { data: 'no_sambung', name: 'no_sambung' },
            { data: 'wilayah', name: 'wilayah' },
            { data: 'kondisi_badge', name: 'kondisi_badge', orderable: false, searchable: false },
            { data: 'petugas', name: 'petugas' },
            { data: 'action', orderable: false, searchable: false }
        ],
        language: {
            search: "",
            searchPlaceholder: "Cari...",
            decimal: ",",
            thousands: ".",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            paginate: { previous: "Previous", next: "Next" }
        },
        scrollX: true,
        pageLength: 10,
        lengthChange: false,
        order: [[3, 'desc']],
        createdRow: function(row, data, index) {
            $(row).data('id', data.id);
        }
    }).on('draw', function() {
        $('.dataTables_filter input[type="search"]').css({ marginBottom: '10px' });
    });

    // Custom page length
    $('#pageLengthSelect').on('change', function() {
        table.page.len(parseInt($(this).val())).draw();
    });

    // Custom search
    $('#tableSearch').on('keyup', function() {
        table.search($(this).val()).draw();
    });

    // Filter apply
    $('#filter_apply').on('click', function() {
        table.ajax.reload();
    });

    // Filter reset
    $('#filter_reset').on('click', function() {
        $('#filter_petugas, #filter_wilayah, #filter_kondisi, #filter_bulan, #filter_tahun').val('');
        $('#filter_search').val('');
        table.ajax.reload();
    });

    // Enter key on filter search
    $('#filter_search').on('keypress', function(e) {
        if (e.which === 13) { $('#filter_apply').click(); }
    });

    // Row click for detail
    $('#myTable tbody').on('click', 'tr', function(e) {
        if ($(e.target).closest('.btn-action, .photo-thumb, .photo-thumb *').length) return;
        var id = $(this).data('id');
        if (id) {
            $.get('/cater/' + id, function(data) {
                showDetail(data);
            });
        }
    });

    // Create form
    $('#createForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '/cater',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                bootstrap.Modal.getInstance(document.getElementById('createModal')).hide();
                $('#createForm')[0].reset();
                $('#fotoPreview').hide();
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.success, timer: 1500, showConfirmButton: false });
            },
            error: function(xhr) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON ? Object.values(xhr.responseJSON.errors).flat().join(', ') : 'Terjadi kesalahan' });
            }
        });
    });

    // Edit
    $(document).on('click', '.btn-edit', function(e) {
        e.stopPropagation();
        var id = $(this).data('id');
        $('#editForm')[0].reset();
        $('#edit_id').val(id);
        $.get('/cater/' + id, function(data) {
            $('#edit_id_pelanggan').val(data.id_pelanggan);
            $('#edit_id_petugas').val(data.id_petugas);
            $('#edit_id_kondisi').val(data.id_kondisi);
            $('#edit_waktu').val(data.waktu ? new Date(data.waktu).toISOString().slice(0, 16) : '');
            $('#edit_stand').val(data.stand);
            $('#edit_gps').val(data.gps);
            $('#edit_status').val(data.status);
            if (data.foto_url) {
                $('#edit_foto_preview').attr('src', data.foto_url);
                $('#edit_foto_existing').show();
            } else {
                $('#edit_foto_existing').hide();
            }
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    });

    $('#editForm').submit(function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        var formData = new FormData(this);
        formData.append('_method', 'PUT');
        $.ajax({
            url: '/cater/' + id,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.success, timer: 1500, showConfirmButton: false });
            },
            error: function(xhr) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON ? Object.values(xhr.responseJSON.errors).flat().join(', ') : 'Terjadi kesalahan' });
            }
        });
    });

    // Delete
    $(document).on('click', '.btn-delete', function(e) {
        e.stopPropagation();
        var id = $(this).data('id');
        $('#confirmDelete').data('id', id);
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });

    $('#confirmDelete').click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: '/cater/' + id,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.success, timer: 1500, showConfirmButton: false });
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat menghapus data' });
            }
        });
    });

    // Download Excel
    $('#downloadExcel').on('click', function() {
        var params = $.param({
            id_petugas: $('#filter_petugas').val(),
            id_wilayah: $('#filter_wilayah').val(),
            id_kondisi: $('#filter_kondisi').val(),
            bulan: $('#filter_bulan').val(),
            tahun: $('#filter_tahun').val(),
            search: $('#filter_search').val()
        });
        window.location.href = '/cater/excel?' + params;
    });

    // Photo viewer drag
    var img = document.getElementById('photoViewImage');
    img.addEventListener('mousedown', startDrag);
    document.addEventListener('mousemove', doDrag);
    document.addEventListener('mouseup', stopDrag);
    img.addEventListener('touchstart', startDragTouch, { passive: false });
    document.addEventListener('touchmove', doDragTouch, { passive: false });
    document.addEventListener('touchend', stopDrag);

    // Mouse wheel zoom
    img.addEventListener('wheel', function(e) {
        e.preventDefault();
        var delta = e.deltaY > 0 ? -0.1 : 0.1;
        zoomPhoto(delta);
    });
});

function openPhotoModal(url, id, stand) {
    photoState.currentUrl = url;
    photoState.currentId = id || null;
    photoState.rotation = 0;
    photoState.scale = 1;
    dragOffsetX = 0; dragOffsetY = 0;
    $('#photoViewImage').attr('src', url).css({ transform: 'rotate(0deg) scale(1)', left: '0px', top: '0px' });
    $('#photoStandInput').val(stand || '');
    $('#photoModal').modal('show');
}

function openPhotoModalFromDetail() {
    var url = $('#detail_foto_meter').attr('src');
    if (url && url !== '') {
        openPhotoModal(url);
    }
}

function openDetailRumahZoom() {
    var url = $('#detail_foto_rumah').attr('src');
    if (url && url !== '') {
        openPhotoModal(url);
    }
}

function rotatePhoto(deg) {
    photoState.rotation = (photoState.rotation + deg) % 360;
    updatePhotoTransform();
}

function zoomPhoto(delta) {
    photoState.scale = Math.max(0.5, Math.min(3, photoState.scale + delta));
    updatePhotoTransform();
}

function updatePhotoTransform() {
    var img = $('#photoViewImage');
    var t = 'rotate(' + photoState.rotation + 'deg) scale(' + photoState.scale + ')';
    var translate = 'translate(' + dragOffsetX + 'px, ' + dragOffsetY + 'px)';
    img.css({ transform: translate + ' ' + t });
}

function startDrag(e) { isDragging = true; dragStartX = e.clientX - dragOffsetX; dragStartY = e.clientY - dragOffsetY; $('#photoViewImage').css('cursor', 'grabbing'); }
function doDrag(e) { if (!isDragging) return; dragOffsetX = e.clientX - dragStartX; dragOffsetY = e.clientY - dragStartY; updatePhotoTransform(); }
function stopDrag() { isDragging = false; $('#photoViewImage').css('cursor', 'grab'); }
function startDragTouch(e) { var t = e.touches[0]; isDragging = true; dragStartX = t.clientX - dragOffsetX; dragStartY = t.clientY - dragOffsetY; }
function doDragTouch(e) { if (!isDragging) return; e.preventDefault(); var t = e.touches[0]; dragOffsetX = t.clientX - dragStartX; dragOffsetY = t.clientY - dragStartY; updatePhotoTransform(); }

// Pinch zoom
var lastDist = 0;
document.addEventListener('touchstart', function(e) {
    if (e.touches.length === 2) { lastDist = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY); }
});
document.addEventListener('touchmove', function(e) {
    if (e.touches.length === 2) {
        e.preventDefault();
        var dist = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
        var delta = (dist - lastDist) * 0.005;
        zoomPhoto(delta);
        lastDist = dist;
    }
});

function updateStandFromPhoto() {
    var val = $('#photoStandInput').val();
    if (photoState.currentId && val !== '') {
        $.ajax({
            url: '/cater/' + photoState.currentId + '/update-stand',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', stand: val },
            success: function() {
                Swal.fire({ icon: 'success', title: 'Stand meter berhasil diperbarui', timer: 1500, showConfirmButton: false });
                $('#myTable').DataTable().ajax.reload(null, false);
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memperbarui stand meter' });
            }
        });
    }
}

function showDetail(d) {
    var waktu = d.waktu ? new Date(d.waktu).toLocaleString('id-ID', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' }) : '-';

    $('#detail_nama').text(d.nama || '-');
    $('#detail_wilayah').text(d.wilayah || '-');
    $('#detail_telepon').text(d.telepon || '-');
    $('#detail_alamat').text(d.alamat || '-');
    $('#detail_stand').text(d.stand || '-');
    $('#detail_no_sambu').text(d.no_sambu || '-');
    $('#detail_waktu').text(waktu);
    $('#detail_no_kontrol').text(d.no_kontrol || '-');
    $('#detail_petugas').text(d.petugas_nama || '-');

    // Kondisi badge
    var kn = d.kondisi_nama || '';
    var map = {
        'METER BAIK': { bg: '#dcfce7', text: '#15803d' },
        'METER BURAM': { bg: '#f1f5f9', text: '#475569' },
        'METER HILANG': { bg: '#fee2e2', text: '#dc2626' },
        'METER BARU': { bg: '#dbeafe', text: '#1d4ed8' }
    };
    var c = map[kn.toUpperCase()] || { bg: '#f1f5f9', text: '#475569' };
    $('#detail_kondisi').html('<span style="background:' + c.bg + ';color:' + c.text + ';border-radius:20px;padding:4px 10px;font-size:11px;font-weight:700;display:inline-block;">' + kn + '</span>');

    // Photos
    if (d.foto_url) {
        $('#detail_foto_meter').attr('src', d.foto_url).show();
        $('#detail_foto_meter_placeholder').hide();
    } else {
        $('#detail_foto_meter').hide();
        $('#detail_foto_meter_placeholder').show();
    }
    if (d.foto_rumah_url) {
        $('#detail_foto_rumah').attr('src', d.foto_rumah_url).show();
        $('#detail_foto_rumah_placeholder').hide();
    } else {
        $('#detail_foto_rumah').hide();
        $('#detail_foto_rumah_placeholder').show();
    }

    photoState.currentId = d.id;
    $('#photoStandInput').val(d.stand || '');
    new bootstrap.Modal(document.getElementById('detailModal')).show();
}

function printDetail() {
    var html = '<html><head><title>Detail Catat Meter</title>';
    html += '<style>body{font-family:sans-serif;padding:20px;}table{width:100%;border-collapse:collapse;}td{padding:8px 12px;border:1px solid #ddd;font-size:13px;}td.label{font-weight:600;background:#f8fafc;width:40%;}</style></head><body>';
    html += '<h2 style="color:#1e3a8a;">Detail Catat Meter</h2>';
    html += '<table>';
    var fields = [
        ['Nama', $('#detail_nama').text()],
        ['Wilayah', $('#detail_wilayah').text()],
        ['No Telepon', $('#detail_telepon').text()],
        ['Alamat', $('#detail_alamat').text()],
        ['Stand Meter', $('#detail_stand').text()],
        ['No Sambung', $('#detail_no_sambu').text()],
        ['Waktu Catat', $('#detail_waktu').text()],
        ['No Kontrol', $('#detail_no_kontrol').text()],
        ['Petugas', $('#detail_petugas').text()]
    ];
    fields.forEach(function(f) {
        html += '<tr><td class="label">' + f[0] + '</td><td>' + f[1] + '</td></tr>';
    });
    html += '</table></body></html>';
    var w = window.open('', '_blank');
    w.document.write(html);
    w.document.close();
    w.print();
}

function previewFoto(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('fotoPreview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

function previewEditFoto(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('edit_foto_preview');
        output.src = reader.result;
        document.getElementById('edit_foto_existing').style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endpush
