<div class="rute-child-content">
    <div class="rute-child-header">
        <span class="rute-child-title">
            <i class="fas fa-route me-1"></i> Rute untuk: <strong>{{ $wilayah->wilayah }}</strong>
        </span>
        <button class="btn btn-sm btn-primary" onclick="openAddRute({{ $wilayah->id }})">
            <i class="fas fa-plus me-1"></i> Tambah Rute
        </button>
    </div>

    @if ($rutes->isEmpty())
        <div class="rute-empty-state">
            <div class="empty-icon"><i class="fas fa-route"></i></div>
            <p class="empty-text">Belum ada rute untuk wilayah ini.</p>
            <p class="empty-hint">Klik "Tambah Rute" untuk membuat rute pertama.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table rute-child-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Rute</th>
                        <th>Kode</th>
                        <th>Keterangan</th>
                        <th style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rutes as $index => $rute)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $rute->rute }}</td>
                            <td>{{ $rute->kode ?? '-' }}</td>
                            <td>{{ $rute->ket ?? '-' }}</td>
                            <td>
                                <button class="btn-action-rute btn-edit-rute" data-id="{{ $rute->id }}" data-rute="{{ $rute->rute }}" data-kode="{{ $rute->kode ?? '' }}" data-ket="{{ $rute->ket ?? '' }}" title="Edit Rute"><i class="fas fa-pen-to-square"></i></button>
                                <button class="btn-action-rute btn-delete-rute" data-id="{{ $rute->id }}" data-rute="{{ $rute->rute }}" title="Hapus Rute"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
