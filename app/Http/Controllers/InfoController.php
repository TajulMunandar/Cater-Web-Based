<?php

namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Info';
        $petugas = Petugas::select('id', 'nama')->get();
        $infos = Info::with('petugas')->latest()->get();
        return view('dashboard.pages.info', compact('infos', 'petugas', 'page'));
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
                'waktu' => 'required|date',
                'gps' => 'required|string|max:25',
                'info' => 'required|string|max:250',
                'alamat' => 'required|string|max:100',
                'jenis' => 'required|string|max:10',
                'id_petugas' => 'required',
            ]);

            Info::create($request->all());
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error creating info: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'waktu' => 'sometimes|date',
                'gps' => 'sometimes|string|max:25',
                'info' => 'sometimes|string|max:250',
                'alamat' => 'sometimes|string|max:100',
                'jenis' => 'sometimes|string|max:10',
                'id_petugas' => 'sometimes|exists:petugas,id',
            ]);

            $info = Info::findOrFail($id);
            $info->update($request->all());

            return redirect()->back()->with('update', 'Data berhasil diupdate');
        } catch (\Exception $e) {
            Log::error("Error updating info ID $id: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mempebarui data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $info = Info::findOrFail($id);
            $info->delete();

            return redirect()->back()->with('delete', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error("Error deleting info ID $id: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }
}
