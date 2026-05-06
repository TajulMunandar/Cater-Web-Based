<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class GolonganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = 'Golongan';
        return view('dashboard.pages.settings.golongan')->with(compact('page'));
    }

    public function data(Request $request)
    {
        $golongans = Golongan::select(['id', 'kode', 'nama', 'tarif_per_m3', 'biaya_admin', 'keterangan']);
        
        return DataTables::of($golongans)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editBtn = '<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal' . $row->id . '"><i class="fas fa-pen-to-square"></i></button>';
                    $deleteBtn = '<button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row->id . '"><i class="fas fa-trash"></i></button>';

                    // Edit Modal
                    $editModal = '
                    <div class="modal fade" id="editModal' . $row->id . '" tabindex="-1" aria-labelledby="editModalLabel' . $row->id . '" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="' . route('golongan.update', $row->id) . '" method="POST">
                                    ' . csrf_field() . '
                                    ' . method_field('PUT') . '
                                    <div class="modal-header bg-dark">
                                        <h5 class="modal-title text-white">Edit Golongan</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Kode</label>
                                            <input type="text" class="form-control" name="kode" value="' . htmlspecialchars($row->kode) . '" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" class="form-control" name="nama" value="' . htmlspecialchars($row->nama) . '" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tarif per m³</label>
                                            <input type="number" class="form-control" name="tarif_per_m3" value="' . $row->tarif_per_m3 . '" step="0.01" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Biaya Admin</label>
                                            <input type="number" class="form-control" name="biaya_admin" value="' . $row->biaya_admin . '" step="0.01">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <textarea class="form-control" name="keterangan" rows="2">' . htmlspecialchars($row->keterangan ?? '') . '</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-warning">Simpan</button>
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
                                <form action="' . route('golongan.destroy', $row->id) . '" method="POST">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                    <div class="modal-header bg-dark">
                                        <h5 class="modal-title text-white">Hapus Golongan</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Yakin menghapus golongan <strong>' . htmlspecialchars($row->nama) . '</strong>?
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
                ->rawColumns(['action'])
                ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'kode' => 'required|string|max:10|unique:golongans,kode',
                'nama' => 'required|string|max:50',
                'tarif_per_m3' => 'required|numeric|min:0',
                'biaya_admin' => 'nullable|numeric|min:0',
                'keterangan' => 'nullable|string',
            ]);

            Golongan::create($request->all());

            return redirect()->back()->with('success', 'Data golongan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating Golongan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $golongan = Golongan::findOrFail($id);

            $request->validate([
                'kode' => 'required|string|max:10|unique:golongans,kode,' . $id,
                'nama' => 'required|string|max:50',
                'tarif_per_m3' => 'required|numeric|min:0',
                'biaya_admin' => 'nullable|numeric|min:0',
                'keterangan' => 'nullable|string',
            ]);

            $golongan->update($request->all());

            return redirect()->back()->with('update', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating Golongan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $golongan = Golongan::findOrFail($id);
            $golongan->delete();

            return redirect()->back()->with('delete', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting Golongan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}