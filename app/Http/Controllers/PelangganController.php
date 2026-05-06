<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Wilayah;
use App\Models\Golongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = 'Pelanggan';
        
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
        $pelanggans = Pelanggan::with(['wilayah', 'golongan'])
            ->select(['id', 'no_sambung', 'no_kontrol', 'nama', 'alamat', 'telepon', 'type', 'status', 'id_wilayah', 'id_gol']);
        
        return DataTables::of($pelanggans)
            ->addIndexColumn()
            ->addColumn('wilayah', function ($row) {
                return $row->wilayah ? $row->wilayah->wilayah : '-';
            })
            ->addColumn('golongan', function ($row) {
                return $row->golongan ? $row->golongan->nama : '-';
            })
            ->addColumn('status_badge', function ($row) {
                return $row->status == 'aktif' 
                    ? '<span class="badge bg-success">Aktif</span>' 
                    : '<span class="badge bg-danger">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                $editBtn = '<button class="btn btn-warning btn-sm btn-edit" data-id="' . $row->id . '"><i class="fas fa-pen-to-square"></i></button>';
                $deleteBtn = '<button class="btn btn-danger btn-sm btn-delete" data-id="' . $row->id . '"><i class="fas fa-trash"></i></button>';

                // Edit Modal
                $editModal = '
                <div class="modal fade" id="editModal' . $row->id . '" tabindex="-1" aria-labelledby="editModalLabel' . $row->id . '" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action="' . route('pelanggan.update', $row->id) . '" method="POST">
                                ' . csrf_field() . '
                                ' . method_field('PUT') . '
                                <div class="modal-header bg-dark">
                                    <h5 class="modal-title text-white">Edit Pelanggan</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">No Sambu</label>
                                                <input type="text" class="form-control" name="no_sambu" value="' . htmlspecialchars($row->no_sambu) . '" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">No Kontrol</label>
                                                <input type="text" class="form-control" name="no_kontrol" value="' . htmlspecialchars($row->no_kontrol) . '" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama</label>
                                                <input type="text" class="form-control" name="nama" value="' . htmlspecialchars($row->nama) . '" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Telepon</label>
                                                <input type="text" class="form-control" name="telepon" value="' . htmlspecialchars($row->telepon ?? '') . '">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Alamat</label>
                                                <textarea class="form-control" name="alamat" rows="3" required>' . htmlspecialchars($row->alamat) . '</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Wilayah</label>
                                                <select class="form-select" name="id_wilayah">
                                                    <option value="">Pilih Wilayah</option>
                                                    ' . \App\Models\Wilayah::all()->map(function($w) use ($row) {
                                                        return '<option value="' . $w->id . '" ' . ($row->id_wilayah == $w->id ? 'selected' : '') . '>' . htmlspecialchars($w->wilayah) . '</option>';
                                                    })->implode('') . '
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Golongan</label>
                                                <select class="form-select" name="id_gol">
                                                    <option value="">Pilih Golongan</option>
                                                    ' . \App\Models\Golongan::all()->map(function($g) use ($row) {
                                                        return '<option value="' . $g->id . '" ' . ($row->id_gol == $g->id ? 'selected' : '') . '>' . htmlspecialchars($g->nama) . '</option>';
                                                    })->implode('') . '
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select class="form-select" name="status">
                                                    <option value="aktif" ' . ($row->status == 'aktif' ? 'selected' : '') . '>Aktif</option>
                                                    <option value="non-aktif" ' . ($row->status == 'non-aktif' ? 'selected' : '') . '>Non-Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';

                // Delete Modal
                $deleteModal = '
                <div class="modal fade" id="deleteModal' . $row->id . '" tabindex="-1" aria-labelledby="deleteModalLabel' . $row->id . '" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="' . route('pelanggan.destroy', $row->id) . '" method="POST">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <div class="modal-header bg-dark">
                                    <h5 class="modal-title text-white">Hapus Pelanggan</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Yakin ingin menghapus pelanggan <strong>' . htmlspecialchars($row->nama) . '</strong>?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';

                return $editBtn . ' ' . $deleteBtn . $editModal . $deleteModal;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'no_sambu' => 'required|string|max:30|unique:pelanggans,no_sambu',
                'no_kontrol' => 'required|string|max:50|unique:pelanggans,no_kontrol',
                'nama' => 'required|string|max:100',
                'alamat' => 'required|string',
                'telepon' => 'nullable|string|max:20',
                'id_wilayah' => 'nullable|exists:wilayahs,id',
                'id_gol' => 'nullable|exists:golongans,id',
                'status' => 'required|in:aktif,non-aktif',
            ]);

            Pelanggan::create($request->all());

            return redirect()->back()->with('success', 'Data pelanggan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating pelanggan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data pelanggan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);

            $request->validate([
                'no_sambu' => 'required|string|max:30|unique:pelanggans,no_sambu,' . $id,
                'no_kontrol' => 'required|string|max:50|unique:pelanggans,no_kontrol,' . $id,
                'nama' => 'required|string|max:100',
                'alamat' => 'required|string',
                'telepon' => 'nullable|string|max:20',
                'id_wilayah' => 'nullable|exists:wilayahs,id',
                'id_gol' => 'nullable|exists:golongans,id',
                'status' => 'required|in:aktif,non-aktif',
            ]);

            $pelanggan->update($request->all());

            return redirect()->back()->with('update', 'Data pelanggan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating pelanggan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data pelanggan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->delete();

            return redirect()->back()->with('delete', 'Data pelanggan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting pelanggan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data pelanggan.');
        }
    }
}