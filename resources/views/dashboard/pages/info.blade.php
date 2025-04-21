@extends('dashboard.partials.main')

@section('content')
    <div class="row">
        <div class="col">

            <h3 class="fw-bolder">Informasi</h3>
        </div>
        <div class="col">
            <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addModal">Tambah</button>
        </div>
    </div>
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

        <div class="card p-3">
            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Waktu</th>
                            <th>Jenis Info</th>
                            <th>Info</th>
                            <th>Alamat</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($infos as $info)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $info->waktu }}</td>
                                <td>{{ $info->jenis }}</td>
                                <td>{{ $info->info }}</td>
                                <td>{{ $info->alamat }}</td>
                                <td>
                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $loop->iteration }}">Edit</button>
                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $loop->iteration }}">Delete</button>
                                </td>
                            </tr>

                            <!-- Modal Edit Informasi -->
                            <x-modal id="editModal{{ $loop->iteration }}" title="Edit Info"
                                route="{{ route('info.update', $info->id) }}" method="PUT" primaryBtnTitle="Save Changes"
                                primaryBtnClass="btn-warning">
                                <div class="mb-3">
                                    <label for="petugas_id_{{ $loop->iteration }}" class="form-label">Petugas</label>
                                    <select name="id_petugas" id="petugas_id_{{ $loop->iteration }}" class="form-select"
                                        required>
                                        <option value="" disabled>Pilih Petugas</option>
                                        @foreach ($petugas as $p)
                                            <option value="{{ $p->id }}"
                                                {{ $p->id == $info->id_petugas ? 'selected' : '' }}>
                                                {{ $p->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="gps_{{ $loop->iteration }}" class="form-label">GPS</label>
                                    <input type="text" name="gps" id="gps_{{ $loop->iteration }}"
                                        class="form-control" value="{{ $info->gps }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="waktu_{{ $loop->iteration }}" class="form-label">Waktu</label>
                                    <input type="datetime-local" name="waktu" id="waktu_{{ $loop->iteration }}"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::parse($info->waktu)->format('Y-m-d\TH:i') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_{{ $loop->iteration }}" class="form-label">Jenis Info</label>
                                    <input type="text" name="jenis" id="jenis_{{ $loop->iteration }}"
                                        class="form-control" value="{{ $info->jenis }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="info_{{ $loop->iteration }}" class="form-label">Info</label>
                                    <textarea name="info" id="info_{{ $loop->iteration }}" rows="3" class="form-control" required>{{ $info->info }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat_{{ $loop->iteration }}" class="form-label">Alamat</label>
                                    <textarea name="alamat" id="alamat_{{ $loop->iteration }}" rows="2" class="form-control" required>{{ $info->alamat }}</textarea>
                                </div>
                            </x-modal>

                            <!-- Modal Hapus Informasi -->
                            <x-modal id="deleteModal{{ $loop->iteration }}" title="Delete Info"
                                route="{{ route('info.destroy', $info->id) }}" method="delete"
                                primaryBtnClass="btn-danger" primaryBtnTitle="Delete">
                                <div>
                                    Apakah Anda yakin ingin menghapus informasi ini?
                                    <p><strong>{{ $info->jenis }}</strong> - {{ $info->waktu }}</p>
                                </div>
                            </x-modal>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Informasi -->
    <x-modal id="addModal" title="Tambah Info" route="{{ route('info.store') }}" method="POST">
        <div class="mb-3">
            <label for="petugas_id" class="form-label">Petugas</label>
            <select name="id_petugas" id="petugas_id" class="form-select" required>
                <option value="" disabled selected>Pilih Petugas</option>
                @foreach ($petugas as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="gps" class="form-label">GPS</label>
            <input type="text" name="gps" id="gps" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="waktu" class="form-label">Waktu</label>
            <input type="datetime-local" name="waktu" id="waktu" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis Info</label>
            <input type="text" name="jenis" id="jenis" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="info" class="form-label">Info</label>
            <textarea name="info" id="info" rows="3" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" id="alamat" rows="2" class="form-control" required></textarea>
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
