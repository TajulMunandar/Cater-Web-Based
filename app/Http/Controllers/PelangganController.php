<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Wilayah;
use App\Models\Golongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = 'Pelanggan';

        session(['back_to_pelanggan' => route('pelanggan.index')]);

        if ($request->ajax()) {
            return $this->data($request);
        }

        return view('dashboard.pages.pelanggan.pelanggan')->with(compact('page'));
    }

    /**
     * Get data for DataTables
     */
    public function data(Request $request)
    {
        try {
            $pelanggans = Pelanggan::with(['wilayah', 'golongan', 'FotoPelanggan'])
                ->select(['id', 'no_sambu', 'no_kontrol', 'nama', 'alamat', 'telepon', 'type', 'status', 'id_wilayah', 'id_gol']);

            return DataTables::of($pelanggans)
                ->addIndexColumn()
                ->addColumn('wilayah', function ($row) {
                    return $row->wilayah ? $row->wilayah->wilayah : '-';
                })
                ->addColumn('golongan', function ($row) {
                    return $row->golongan ? $row->golongan->nama : '-';
                })
            ->addColumn('status_badge', function ($row) {
                return $row->status == Pelanggan::STATUS_AKTIF
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Non-Aktif</span>';
            })
            ->addColumn('status_text', function ($row) {
                return $row->status;
            })
            ->addColumn('foto', function ($row) {
                if ($row->FotoPelanggan && $row->FotoPelanggan->count() > 0) {
                    $foto = $row->FotoPelanggan->first();
                    $url = Storage::url($foto->foto);
                    $nama = basename($foto->foto);
                    return '<div class="d-flex align-items-center gap-2">
                        <img src="' . $url . '" width="45" height="45" style="object-fit:cover;border-radius:6px;" alt="Foto" onerror="this.src=\'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2245%22 height=%2245%22%3E%3Crect fill=%22%23f8f9fa%22 width=%2245%22 height=%2245%22/%3E%3Ctext fill=%22%236c757d%22 font-size=%228%22 x=%2250%%22 y=%2250%%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22%3ENo%20Img%3C/text%3E%3C/svg%3E\';this.onerror=null;">
                    </div>';
                }
                return '<div class="d-flex align-items-center gap-2">
                    <div style="width:45px;height:45px;background:#f0f0f0;border-radius:6px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-image text-muted" style="font-size:18px;"></i></div>
                    <small class="text-muted" style="font-size:11px;">-</small>
                </div>';
            })
            ->addColumn('foto_url', function ($row) {
                if ($row->FotoPelanggan && $row->FotoPelanggan->count() > 0) {
                    return Storage::url($row->FotoPelanggan->first()->foto);
                }
                return null;
            })
            ->addColumn('foto_nama', function ($row) {
                if ($row->FotoPelanggan && $row->FotoPelanggan->count() > 0) {
                    return basename($row->FotoPelanggan->first()->foto);
                }
                return null;
            })
            ->addColumn('action', function ($row) {
                $isActive = $row->status == Pelanggan::STATUS_AKTIF;
                $btnClass = $isActive ? 'btn-danger' : 'btn-success';
                $icon = $isActive ? 'fa-times-circle' : 'fa-check-circle';
                $label = $isActive ? 'Non-Aktifkan' : 'Aktifkan';
                return '<button type="button" class="btn btn-sm ' . $btnClass . ' btn-toggle-status" data-id="' . $row->id . '" data-nama="' . e($row->nama) . '" data-status="' . $row->status . '">
                    <i class="fas ' . $icon . ' me-1"></i>' . $label . '</button>';
            })

                ->rawColumns(['status_badge', 'foto', 'action'])
                ->make(true);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('DataTables Pelanggan error: ' . $e->getMessage());
            return response()->json([
                'draw' => request('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Gagal memuat data. Silakan refresh halaman.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('DataTables unexpected error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_sambu' => 'required|string|max:30|unique:pelanggans,no_sambu',
            'no_kontrol' => 'required|string|max:50|unique:pelanggans,no_kontrol',
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:50',
            'id_wilayah' => 'nullable|exists:wilayahs,id',
            'id_gol' => 'nullable|exists:golongans,id',
            'status' => ['required', Rule::in(Pelanggan::STATUS_LIST)],
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
        ]);

        try {
            Pelanggan::create($validated);

            return redirect()->back()->with('success', 'Data pelanggan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating pelanggan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data pelanggan: ' . $e->getMessage())->withInput();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Pelanggan $pelanggan)
    {
        $page = 'Detail Pelanggan';

        // Load relationships
        $pelanggan->load(['wilayah', 'golongan', 'PelangganDetail.Petugas', 'PelangganDetail.Kondisi', 'PelangganDetail.Wilayah', 'FotoPelanggan']);

        $back_url = session('back_to_pelanggan', route('pelanggan.index'));

        return view('dashboard.pages.pelanggan.show', compact('pelanggan', 'page', 'back_url'));
    }

    /**
     * Toggle customer active/inactive status.
     */
    public function toggleStatus(Pelanggan $pelanggan)
    {
        try {
            $pelanggan->status = $pelanggan->status == Pelanggan::STATUS_AKTIF
                ? Pelanggan::STATUS_NON_AKTIF
                : Pelanggan::STATUS_AKTIF;
            $pelanggan->save();

            $statusLabel = $pelanggan->status == Pelanggan::STATUS_AKTIF ? 'diaktifkan' : 'dinonaktifkan';
            return response()->json(['success' => true, 'message' => "Status pelanggan berhasil {$statusLabel}."]);
        } catch (\Exception $e) {
            Log::error('Error toggling status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status pelanggan.'], 500);
        }
    }
}
