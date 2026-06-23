<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\PelangganDetail;
use App\Models\Golongan;
use App\Models\Rute;
use App\Models\Wilayah;
use App\Models\Petugas;
use App\Models\KondisiMeter;
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
            $pelanggans = Pelanggan::with(['rute.wilayah', 'golongan', 'FotoPelanggan'])
                ->select(['id', 'no_sambu', 'nama', 'alamat', 'telepon', 'type', 'status', 'id_rute', 'id_gol']);

            return DataTables::of($pelanggans)
                ->addIndexColumn()
                ->addColumn('wilayah', function ($row) {
                    return $row->rute && $row->rute->wilayah ? $row->rute->wilayah->wilayah : '-';
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
                    $url = str_starts_with($foto->foto, 'http') ? $foto->foto : Storage::url($foto->foto);
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
                    $fotoPath = $row->FotoPelanggan->first()->foto;
                    return str_starts_with($fotoPath, 'http') ? $fotoPath : Storage::url($fotoPath);
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
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:50',
            'id_rute' => 'nullable|exists:rutes,id',
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
        $pelanggan->load(['rute.wilayah', 'golongan', 'PelangganDetail.Petugas', 'PelangganDetail.Kondisi', 'FotoPelanggan']);

        $back_url = session('back_to_pelanggan', route('pelanggan.index'));
        $golongans = Golongan::orderBy('nama')->get();
        $rutes = Rute::with('wilayah')->orderBy('rute')->get();
        $petugasList = Petugas::orderBy('nama')->get();
        $kondisiList = KondisiMeter::orderBy('kondisi')->get();

        return view('dashboard.pages.pelanggan.show', compact('pelanggan', 'page', 'back_url', 'golongans', 'rutes', 'petugasList', 'kondisiList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'no_sambu' => ['required', 'string', 'max:30', Rule::unique('pelanggans', 'no_sambu')->ignore($pelanggan->id)],
            'type' => 'nullable|string|max:50',
            'id_rute' => 'nullable|exists:rutes,id',
            'id_gol' => 'nullable|exists:golongans,id',
            'status' => ['required', Rule::in(Pelanggan::STATUS_LIST)],
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'hapus_foto.*' => 'nullable|exists:foto_pelanggans,id',
            'foto_pelanggan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $pelanggan->update($validated);

            // Handle deleted photos
            if ($request->has('hapus_foto')) {
                foreach ($validated['hapus_foto'] as $fotoId) {
                    $foto = $pelanggan->FotoPelanggan()->find($fotoId);
                    if ($foto) {
                        Storage::disk('public')->delete($foto->foto);
                        $foto->delete();
                    }
                }
            }

            // Handle new photo uploads
            if ($request->hasFile('foto_pelanggan')) {
                foreach ($request->file('foto_pelanggan') as $foto) {
                    if ($foto->isValid()) {
                        $path = $foto->store('pelanggan-foto', 'public');
                        $pelanggan->FotoPelanggan()->create(['foto' => $path]);
                    }
                }
            }

            $pelanggan->load(['rute.wilayah', 'golongan']);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui.',
                'data' => [
                    'nama' => $pelanggan->nama,
                    'alamat' => $pelanggan->alamat,
                    'no_sambu' => $pelanggan->no_sambu,
                    'telepon' => $pelanggan->telepon,
                    'type' => $pelanggan->type,
                    'id_rute' => $pelanggan->id_rute,
                    'id_gol' => $pelanggan->id_gol,
                    'status' => $pelanggan->status,
                    'lat' => $pelanggan->lat,
                    'long' => $pelanggan->long,
                    'rute_label' => $pelanggan->rute?->rute . ($pelanggan->rute?->wilayah ? ' - ' . $pelanggan->rute->wilayah->wilayah : ''),
                    'golongan_label' => $pelanggan->golongan?->nama,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating pelanggan: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui data.'], 500);
        }
    }

    /**
     * Store operational detail for a customer.
     */
    public function storeDetail(Request $request, Pelanggan $pelanggan)
    {
        try {
            $validated = $request->validate([
                'id_petugas' => 'required|exists:petugas,id',
                'id_kondisi' => 'required|exists:kondisi_meters,id',
                'stand_terakhir' => 'required|integer',
                'ket' => 'nullable|string|max:255',
                'urutan' => 'required|integer',
            ]);

            $validated['id_pelanggan'] = $pelanggan->id;
            $detail = PelangganDetail::updateOrCreate(
                ['id_pelanggan' => $pelanggan->id],
                $validated
            );
            $detail->load(['Petugas', 'Kondisi']);
            return response()->json([
                'success' => true,
                'message' => 'Data operasional berhasil disimpan.',
                'data' => [
                    'id_petugas' => $detail->id_petugas,
                    'id_kondisi' => $detail->id_kondisi,
                    'stand_terakhir' => $detail->stand_terakhir,
                    'ket' => $detail->ket,
                    'urutan' => $detail->urutan,
                    'petugas_label' => $detail->Petugas?->nama ?? '-',
                    'kondisi_label' => $detail->Kondisi?->kondisi ?? '-',
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error storing pelanggan detail: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data operasional.'], 500);
        }
    }

    public function select2(Request $request)
    {
        $search = $request->query('q', '');
        $page = (int) $request->query('page', 1);
        $limit = 20;

        $query = Pelanggan::select('id', 'nama', 'no_sambu')
            ->whereNull('deleted_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_sambu', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $items = $query->orderBy('nama')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get()
            ->map(fn($p) => ['id' => $p->id, 'text' => "{$p->nama} ({$p->no_sambu})"]);

        return response()->json([
            'results' => $items,
            'pagination' => ['more' => ($page * $limit) < $total],
        ]);
    }

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
