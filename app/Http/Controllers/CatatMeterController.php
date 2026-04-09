<?php

namespace App\Http\Controllers;

use App\Models\CatatMeter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CatatMeterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Catat Meter';
        return view('dashboard.pages.catat-meter.catat-meter')->with(compact('page'));
    }

    public function data(Request $request)
    {
        $catatMeter = CatatMeter::with(['Pelanggan', 'Petugas', 'KondisiMeter'])->select(['id', 'id_pelanggan', 'id_petugas', 'id_kondisi', 'waktu', 'stand', 'gps', 'status'])
            ->latest();

        return DataTables::of($catatMeter)
            ->addIndexColumn()
            ->addColumn('pelanggan', function ($row) {
                return $row->Pelanggan ? $row->Pelanggan->nama : '';
            })
            ->addColumn('petugas', function ($row) {
                return $row->Petugas ? $row->Petugas->nama : '';
            })
            ->addColumn('kondisi', function ($row) {
                return $row->KondisiMeter ? $row->KondisiMeter->nama_kondisi : '';
            })
            ->addColumn('waktu', function ($row) {
                return $row->waktu ? date('d/m/Y H:i', strtotime($row->waktu)) : '';
            })
            ->addColumn('action', function ($row) {
                $editBtn = '<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal' . $row->id . '"><i class="fas fa-pen-to-square"></i></button>';
                $deleteBtn = '<button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row->id . '"><i class="fas fa-trash"></i></button>';

                return $editBtn . ' ' . $deleteBtn;
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
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggans,id',
            'id_petugas' => 'required|exists:petugas,id',
            'id_kondisi' => 'required|exists:kondisi_meters,id',
            'waktu' => 'required|date',
            'stand' => 'required|integer',
            'gps' => 'nullable|string|max:25',
            'status' => 'required|integer|between:0,255',
        ]);

        CatatMeter::create($request->all());

        return redirect()->back()->with('success', 'Data catat meter berhasil disimpan.');
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
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggans,id',
            'id_petugas' => 'required|exists:petugas,id',
            'id_kondisi' => 'required|exists:kondisi_meters,id',
            'waktu' => 'required|date',
            'stand' => 'required|integer',
            'gps' => 'nullable|string|max:25',
            'status' => 'required|integer|between:0,255',
        ]);

        $catatMeter = CatatMeter::findOrFail($id);
        $catatMeter->update($request->all());

        return redirect()->back()->with('update', 'Data catat meter berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $catatMeter = CatatMeter::findOrFail($id);
        $catatMeter->delete();

        return redirect()->back()->with('delete', 'Data catat meter berhasil dihapus.');
    }
}
