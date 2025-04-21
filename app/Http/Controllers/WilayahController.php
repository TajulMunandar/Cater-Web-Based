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
        $wilayahs = Wilayah::latest()->get();
        return view('dashboard.pages.settings.wilayah', compact('wilayahs'));
    }

    public function data(Request $request)
    {
        $wilayahs = Wilayah::select(['id', 'kode', 'wilayah', 'cabang']);

        return DataTables::of($wilayahs)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editBtn = '<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal' . $row->id . '">Edit</button>';
                $deleteBtn = '<button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row->id . '">Delete</button>';
                // Generate modal HTML
                $editModal = '<div class="modal fade" id="editModal' . $row->id . '" tabindex="-1" aria-labelledby="editModalLabel' . $row->id . '" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel' . $row->id . '">Edit Wilayah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Your form for editing wilayah -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>';

                $deleteModal = '<div class="modal fade" id="deleteModal' . $row->id . '" tabindex="-1" aria-labelledby="deleteModalLabel' . $row->id . '" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel' . $row->id . '">Delete Wilayah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this wilayah?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger">Delete</button>
                    </div>
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
            return redirect()->back()->with('success', 'Data berhasil diperbarui');
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

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error("Error deleting info ID $id: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }
}
