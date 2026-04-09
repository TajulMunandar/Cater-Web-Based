<?php

namespace App\Http\Controllers;

use App\Models\CatatMeter;
use App\Models\KondisiMeter;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RekapController extends Controller
{
    public function index()
    {
        $page = 'Rekap Catat Meter';
        return view('dashboard.pages.data-rekap.rekap-catat-meter')->with(compact('page'));
    }

    public function kondisi()
    {
        $page = 'Rekap Kondisi';
        return view('dashboard.pages.data-rekap.rekap-kondisi')->with(compact('page'));
    }

    public function wilayah()
    {
        $page = 'Rekap Wilayah';
        return view('dashboard.pages.data-rekap.rekap-wilayah')->with(compact('page'));
    }

    public function dataCatatMeter(Request $request)
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
                return '<button class="btn btn-sm btn-warning edit-btn" data-id="' . $row->id . '">Edit</button> <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataKondisi(Request $request)
    {
        $kondisi = KondisiMeter::select(['id', 'kondisi', 'created_at'])
            ->latest();

        return DataTables::of($kondisi)
            ->addIndexColumn()
            ->addColumn('nama_kondisi', function ($row) {
                return $row->kondisi;
            })
            ->make(true);
    }

    public function dataWilayah(Request $request)
    {
        $wilayah = Wilayah::select(['id', 'wilayah', 'created_at'])
            ->latest();

        return DataTables::of($wilayah)
            ->addIndexColumn()
            ->addColumn('nama_wilayah', function ($row) {
                return $row->wilayah;
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required',
            'id_petugas' => 'required',
            'id_kondisi' => 'required',
            'waktu' => 'required',
            'stand' => 'required',
            'gps' => 'required',
            'status' => 'required',
        ]);

        CatatMeter::create($request->all());

        return response()->json(['success' => 'Data added successfully.']);
    }

    public function show($id)
    {
        $catatMeter = CatatMeter::find($id);
        return response()->json($catatMeter);
    }

    public function update(Request $request, $id)
    {
        $catatMeter = CatatMeter::find($id);
        $catatMeter->update($request->all());
        return response()->json(['success' => 'Data updated successfully.']);
    }

    public function destroy($id)
    {
        CatatMeter::find($id)->delete();
        return response()->json(['success' => 'Data deleted successfully.']);
    }
}
