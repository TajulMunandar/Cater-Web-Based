<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class WilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Wilayah';
        $wilayahs = Wilayah::all();
        return view('dashboard.pages.settings.wilayah', compact('page', 'wilayahs'));
    }

    public function data(Request $request)
    {
        $wilayahs = Wilayah::select(['id', 'wilayah', 'center_lat', 'center_long', 'ket'])
            ->withCount('rutes')
            ->latest();

        return DataTables::of($wilayahs)
            ->addIndexColumn()
            ->addColumn('rute_count', function ($row) {
                $count = $row->rutes_count;
                $badge = $count > 0
                    ? '<span class="badge-rute-count">' . $count . ' Rute</span>'
                    : '<span class="badge-rute-count badge-empty">Belum ada rute</span>';
                return $badge;
            })
            ->addColumn('action', function ($row) {
                $ruteCount = $row->rutes_count;
                $editBtn = '<button class="btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#editModal' . $row->id . '" title="Edit Wilayah"><i class="fas fa-pen-to-square"></i><span class="d-none d-md-inline"> Edit</span></button>';
                $deleteBtn = '<button class="btn-action btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row->id . '" title="Hapus Wilayah"><i class="fas fa-trash"></i><span class="d-none d-md-inline"> Hapus</span></button>';

                $editModal = '<div class="modal fade" id="editModal' . $row->id . '" tabindex="-1" aria-labelledby="editModalLabel' . $row->id . '" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="' . route('wilayah.update', $row->id) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('PUT') . '
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel' . $row->id . '">Edit Wilayah</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="wilayah' . $row->id . '" class="form-label">Nama Wilayah</label>
                                    <input type="text" class="form-control" id="wilayah' . $row->id . '" name="wilayah" value="' . htmlspecialchars($row->wilayah) . '">
                                </div>
                                <div class="mb-3">
                                    <label for="ket' . $row->id . '" class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="ket' . $row->id . '" name="ket" value="' . htmlspecialchars($row->ket) . '">
                                </div>
                                <div class="mb-3">
                                    <label for="center_lat' . $row->id . '" class="form-label">Center Latitude</label>
                                    <input type="text" class="form-control center_lat_input" id="center_lat' . $row->id . '" name="center_lat" value="' . htmlspecialchars($row->center_lat) . '" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="center_long' . $row->id . '" class="form-label">Center Longitude</label>
                                    <input type="text" class="form-control center_long_input" id="center_long' . $row->id . '" name="center_long" value="' . htmlspecialchars($row->center_long) . '" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Peta</label>
                                    <div id="mapEdit' . $row->id . '" class="map-edit" style="height: 250px; width: 100%; border: 1px solid #ddd;" data-lat="' . htmlspecialchars($row->center_lat) . '" data-lng="' . htmlspecialchars($row->center_long) . '"></div>
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

                $deleteUrl = route('wilayah.destroy', $row->id);

                $warningMsg = $ruteCount > 0
                    ? 'Wilayah <strong>' . htmlspecialchars($row->wilayah) . '</strong> memiliki <strong>' . $ruteCount . ' rute</strong> yang akan ikut terhapus.'
                    : 'Yakin ingin menghapus wilayah <strong>' . htmlspecialchars($row->wilayah) . '</strong>?';

                $deleteModal = '<div class="modal fade" id="deleteModal' . $row->id . '" tabindex="-1" aria-labelledby="deleteModalLabel' . $row->id . '" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="' . $deleteUrl . '" method="POST">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel' . $row->id . '">Hapus Wilayah</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center py-3">
                                        <div class="mb-3" style="font-size:2.5rem;color:var(--color-danger);">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        ' . $warningMsg . '
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
            ->rawColumns(['rute_count', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'wilayah' => 'required|string|max:30',
                'ket' => 'nullable|string|max:100',
                'center_lat' => 'nullable|string|max:100',
                'center_long' => 'nullable|string|max:100',
            ]);

            Wilayah::create($request->all());
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error creating info: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $wilayah = Wilayah::findOrFail($id);
        return response()->json(['wilayah' => $wilayah->wilayah]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $wilayah = Wilayah::findOrFail($id);
        $html = view('dashboard.pages.datas.edit-wilayah-forms', compact('wilayah'))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'wilayah' => 'required|string|max:30',
                'ket' => 'nullable|string|max:100',
                'center_lat' => 'nullable|string|max:100',
                'center_long' => 'nullable|string|max:100',
            ]);

            $wilayah = Wilayah::findOrFail($id);
            $wilayah->update($request->all());
            return redirect()->back()->with('update', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error creating info: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $wilayah = Wilayah::findOrFail($id);
            $wilayah->delete();

            return redirect()->back()->with('delete', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error("Error deleting info ID $id: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }
}
