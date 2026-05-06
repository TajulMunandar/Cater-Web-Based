@extends('dashboard.partials.main')

@section('content')
    <div class="row mt-3">
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

            @if (session()->has('delete'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('delete') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('update'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('update') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-2">

        <div class="card">
            <div class="row">
                <div class="col">
                    <ul class="nav nav-pills user-profile-tab">
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/wilayah"><i class="fas fa-map-location-dot me-2"></i>Wilayah</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/kondisi"><i class="fas fa-gauge me-2"></i>Kondisi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/petugas"><i class="fas fa-user-tie me-2"></i>Petugas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                href="/settings/golongan"><i class="fas fa-tags me-2"></i>Golongan</a>
                        </li>
                    </ul>
                </div>
                <div class="col pt-3 pe-3">
                    <button class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#createKondisiMeterModal"><i class="fas fa-plus me-2"></i> Tambah</button>
                </div>
            </div>

            <div class="card-body ">

                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kondisi Meter</th>
                            <th>Kode</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kondisis as $kondisi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $kondisi->kondisi }}</td>
                                <td>{{ $kondisi->kode }}</td>
                                <td>
                                    @if ($kondisi->keterangan == null)
                                        -
                                    @else
                                        {{ $kondisi->keterangan }}
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $loop->iteration }}"><i
                                            class="fas fa-pen-to-square"></i></button>
                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $loop->iteration }}"><i
                                            class="fas fa-trash"></i></button>
                                </td>
                            </tr>

                            <!-- Modal Edit Informasi -->
                            <x-modal id="editModal{{ $loop->iteration }}" title="Edit Kondisi Meter"
                                route="{{ route('kondisi.update', $kondisi->id) }}" method="PUT"
                                primaryBtnTitle="Simpan Perubahan" secondaryBtnTitle="Batal" primaryBtnClass="btn-warning">
                                <div class="mb-3">
                                    <label for="kondisi" class="form-label">Kondisi</label>
                                    <input type="text" class="form-control" id="kondisi" name="kondisi"
                                        value="{{ $kondisi->kondisi }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="kode" class="form-label">Kode</label>
                                    <input type="text" class="form-control" id="kode" name="kode"
                                        value="{{ $kondisi->kode }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="keterangan" name="keterangan"
                                        value="{{ $kondisi->keterangan }}" required>
                                </div>
                            </x-modal>

                            <!-- Modal Hapus Informasi -->
                            <x-modal id="deleteModal{{ $loop->iteration }}" title="Delete Info"
                                route="{{ route('kondisi.destroy', $kondisi->id) }}" method="delete"
                                primaryBtnClass="btn-danger" primaryBtnTitle="Hapus" secondaryBtnTitle="Batal">
                                <div>
                                    Apakah Anda yakin ingin menghapus Kondisi ini?
                                    <p><strong>{{ $kondisi->kondisi }}</strong> - {{ $kondisi->kode }}</p>
                                </div>
                            </x-modal>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal id="createKondisiMeterModal" modalSize="modal-md" title="Tambah Kondisi Meter"
        route="{{ route('kondisi.store') }}" method="POST" primaryBtnTitle="Simpan" secondaryBtnTitle="Batal">
        <div class="mb-3">
            <label for="kondisi" class="form-label">Kondisi</label>
            <input type="text" class="form-control" id="kondisi" name="kondisi" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" required>
        </div>

        <div class="mb-3">
            <label for="kode" class="form-label">Kode</label>
            <input type="text" class="form-control" id="kode" name="kode" required>
        </div>
    </x-modal>
@endsection

@push('script')
    <script>
        var isMobile = window.innerWidth <= 768;
        $(document).ready(function() {
            $('#myTable').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search...",
                    "decimal": ",",
                    "thousands": ".",
                },
                "scrollX": isMobile,
            });

            $('.dataTables_filter input[type="search"]').css({
                "marginBottom": "10px"
            });
        });
    </script>
@endpush
