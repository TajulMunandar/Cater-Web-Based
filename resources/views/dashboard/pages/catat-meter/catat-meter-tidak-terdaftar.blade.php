@extends('dashboard.partials.main')

@section('title', 'Tidak Terdaftar')

@section('content')
@include('dashboard.partials.page-header', ['title' => 'Catat Meter Tidak Terdaftar', 'subtitle' => 'Pencatatan meter pelanggan tidak terdaftar', 'icon' => 'clipboard-x'])

<div class="row mt-2">
    <div class="col">
        <div class="card" style="border:none;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.06);overflow:hidden;">
            <!-- Tab Navigation -->
            <div style="padding:16px 20px 0;background:#fff;border-bottom:1px solid #e2e8f0;">
                <ul class="nav nav-pills" style="gap:6px;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cater.index') }}" style="color:#64748b;border-radius:8px;padding:8px 20px;font-size:13px;">Catat Meter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('cater.tidak-terdaftar') }}" style="background:#3b82f6;color:#fff;border-radius:8px;padding:8px 20px;font-weight:700;font-size:13px;">Catat Meter Tidak Terdaftar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cater.urutan') }}" style="color:#64748b;border-radius:8px;padding:8px 20px;font-size:13px;">Urutan Catat Meter</a>
                    </li>
                </ul>
            </div>
            <!-- Table -->
            <div style="padding:16px 20px 20px;background:#fff;">
                <div style="overflow-x:auto;">
                    <table class="table" id="myTable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Photo</th>
                                <th>Stand Meter</th>
                                <th>Waktu</th>
                                <th>Nama</th>
                                <th>Wilayah</th>
                                <th>Kondisi Meter</th>
                                <th>Petugas</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('head')
<style>
th { background:#1e3a8a !important; color:#fff !important; font-weight:700 !important; font-size:13px !important; padding:12px 16px !important; text-align:center !important; border:none !important; }
#myTable tbody td { padding:12px 16px !important; font-size:13px !important; color:#334155 !important; border-bottom:1px solid #f1f5f9 !important; vertical-align:middle !important; }
#myTable tbody tr:nth-child(odd) td { background:#ffffff; }
#myTable tbody tr:nth-child(even) td { background:#f8faff; }
#myTable tbody tr:hover td { background:#eff6ff !important; transition:background 0.15s ease; }
</style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("cater.data-tidak-terdaftar") }}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'foto', orderable: false, searchable: false },
                    { data: 'stand' },
                    { data: 'waktu' },
                    { data: 'pelanggan' },
                    { data: 'wilayah' },
                    { data: 'kondisi' },
                    { data: 'petugas' },
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
            });
        });
    </script>
@endpush
