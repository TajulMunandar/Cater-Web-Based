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
        return view('dashboard.pages.settings.wilayah')->with(compact('page'));
    }

    public function data(Request $request)
    {
        $wilayahs = Wilayah::select(['id', 'kode', 'wilayah', 'cabang', 'center_lat', 'center_long', 'ket'])
            ->latest();

        return DataTables::of($wilayahs)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editBtn = '<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal' . $row->id . '"><i
                                            class="fas fa-pen-to-square"></i></button>';
                $deleteBtn = '<button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row->id . '"><i
                                            class="fas fa-trash"></i></button>';
                // Generate modal HTML
                $editModal = '<div class="modal fade" id="editModal' . $row->id . '" tabindex="-1" aria-labelledby="editModalLabel' . $row->id . '" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="' . route('wilayah.update', $row->id) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('PUT') . '
                            <div class="modal-header bg-dark">
                                <h5 class="modal-title text-white" id="editModalLabel' . $row->id . '">Edit Wilayah</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="wilayah' . $row->id . '" class="form-label">Nama Wilayah</label>
                                    <input type="text" class="form-control" id="wilayah' . $row->id . '" name="wilayah" value="' . htmlspecialchars($row->wilayah) . '">
                                </div>
                                <div class="mb-3">
                                    <label for="kode' . $row->id . '" class="form-label">Kode Wilayah</label>
                                    <input type="text" class="form-control" id="kode' . $row->id . '" name="kode" value="' . htmlspecialchars($row->kode) . '">
                                </div>
                                <div class="mb-3">
                                    <label for="ket' . $row->id . '" class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="ket' . $row->id . '" name="ket" value="' . htmlspecialchars($row->ket) . '">
                                </div>
                                <div class="mb-3">
                                    <label for="cabang' . $row->id . '" class="form-label">Cabang</label>
                                    <input type="text" class="form-control" id="cabang' . $row->id . '" name="cabang" value="' . htmlspecialchars($row->cabang) . '">
                                </div>
                                <div class="mb-3">
                                    <label for="center_lat' . $row->id . '" class="form-label">Center Latitude</label>
                                    <input type="text" class="form-control" id="center_lat' . $row->id . '" name="center_lat" value="' . htmlspecialchars($row->center_lat) . '">
                                </div>
                                <div class="mb-3">
                                    <label for="center_long' . $row->id . '" class="form-label">Center Longitude</label>
                                    <input type="text" class="form-control" id="center_long' . $row->id . '" name="center_long" value="' . htmlspecialchars($row->center_long) . '">
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

                $deleteUrl = route('wilayah.destroy', $row->id);

                $deleteModal = '<div class="modal fade" id="deleteModal' . $row->id . '" tabindex="-1" aria-labelledby="deleteModalLabel' . $row->id . '" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="' . $deleteUrl . '" method="POST">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <div class="modal-header bg-dark">
                                    <h5 class="modal-title text-white" id="deleteModalLabel' . $row->id . '">Delete Wilayah</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete wilayah <strong>' . $row->wilayah . '</strong>?
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
                'kode' => 'required|string|max:20',
                'ket' => 'required|string|max:100',
                'cabang' => 'required|string|max:100',
                'center_lat' => 'required|string|max:100',
                'center_long' => 'required|string|max:100',
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
                'ket' => 'required|string|max:100',
                'cabang' => 'required|string|max:100',
                'center_lat' => 'required|string|max:100',
                'center_long' => 'required|string|max:100',
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
