<?php

namespace App\Http\Controllers;

use App\Models\CatatMeter;
use App\Models\KondisiMeter;
use App\Models\Pelanggan;
use App\Models\Petugas;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CatatMeterController extends Controller
{
    public function index()
    {
        $page = 'Catat Meter';
        $petugasList = Petugas::orderBy('nama')->get();
        $wilayahList = Wilayah::orderBy('wilayah')->get();
        $kondisiList = KondisiMeter::orderBy('kondisi')->get();
        return view('dashboard.pages.catat-meter.catat-meter', compact('page', 'petugasList', 'wilayahList', 'kondisiList'));
    }

    public function data(Request $request)
    {
        $catatMeter = CatatMeter::with(['Pelanggan.wilayah', 'Petugas', 'KondisiMeter', 'FotoCater'])
            ->select(['id', 'id_pelanggan', 'id_petugas', 'id_kondisi', 'waktu', 'stand', 'gps', 'status']);

        if ($request->filled('id_petugas')) {
            $catatMeter->where('id_petugas', $request->id_petugas);
        }
        if ($request->filled('id_kondisi')) {
            $catatMeter->where('id_kondisi', $request->id_kondisi);
        }
        if ($request->filled('id_wilayah')) {
            $catatMeter->whereHas('Pelanggan', function ($q) use ($request) {
                $q->where('id_wilayah', $request->id_wilayah);
            });
        }
        if ($request->filled('bulan')) {
            $catatMeter->whereMonth('waktu', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $catatMeter->whereYear('waktu', $request->tahun);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $catatMeter->where(function ($q) use ($search) {
                $q->whereHas('Pelanggan', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%$search%")
                        ->orWhere('no_kontrol', 'like', "%$search%")
                        ->orWhere('no_sambu', 'like', "%$search%")
                        ->orWhere('alamat', 'like', "%$search%");
                })->orWhere('stand', 'like', "%$search%");
            });
        }

        $catatMeter->latest();

        return DataTables::of($catatMeter)
            ->addIndexColumn()
            ->addColumn('foto', function ($row) {
                $foto = $row->FotoCater->first();
                $url = $foto && $foto->foto ? asset('storage/' . $foto->foto) : null;
                if ($url) {
                    return '<div class="photo-thumb" data-foto="' . $url . '" onclick="openPhotoModal(\'' . $url . '\', ' . $row->id . ', ' . $row->stand . ')">
                                <img src="' . $url . '" width="40" height="40" style="object-fit:cover;border-radius:8px;cursor:pointer;">
                            </div>';
                }
                return '<div class="photo-thumb no-photo" style="width:40px;height:40px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                            <i class="fas fa-camera text-muted" style="font-size:14px;"></i>
                        </div>';
            })
            ->addColumn('stand_meter', function ($row) {
                return $row->stand;
            })
            ->addColumn('waktu', function ($row) {
                return $row->waktu ? date('d/m/Y H:i', strtotime($row->waktu)) : '';
            })
            ->addColumn('nama', function ($row) {
                return $row->Pelanggan ? $row->Pelanggan->nama : '';
            })
            ->addColumn('no_kontrol', function ($row) {
                return $row->Pelanggan ? $row->Pelanggan->no_kontrol : '';
            })
            ->addColumn('no_sambung', function ($row) {
                return $row->Pelanggan ? $row->Pelanggan->no_sambu : '';
            })
            ->addColumn('wilayah', function ($row) {
                return $row->Pelanggan && $row->Pelanggan->wilayah ? $row->Pelanggan->wilayah->wilayah : '';
            })
            ->addColumn('kondisi_badge', function ($row) {
                $nama = $row->KondisiMeter ? $row->KondisiMeter->kondisi : '';
                $map = [
                    'METER BAIK' => ['bg' => '#dcfce7', 'text' => '#15803d'],
                    'METER BURAM' => ['bg' => '#f1f5f9', 'text' => '#475569'],
                    'METER HILANG' => ['bg' => '#fee2e2', 'text' => '#dc2626'],
                    'METER BARU' => ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                ];
                $style = 'background:#f1f5f9;color:#475569;';
                foreach ($map as $key => $c) {
                    if (strtoupper($nama) === $key) {
                        $style = 'background:' . $c['bg'] . ';color:' . $c['text'] . ';';
                        break;
                    }
                }
                return '<span class="badge-kondisi" style="' . $style . 'border-radius:20px;padding:4px 10px;font-size:11px;font-weight:700;display:inline-block;">' . e($nama) . '</span>';
            })
            ->addColumn('petugas', function ($row) {
                return $row->Petugas ? $row->Petugas->nama : '';
            })
            ->addColumn('action', function ($row) {
                return '<div style="display:flex;gap:4px;justify-content:center;">
                            <button class="btn-action btn-edit" data-id="' . $row->id . '" title="Edit"><i class="fas fa-pen-to-square"></i></button>
                            <button class="btn-action btn-delete" data-id="' . $row->id . '" title="Hapus"><i class="fas fa-trash"></i></button>
                        </div>';
            })
            ->rawColumns(['foto', 'kondisi_badge', 'action'])
            ->make(true);
    }

    public function dataTidakTerdaftar(Request $request)
    {
        $catatMeter = CatatMeter::with(['Pelanggan', 'Petugas', 'KondisiMeter', 'FotoCater'])
            ->select(['id', 'id_pelanggan', 'id_petugas', 'id_kondisi', 'waktu', 'stand', 'gps', 'status'])
            ->latest();

        return DataTables::of($catatMeter)
            ->addIndexColumn()
            ->addColumn('pelanggan', function ($row) {
                return $row->Pelanggan ? $row->Pelanggan->nama : '';
            })
            ->addColumn('no_kontrol', function ($row) {
                return $row->Pelanggan ? $row->Pelanggan->no_kontrol : '';
            })
            ->addColumn('no_sambung', function ($row) {
                return $row->Pelanggan ? $row->Pelanggan->no_sambu : '';
            })
            ->addColumn('wilayah', function ($row) {
                return $row->Pelanggan && $row->Pelanggan->wilayah ? $row->Pelanggan->wilayah->wilayah : '';
            })
            ->addColumn('kondisi', function ($row) {
                return $row->KondisiMeter ? $row->KondisiMeter->kondisi : '';
            })
            ->addColumn('petugas', function ($row) {
                return $row->Petugas ? $row->Petugas->nama : '';
            })
            ->addColumn('waktu', function ($row) {
                return $row->waktu ? date('d/m/Y H:i', strtotime($row->waktu)) : '';
            })
            ->addColumn('foto', function ($row) {
                $foto = $row->FotoCater->first();
                if ($foto && $foto->foto) {
                    return '<img src="' . asset('storage/' . $foto->foto) . '" width="50" height="50" style="object-fit:cover;border-radius:4px;">';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-warning btn-sm edit-btn" data-id="' . $row->id . '"><i class="fas fa-pen-to-square"></i></button> <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['foto', 'action'])
            ->make(true);
    }

    public function dataUrutan(Request $request)
    {
        $pelanggan = Pelanggan::with(['wilayah'])
            ->select(['id', 'nama', 'no_sambu', 'no_kontrol', 'alamat', 'id_wilayah'])
            ->orderBy('nama');

        return DataTables::of($pelanggan)
            ->addIndexColumn()
            ->addColumn('wilayah', function ($row) {
                return $row->wilayah ? $row->wilayah->wilayah : '';
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-primary btn-sm" onclick="window.location.href=\'/cater?pelanggan=' . $row->id . '\'"><i class="fas fa-eye"></i> Catat</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        //
    }

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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $catatMeter = CatatMeter::create($request->only(['id_pelanggan', 'id_petugas', 'id_kondisi', 'waktu', 'stand', 'gps', 'status']));

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('cater-foto', 'public');
            $catatMeter->FotoCater()->create(['foto' => $path]);
        }

        return response()->json(['success' => 'Data catat meter berhasil disimpan.']);
    }

    public function show(string $id)
    {
        $catatMeter = CatatMeter::with(['Pelanggan.wilayah', 'Petugas', 'KondisiMeter', 'FotoCater'])->findOrFail($id);
        $data = $catatMeter->toArray();
        $pel = $catatMeter->Pelanggan;
        $foto = $catatMeter->FotoCater->first();
        $fotoRumah = $pel && $pel->FotoPelanggan ? $pel->FotoPelanggan->first() : null;
        $data['nama'] = $pel ? $pel->nama : '';
        $data['alamat'] = $pel ? $pel->alamat : '';
        $data['telepon'] = $pel ? $pel->telepon : '';
        $data['no_sambu'] = $pel ? $pel->no_sambu : '';
        $data['no_kontrol'] = $pel ? $pel->no_kontrol : '';
        $data['wilayah'] = $pel && $pel->wilayah ? $pel->wilayah->wilayah : '';
        $data['kondisi_nama'] = $catatMeter->KondisiMeter ? $catatMeter->KondisiMeter->kondisi : '';
        $data['petugas_nama'] = $catatMeter->Petugas ? $catatMeter->Petugas->nama : '';
        $data['foto_url'] = $foto ? asset('storage/' . $foto->foto) : null;
        $data['foto_rumah_url'] = $fotoRumah ? asset('storage/' . $fotoRumah->foto) : null;
        return response()->json($data);
    }

    public function edit(string $id)
    {
        //
    }

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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $catatMeter = CatatMeter::findOrFail($id);
        $catatMeter->update($request->only(['id_pelanggan', 'id_petugas', 'id_kondisi', 'waktu', 'stand', 'gps', 'status']));

        if ($request->hasFile('foto')) {
            $oldFoto = $catatMeter->FotoCater->first();
            if ($oldFoto) {
                Storage::disk('public')->delete($oldFoto->foto);
                $oldFoto->delete();
            }
            $path = $request->file('foto')->store('cater-foto', 'public');
            $catatMeter->FotoCater()->create(['foto' => $path]);
        }

        return response()->json(['success' => 'Data catat meter berhasil diperbarui.']);
    }

    public function destroy(string $id)
    {
        $catatMeter = CatatMeter::findOrFail($id);

        foreach ($catatMeter->FotoCater as $foto) {
            Storage::disk('public')->delete($foto->foto);
            $foto->delete();
        }

        $catatMeter->delete();

        return response()->json(['success' => 'Data catat meter berhasil dihapus.']);
    }

    public function updateStand(Request $request, string $id)
    {
        $request->validate(['stand' => 'required|integer']);
        $catatMeter = CatatMeter::findOrFail($id);
        $catatMeter->update(['stand' => $request->stand]);
        return response()->json(['success' => 'Stand meter berhasil diperbarui.']);
    }

    public function excel(Request $request)
    {
        $catatMeter = CatatMeter::with(['Pelanggan.wilayah', 'Petugas', 'KondisiMeter'])
            ->select(['id', 'id_pelanggan', 'id_petugas', 'id_kondisi', 'waktu', 'stand', 'gps', 'status']);

        if ($request->filled('id_petugas')) {
            $catatMeter->where('id_petugas', $request->id_petugas);
        }
        if ($request->filled('id_kondisi')) {
            $catatMeter->where('id_kondisi', $request->id_kondisi);
        }
        if ($request->filled('id_wilayah')) {
            $catatMeter->whereHas('Pelanggan', function ($q) use ($request) {
                $q->where('id_wilayah', $request->id_wilayah);
            });
        }
        if ($request->filled('bulan')) {
            $catatMeter->whereMonth('waktu', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $catatMeter->whereYear('waktu', $request->tahun);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $catatMeter->where(function ($q) use ($search) {
                $q->whereHas('Pelanggan', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%$search%")
                        ->orWhere('no_kontrol', 'like', "%$search%")
                        ->orWhere('no_sambu', 'like', "%$search%")
                        ->orWhere('alamat', 'like', "%$search%");
                })->orWhere('stand', 'like', "%$search%");
            });
        }

        $catatMeter->latest();
        $data = $catatMeter->get();

        $filename = 'catat-meter-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Nama', 'No Kontrol', 'No Sambung', 'Wilayah', 'Stand Meter', 'Waktu', 'Kondisi Meter', 'Petugas', 'GPS', 'Status']);

            $no = 1;
            foreach ($data as $row) {
                $pel = $row->Pelanggan;
                fputcsv($handle, [
                    $no++,
                    $pel ? $pel->nama : '',
                    $pel ? $pel->no_kontrol : '',
                    $pel ? $pel->no_sambu : '',
                    $pel && $pel->wilayah ? $pel->wilayah->wilayah : '',
                    $row->stand,
                    $row->waktu ? date('d/m/Y H:i', strtotime($row->waktu)) : '',
                    $row->KondisiMeter ? $row->KondisiMeter->kondisi : '',
                    $row->Petugas ? $row->Petugas->nama : '',
                    $row->gps,
                    $row->status == 1 ? 'Aktif' : 'Tidak Aktif',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
