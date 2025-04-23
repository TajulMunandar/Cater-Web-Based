<?php

namespace App\Http\Controllers;

use App\Models\KondisiMeter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KondisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Kondisi Meter';
        $kondisis = KondisiMeter::select(['id', 'kondisi', 'keterangan', 'kode'])->latest()->get();
        return view('dashboard.pages.settings.kondisi')->with(compact('kondisis', 'page'));
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
                'kondisi' => 'required|string|max:20',
                'keterangan' => 'required|string|max:50',
                'kode' => 'required|string|max:20',
            ]);
            KondisiMeter::create([
                'kondisi' => $request->kondisi,
                'keterangan' => $request->keterangan,
                'kode' => $request->kode,
            ]);

            return redirect()->back()->with('success', 'Data kondisi meter berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan kondisi meter: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
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
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'kondisi' => 'required|string|max:20',
                'keterangan' => 'required|string|max:50',
                'kode' => 'required|string|max:20',
            ]);
            $kondisiMeter = KondisiMeter::findOrFail($id);

            $kondisiMeter->update([
                'kondisi' => $request->kondisi,
                'keterangan' => $request->keterangan,
                'kode' => $request->kode,
            ]);

            return redirect()->back()->with('update', 'Data kondisi meter berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui kondisi meter: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $kondisiMeter = KondisiMeter::findOrFail($id);
            $kondisiMeter->delete();

            return redirect()->back()->with('delete', 'Data kondisi meter berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus kondisi meter: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
