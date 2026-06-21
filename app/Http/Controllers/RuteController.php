<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class RuteController extends Controller
{
    public function data(Request $request)
    {
        $query = Rute::with('wilayah')->select(['id', 'id_wilayah', 'rute', 'kode', 'ket']);

        if ($request->filled('id_wilayah')) {
            $query->where('id_wilayah', $request->id_wilayah);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('wilayah', function ($row) {
                return $row->wilayah ? $row->wilayah->wilayah : '-';
            })
            ->addColumn('action', function ($row) {
                $editBtn = '<button class="btn-action-rute btn-edit-rute" data-id="' . $row->id . '" title="Edit Rute"><i class="fas fa-pen-to-square"></i></button>';
                $deleteBtn = '<button class="btn-action-rute btn-delete-rute" data-id="' . $row->id . '" title="Hapus Rute"><i class="fas fa-trash"></i></button>';

                $editModal = '<div class="modal fade" id="editRuteModal' . $row->id . '" tabindex="-1" aria-labelledby="editRuteModalLabel' . $row->id . '" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="' . route('rute.update', $row->id) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('PUT') . '
                            <div class="modal-header">
                                <h5 class="modal-title" id="editRuteModalLabel' . $row->id . '">Edit Rute</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit_rute' . $row->id . '" class="form-label">Rute</label>
                                    <input type="text" class="form-control" id="edit_rute' . $row->id . '" name="rute" value="' . htmlspecialchars($row->rute) . '">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_kode' . $row->id . '" class="form-label">Kode</label>
                                    <input type="text" class="form-control" id="edit_kode' . $row->id . '" name="kode" value="' . htmlspecialchars($row->kode ?? '') . '">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_ket' . $row->id . '" class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="edit_ket' . $row->id . '" name="ket" value="' . htmlspecialchars($row->ket ?? '') . '">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>';

                $deleteUrl = route('rute.destroy', $row->id);
                $deleteModal = '<div class="modal fade" id="deleteRuteModal' . $row->id . '" tabindex="-1" aria-labelledby="deleteRuteModalLabel' . $row->id . '" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="' . $deleteUrl . '" method="POST">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteRuteModalLabel' . $row->id . '">Hapus Rute</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center py-3">
                                        <div class="mb-3" style="font-size:2rem;color:var(--color-danger);">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <p>Yakin ingin menghapus rute <strong>' . htmlspecialchars($row->rute) . '</strong>?</p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
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

    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_wilayah' => 'nullable|exists:wilayahs,id',
                'rute' => 'required|string|max:50',
                'kode' => 'nullable|string|max:20',
                'ket' => 'nullable|string|max:100',
            ]);

            Rute::create($request->all());
            return redirect()->back()->with('success', 'Data rute berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error creating rute: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data rute');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'id_wilayah' => 'nullable|exists:wilayahs,id',
                'rute' => 'required|string|max:50',
                'kode' => 'nullable|string|max:20',
                'ket' => 'nullable|string|max:100',
            ]);

            $rute = Rute::findOrFail($id);
            $rute->update($request->all());
            return redirect()->back()->with('update', 'Data rute berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating rute: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data rute');
        }
    }

    public function destroy($id)
    {
        try {
            $rute = Rute::findOrFail($id);
            $rute->delete();
            return redirect()->back()->with('delete', 'Data rute berhasil dihapus');
        } catch (\Exception $e) {
            Log::error("Error deleting rute ID $id: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data rute');
        }
    }

    public function list($id)
    {
        $wilayah = Wilayah::findOrFail($id);
        $rutes = Rute::where('id_wilayah', $id)->get();
        return view('dashboard.pages.settings.rute-list', compact('wilayah', 'rutes'))->render();
    }
}
