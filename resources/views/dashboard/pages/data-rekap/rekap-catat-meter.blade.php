@extends('dashboard.partials.main')

@php
$pelanggans = App\Models\Pelanggan::all();
$petugases = App\Models\Petugas::all();
$kondisis = App\Models\KondisiMeter::all();
@endphp

@section('content')
    <div class="row">
        <div class="col">

            <h3>Rekap Catat Meter</h3>
        </div>
        <div class="col">
            <button class="btn btn-primary float-end" id="tambahBtn">Tambah</button>
        </div>
    </div>
    <div class="row mt-2">

        <div class="card p-3">
            <ul class="nav nav-pills ">
                <li class="nav-item">
                    <a class="nav-link active" href="/rekap/index"> Rekap Catat Meter</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/rekap/wilayah">Rekap Wilayah</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/rekap/kondisi">Rekap Kondisi</a>
                </li>
            </ul>
            <div class="card-body">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Petugas</th>
                            <th>Kondisi</th>
                            <th>Waktu</th>
                            <th>Stand</th>
                            <th>GPS</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Catat Meter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="id_pelanggan">Pelanggan</label>
                            <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach($pelanggans as $pelanggan)
                                    <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_petugas">Petugas</label>
                            <select class="form-control" id="id_petugas" name="id_petugas" required>
                                <option value="">Pilih Petugas</option>
                                @foreach($petugases as $petugas)
                                    <option value="{{ $petugas->id }}">{{ $petugas->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_kondisi">Kondisi</label>
                            <select class="form-control" id="id_kondisi" name="id_kondisi" required>
                                <option value="">Pilih Kondisi</option>
                                @foreach($kondisis as $kondisi)
                                    <option value="{{ $kondisi->id }}">{{ $kondisi->nama_kondisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="waktu">Waktu</label>
                            <input type="datetime-local" class="form-control" id="waktu" name="waktu" required>
                        </div>
                        <div class="form-group">
                            <label for="stand">Stand</label>
                            <input type="number" class="form-control" id="stand" name="stand" required>
                        </div>
                        <div class="form-group">
                            <label for="gps">GPS</label>
                            <input type="text" class="form-control" id="gps" name="gps" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Catat Meter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            <label for="edit_id_pelanggan">Pelanggan</label>
                            <select class="form-control" id="edit_id_pelanggan" name="id_pelanggan" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach($pelanggans as $pelanggan)
                                    <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_id_petugas">Petugas</label>
                            <select class="form-control" id="edit_id_petugas" name="id_petugas" required>
                                <option value="">Pilih Petugas</option>
                                @foreach($petugases as $petugas)
                                    <option value="{{ $petugas->id }}">{{ $petugas->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_id_kondisi">Kondisi</label>
                            <select class="form-control" id="edit_id_kondisi" name="id_kondisi" required>
                                <option value="">Pilih Kondisi</option>
                                @foreach($kondisis as $kondisi)
                                    <option value="{{ $kondisi->id }}">{{ $kondisi->nama_kondisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_waktu">Waktu</label>
                            <input type="datetime-local" class="form-control" id="edit_waktu" name="waktu" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_stand">Stand</label>
                            <input type="number" class="form-control" id="edit_stand" name="stand" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_gps">GPS</label>
                            <input type="text" class="form-control" id="edit_gps" name="gps" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_status">Status</label>
                            <select class="form-control" id="edit_status" name="status" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Catat Meter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this record?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var isMobile = window.innerWidth <= 768;
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("rekap.data-catat-meter") }}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'pelanggan' },
                    { data: 'petugas' },
                    { data: 'kondisi' },
                    { data: 'waktu' },
                    { data: 'stand' },
                    { data: 'gps' },
                    { data: 'status' },
                    { data: 'action', orderable: false, searchable: false }
                ],
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

            // Create
            $('#tambahBtn').click(function() {
                $('#createModal').modal('show');
            });

            $('#createForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/rekap',
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#createModal').modal('hide');
                        $('#myTable').DataTable().ajax.reload();
                        alert(response.success);
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });

            // Edit
            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                $.get('/rekap/' + id, function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_id_pelanggan').val(data.id_pelanggan);
                    $('#edit_id_petugas').val(data.id_petugas);
                    $('#edit_id_kondisi').val(data.id_kondisi);
                    $('#edit_waktu').val(data.waktu ? new Date(data.waktu).toISOString().slice(0, 16) : '');
                    $('#edit_stand').val(data.stand);
                    $('#edit_gps').val(data.gps);
                    $('#edit_status').val(data.status);
                    $('#editModal').modal('show');
                });
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();
                var id = $('#edit_id').val();
                $.ajax({
                    url: '/rekap/' + id,
                    method: 'PUT',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#editModal').modal('hide');
                        $('#myTable').DataTable().ajax.reload();
                        alert(response.success);
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });

            // Delete
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                $('#deleteModal').modal('show');
                $('#confirmDelete').data('id', id);
            });

            $('#confirmDelete').click(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/rekap/' + id,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        $('#myTable').DataTable().ajax.reload();
                        alert(response.success);
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
