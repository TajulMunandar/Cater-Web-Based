<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\KondisiMeter;
use App\Models\Pelanggan;
use App\Models\Petugas;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PelangganBaruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = 'Pelanggan Baru';

        session(['back_to_pelanggan' => route('pelanggan.baru.index')]);

        if ($request->ajax()) {
            return $this->data($request);
        }

        return view('dashboard.pages.pelanggan.pelanggan-baru')->with(compact('page'));
    }

    /**
     * Get data for DataTables
     */
    public function data(Request $request)
    {
        try {
            $pelanggans = Pelanggan::with(['wilayah', 'golongan'])
                ->select(['id', 'no_sambu', 'no_kontrol', 'nama', 'alamat', 'telepon', 'type', 'status', 'id_wilayah', 'id_gol'])
                ->latest();

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
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="' . route('pelanggan.baru.edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-pen-to-square"></i></a>';
                    $deleteBtn = '<button class="btn btn-danger btn-sm btn-delete" data-url="' . route('pelanggan.baru.destroy', $row->id) . '" data-nama="' . htmlspecialchars($row->nama) . '"><i class="fas fa-trash"></i></button>';

                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('DataTables PelangganBaru error: ' . $e->getMessage());
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $petugases = Petugas::all();
        $kondisis = KondisiMeter::all();
        $wilayahs = Wilayah::all();
        $golongans = Golongan::all();
        $page = 'Tambah Pelanggan';
        return view('dashboard.pages.data-pelanggan.create')->with(compact('page', 'petugases', 'kondisis', 'wilayahs', 'golongans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
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
            'id_petugas' => 'required|exists:petugas,id',
            'rute' => 'required|integer|min:0',
            'id_kondisi' => 'required|exists:kondisi_meters,id',
            'waktu_catat_meter' => 'required|date|before_or_equal:now',
            'stand_terakhir' => 'required|integer|min:0',
            'ket' => 'nullable|string|max:100',
            'urutan' => 'required|integer|min:0',
            'id_wilayah_detail' => 'required|exists:wilayahs,id',
            'foto_pelanggan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Create Pelanggan
            $pelanggan = Pelanggan::create([
                'no_sambu' => $validatedData['no_sambu'],
                'no_kontrol' => $validatedData['no_kontrol'],
                'nama' => $validatedData['nama'],
                'alamat' => $validatedData['alamat'],
                'telepon' => $validatedData['telepon'] ?? null,
                'type' => $validatedData['type'] ?? null,
                'id_wilayah' => $validatedData['id_wilayah'] ?? null,
                'id_gol' => $validatedData['id_gol'] ?? null,
                'status' => $validatedData['status'],
                'lat' => $validatedData['lat'] ?? null,
                'long' => $validatedData['long'] ?? null,
            ]);

            // Create Pelanggan Detail
            $pelanggan->PelangganDetail()->create([
                'id_petugas' => $validatedData['id_petugas'],
                'rute' => $validatedData['rute'],
                'id_kondisi' => $validatedData['id_kondisi'],
                'waktu_catat_meter' => \Carbon\Carbon::parse($validatedData['waktu_catat_meter'], 'Asia/Jakarta'),
                'stand_terakhir' => $validatedData['stand_terakhir'],
                'ket' => $validatedData['ket'] ?? null,
                'urutan' => $validatedData['urutan'],
                'id_wilayah' => $validatedData['id_wilayah_detail'],
            ]);

            // Handle Foto uploads
            if ($request->hasFile('foto_pelanggan')) {
                foreach ($request->file('foto_pelanggan') as $foto) {
                    if ($foto->isValid()) {
                        $path = $foto->store('pelanggan-foto', 'public');
                        $pelanggan->FotoPelanggan()->create([
                            'foto' => $path,
                        ]);
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            Cache::forget('dashboard_stats');
            Cache::forget('dashboard_pertumbuhan');
            Cache::forget('dashboard_wilayah');
            Cache::forget('dashboard_koordinat');
            Cache::forget('dashboard_pencatatan');

            $backUrl = session('back_to_pelanggan', route('pelanggan.baru.index'));
            return redirect()->to($backUrl)->with('success', 'Data pelanggan berhasil ditambahkan.');
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $errorCode = $e->errorInfo[1] ?? 0;
            $errorMessage = 'Terjadi kesalahan database. ';
            if ($errorCode == 1452) {
                $errorMessage .= 'Pastikan wilayah, golongan, petugas, dan kondisi meter yang dipilih valid.';
            } elseif ($errorCode == 1062) {
                $errorMessage .= 'No Sambung atau No Kontrol sudah ada.';
            } elseif ($errorCode == 1451) {
                $errorMessage .= 'Data referensi tidak dapat digunakan karena masih terkait dengan data lain.';
            } else {
                $errorMessage .= 'Silakan coba lagi atau hubungi administrator.';
            }

            Log::error('Database error creating pelanggan: ' . $e->getMessage());
            return redirect()->back()->with('error', $errorMessage)->withInput();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            Log::error('Unexpected error creating pelanggan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data pelanggan. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pelanggan = Pelanggan::with([
            'wilayah', 'golongan',
            'PelangganDetail.Petugas',
            'PelangganDetail.Kondisi',
            'PelangganDetail.Wilayah',
            'FotoPelanggan'
        ])->findOrFail($id);

        $page = 'Detail Pelanggan Baru';
        $back_url = session('back_to_pelanggan', route('pelanggan.baru.index'));
        return view('dashboard.pages.pelanggan.show', compact('pelanggan', 'page', 'back_url'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggan $pelanggan)
    {
        $pelanggan->load([
            'wilayah', 'golongan',
            'PelangganDetail.Petugas',
            'PelangganDetail.Kondisi',
            'PelangganDetail.Wilayah',
            'FotoPelanggan'
        ]);

        $detail = $pelanggan->PelangganDetail->first();
        $petugases = Petugas::all();
        $kondisis = KondisiMeter::all();
        $wilayahs = Wilayah::all();
        $golongans = Golongan::all();
        $page = 'Edit Pelanggan';

        return view('dashboard.pages.data-pelanggan.edit')
            ->with(compact('page', 'pelanggan', 'detail', 'petugases', 'kondisis', 'wilayahs', 'golongans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validatedData = $request->validate([
            'no_sambu' => 'required|string|max:30|unique:pelanggans,no_sambu,' . $pelanggan->id,
            'no_kontrol' => 'required|string|max:50|unique:pelanggans,no_kontrol,' . $pelanggan->id,
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:50',
            'id_wilayah' => 'nullable|exists:wilayahs,id',
            'id_gol' => 'nullable|exists:golongans,id',
            'status' => ['required', Rule::in(Pelanggan::STATUS_LIST)],
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'id_petugas' => 'required|exists:petugas,id',
            'rute' => 'required|integer|min:0',
            'id_kondisi' => 'required|exists:kondisi_meters,id',
            'waktu_catat_meter' => 'required|date|before_or_equal:now',
            'stand_terakhir' => 'required|integer|min:0',
            'ket' => 'nullable|string|max:100',
            'urutan' => 'required|integer|min:0',
            'id_wilayah_detail' => 'required|exists:wilayahs,id',
            'foto_pelanggan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hapus_foto.*' => 'nullable|exists:foto_pelanggans,id',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Update Pelanggan
            $pelanggan->update([
                'no_sambu' => $validatedData['no_sambu'],
                'no_kontrol' => $validatedData['no_kontrol'],
                'nama' => $validatedData['nama'],
                'alamat' => $validatedData['alamat'],
                'telepon' => $validatedData['telepon'] ?? null,
                'type' => $validatedData['type'] ?? null,
                'id_wilayah' => $validatedData['id_wilayah'] ?? null,
                'id_gol' => $validatedData['id_gol'] ?? null,
                'status' => $validatedData['status'],
                'lat' => $validatedData['lat'] ?? null,
                'long' => $validatedData['long'] ?? null,
            ]);

            // Update or create Pelanggan Detail (first record)
            $detailData = [
                'id_petugas' => $validatedData['id_petugas'],
                'rute' => $validatedData['rute'],
                'id_kondisi' => $validatedData['id_kondisi'],
                'waktu_catat_meter' => \Carbon\Carbon::parse($validatedData['waktu_catat_meter'], 'Asia/Jakarta'),
                'stand_terakhir' => $validatedData['stand_terakhir'],
                'ket' => $validatedData['ket'] ?? null,
                'urutan' => $validatedData['urutan'],
                'id_wilayah' => $validatedData['id_wilayah_detail'],
            ];

            $detail = $pelanggan->PelangganDetail()->firstOrCreate(
                ['id_pelanggan' => $pelanggan->id],
                $detailData
            );

            if (!$detail->wasRecentlyCreated) {
                $detail->update($detailData);
            }

            // Handle deleted photos
            if ($request->has('hapus_foto')) {
                foreach ($validatedData['hapus_foto'] as $fotoId) {
                    $foto = $pelanggan->FotoPelanggan()->find($fotoId);
                    if ($foto) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($foto->foto);
                        $foto->delete();
                    }
                }
            }

            // Handle new foto uploads
            if ($request->hasFile('foto_pelanggan')) {
                foreach ($request->file('foto_pelanggan') as $foto) {
                    if ($foto->isValid()) {
                        $path = $foto->store('pelanggan-foto', 'public');
                        $pelanggan->FotoPelanggan()->create([
                            'foto' => $path,
                        ]);
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            Cache::forget('dashboard_stats');
            Cache::forget('dashboard_pertumbuhan');
            Cache::forget('dashboard_wilayah');
            Cache::forget('dashboard_koordinat');
            Cache::forget('dashboard_pencatatan');

            $backUrl = session('back_to_pelanggan', route('pelanggan.baru.index'));
            return redirect()->to($backUrl)->with('success', 'Data pelanggan berhasil diperbarui.');
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $errorCode = $e->errorInfo[1] ?? 0;
            $errorMessage = 'Terjadi kesalahan database. ';
            if ($errorCode == 1452) {
                $errorMessage .= 'Pastikan wilayah, golongan, petugas, dan kondisi meter yang dipilih valid.';
            } elseif ($errorCode == 1062) {
                $errorMessage .= 'No Sambung atau No Kontrol sudah ada.';
            } elseif ($errorCode == 1451) {
                $errorMessage .= 'Data referensi tidak dapat digunakan karena masih terkait dengan data lain.';
            } else {
                $errorMessage .= 'Silakan coba lagi atau hubungi administrator.';
            }

            Log::error('Database error updating pelanggan: ' . $e->getMessage());
            return redirect()->back()->with('error', $errorMessage)->withInput();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            Log::error('Unexpected error updating pelanggan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data pelanggan. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Hapus foto dari storage (fisik — tidak perlu di-restore)
            foreach ($pelanggan->FotoPelanggan as $foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($foto->foto);
            }

            // Hapus record foto (hard delete — data foto tidak perlu dipulihkan)
            $pelanggan->FotoPelanggan()->delete();

            // Hapus detail pelanggan (hard delete)
            $pelanggan->PelangganDetail()->delete();

            // Soft delete pelanggan (deleted_at terisi, data tetap di DB)
            $pelanggan->delete();

            \Illuminate\Support\Facades\DB::commit();

            Cache::forget('dashboard_stats');
            Cache::forget('dashboard_pertumbuhan');
            Cache::forget('dashboard_wilayah');
            Cache::forget('dashboard_koordinat');
            Cache::forget('dashboard_pencatatan');

            return redirect()->route('pelanggan.baru.index')
                ->with('delete', 'Data pelanggan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $errorCode = $e->errorInfo[1] ?? 0;
            $errorMessage = 'Terjadi kesalahan database. ';
            if ($errorCode == 1451) {
                $errorMessage .= 'Data pelanggan masih digunakan di tabel lain dan tidak dapat dihapus.';
            } elseif ($errorCode == 1452) {
                $errorMessage .= 'Data referensi yang dipilih tidak valid.';
            } else {
                $errorMessage .= 'Silakan coba lagi atau hubungi administrator.';
            }

            Log::error('Database error deleting pelanggan: ' . $e->getMessage());
            return redirect()->back()->with('error', $errorMessage);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            Log::error('Unexpected error deleting pelanggan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data pelanggan. Silakan coba lagi.');
        }
    }

    /**
     * Restore a soft-deleted pelanggan.
     */
    public function restore(string $id)
    {
        $pelanggan = Pelanggan::withTrashed()->findOrFail($id);
        $pelanggan->restore();

        Cache::forget('dashboard_stats');
        Cache::forget('dashboard_pertumbuhan');
        Cache::forget('dashboard_wilayah');
        Cache::forget('dashboard_koordinat');
            Cache::forget('dashboard_pencatatan');

        return redirect()->route('pelanggan.baru.index')
            ->with('success', 'Data pelanggan berhasil dipulihkan.');
    }
}
