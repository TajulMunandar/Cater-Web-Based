@extends('dashboard.partials.main')

@section('title', 'Detail Pelanggan')

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Detail Pelanggan', 'subtitle' => 'Informasi lengkap data pelanggan', 'icon' => 'user'])
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
                <div class="col pt-3 pe-3">
                    <a href="{{ $back_url ?? route('pelanggan.index') }}" class="btn btn-secondary float-end"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
                </div>
            </div>

            <div class="card-body">
                <!-- Basic Customer Information -->
                <h4 class="card-title mb-4">Informasi Dasar Pelanggan</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama</label>
                            <input type="text" class="form-control" value="{{ $pelanggan->nama }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" readonly rows="3">{{ $pelanggan->alamat }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Sambu</label>
                            <input type="text" class="form-control" value="{{ $pelanggan->no_sambu }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Kontrol</label>
                            <input type="text" class="form-control" value="{{ $pelanggan->no_kontrol }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Telepon</label>
                            <input type="text" class="form-control" value="{{ $pelanggan->telepon ?? '-' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type</label>
                            <input type="text" class="form-control" value="{{ $pelanggan->type ?? '-' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Wilayah Domisili</label>
                            <input type="text" class="form-control" value="{{ $pelanggan->wilayah?->wilayah ?? '-' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Golongan</label>
                            <input type="text" class="form-control" value="{{ $pelanggan->golongan?->nama ?? '-' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <input type="text" class="form-control" value="{{ ucfirst($pelanggan->status) }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Koordinat</label>
                            <input type="text" class="form-control" value="{{ $pelanggan->lat && $pelanggan->long ? $pelanggan->lat . ', ' . $pelanggan->long : '-' }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Operational Details -->
                @if($pelanggan->PelangganDetail && $pelanggan->PelangganDetail->count() > 0)
                    <hr class="my-4">
                    <h4 class="card-title mb-4">Detail Operasional</h4>

                    @foreach($pelanggan->PelangganDetail as $detail)
                        <div class="card border mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Data Operasional - {{ $detail->waktu_catat_meter?->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') ?? 'N/A' }} WIB</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Petugas</label>
                                            <input type="text" class="form-control" value="{{ $detail->Petugas?->nama ?? '-' }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Rute</label>
                                            <input type="text" class="form-control" value="{{ $detail->rute }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Kondisi Meter</label>
                                            <input type="text" class="form-control" value="{{ $detail->Kondisi?->kondisi ?? '-' }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Waktu Catat Meter</label>
                                            <input type="text" class="form-control" value="{{ $detail->waktu_catat_meter?->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') ?? '-' }}" readonly>
                                            <small class="text-muted">WIB</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Stand Terakhir</label>
                                            <input type="text" class="form-control" value="{{ $detail->stand_terakhir }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Keterangan</label>
                                            <input type="text" class="form-control" value="{{ $detail->ket ?? '-' }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Wilayah Operasional</label>
                                            <input type="text" class="form-control" value="{{ $detail->Wilayah?->wilayah ?? '-' }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Urutan</label>
                                            <input type="text" class="form-control" value="{{ $detail->urutan }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <hr class="my-4">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Belum ada data operasional untuk pelanggan ini.
                    </div>
                @endif

                <!-- Photos -->
                @if($pelanggan->FotoPelanggan && $pelanggan->FotoPelanggan->count() > 0)
                    <hr class="my-4">
                    <h4 class="card-title mb-4">Foto Pelanggan</h4>
                    <div class="row">
                        @foreach($pelanggan->FotoPelanggan as $foto)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($foto->foto) }}" class="card-img-top" alt="Foto Pelanggan" style="height: 200px; object-fit: cover;" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22200%22><rect fill=%22%23f8f9fa%22 width=%22300%22 height=%22200%22/><text fill=%22%236c757d%22 font-size=%2216%22 x=%2250%%22 y=%2250%%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22>Gambar Tidak Tersedia</text></svg>'; this.onerror=null;">
                                    <div class="card-body p-2">
                                        <small class="text-muted">{{ $foto->created_at?->format('d/m/Y H:i') ?? '-' }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
