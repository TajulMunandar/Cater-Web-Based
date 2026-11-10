<?php

namespace App\Http\Controllers;

use App\Models\CatatMeter;
use App\Models\KondisiMeter;
use App\Models\Pelanggan;
use App\Models\PelangganDetail;
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
        // Admin melihat SEMUA data (termasuk yang ditolak) agar bisa diverifikasi ulang.
        $catatMeter = CatatMeter::withoutGlobalScopes()
            ->with(['Pelanggan.rute.wilayah', 'Petugas', 'KondisiMeter', 'FotoCater'])
            ->select(['id', 'id_pelanggan', 'id_petugas', 'id_kondisi', 'waktu', 'stand', 'gps', 'status', 'status_verifikasi', 'alasan_tolak']);

        if ($request->filled('status_verifikasi')) {
            $catatMeter->where('status_verifikasi', $request->status_verifikasi);
        }
        if ($request->filled('id_petugas')) {
            $catatMeter->where('id_petugas', $request->id_petugas);
        }
        if ($request->filled('id_kondisi')) {
            $catatMeter->where('id_kondisi', $request->id_kondisi);
        }
        if ($request->filled('id_wilayah')) {
            $catatMeter->whereHas('Pelanggan.rute', function ($q) use ($request) {
                $q->where('id_wilayah', $request->id_wilayah);
            });
        }
        if ($request->filled('bulan')) {
            $catatMeter->whereMonth('waktu', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $catatMeter->whereYear('waktu', $request->tahun);
        }
        $search = $request->input('search.value');
        if ($search) {
            $catatMeter->where(function ($q) use ($search) {
                $q->whereHas('Pelanggan', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%$search%")
                        ->orWhere('alamat', 'like', "%$search%");
                })->orWhere('stand', 'like', "%$search%");
            });
        }

        $catatMeter = $catatMeter->latest();

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
            ->addColumn('wilayah', function ($row) {
                return $row->Pelanggan && $row->Pelanggan->rute && $row->Pelanggan->rute->wilayah ? $row->Pelanggan->rute->wilayah->wilayah : '';
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
            ->addColumn('verifikasi_badge', function ($row) {
                $map = [
                    CatatMeter::VERIF_PENDING => ['Menunggu', '#fef3c7', '#b45309'],
                    CatatMeter::VERIF_APPROVED => ['Disetujui', '#dcfce7', '#15803d'],
                    CatatMeter::VERIF_DECLINED => ['Ditolak', '#fee2e2', '#dc2626'],
                ];
                [$label, $bg, $text] = $map[$row->status_verifikasi] ?? ['-', '#f1f5f9', '#475569'];
                $html = '<span style="background:' . $bg . ';color:' . $text . ';border-radius:20px;padding:4px 10px;font-size:11px;font-weight:700;display:inline-block;">' . $label . '</span>';
                if ($row->status_verifikasi === CatatMeter::VERIF_DECLINED && $row->alasan_tolak) {
                    $html .= '<div style="font-size:11px;color:#64748b;margin-top:4px;max-width:180px;white-space:normal;">' . e($row->alasan_tolak) . '</div>';
                }
                return $html;
            })
            ->addColumn('action', function ($row) {
                $verif = '';
                if ($row->status_verifikasi !== CatatMeter::VERIF_APPROVED) {
                    $verif .= '<button class="btn-action btn-approve" data-id="' . $row->id . '" title="Setujui"><i class="fas fa-check"></i></button>';
                }
                if ($row->status_verifikasi !== CatatMeter::VERIF_DECLINED) {
                    $verif .= '<button class="btn-action btn-decline" data-id="' . $row->id . '" title="Tolak"><i class="fas fa-xmark"></i></button>';
                }
                return '<div style="display:flex;gap:4px;justify-content:center;">'
                    . $verif .
                    '<button class="btn-action btn-edit" data-id="' . $row->id . '" title="Edit"><i class="fas fa-pen-to-square"></i></button>
                            <button class="btn-action btn-delete" data-id="' . $row->id . '" title="Hapus"><i class="fas fa-trash"></i></button>
                        </div>';
            })
            ->rawColumns(['foto', 'kondisi_badge', 'verifikasi_badge', 'action'])
            ->make(true);
    }

    /**
     * Setujui data catat meter.
     */
    public function approve(string $id)
    {
        $catatMeter = CatatMeter::withoutGlobalScopes()->findOrFail($id);

        $catatMeter->update([
            'status_verifikasi' => CatatMeter::VERIF_APPROVED,
            'alasan_tolak' => null,
            'verifikasi_oleh' => auth()->id(),
            'verifikasi_at' => now(),
            'notif_dibaca_at' => null,
        ]);

        return response()->json(['success' => 'Data catat meter disetujui.']);
    }

    /**
     * Tolak data catat meter. Alasan wajib diisi dan akan dikirim ke petugas
     * lewat API; pelanggan kembali berstatus "belum tercatat" di aplikasi mobile.
     */
    public function decline(Request $request, string $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|min:3|max:500',
        ], [
            'alasan_tolak.required' => 'Alasan penolakan wajib diisi.',
            'alasan_tolak.min' => 'Alasan penolakan terlalu pendek.',
        ]);

        $catatMeter = CatatMeter::withoutGlobalScopes()->findOrFail($id);

        $catatMeter->update([
            'status_verifikasi' => CatatMeter::VERIF_DECLINED,
            'alasan_tolak' => trim($request->alasan_tolak),
            'verifikasi_oleh' => auth()->id(),
            'verifikasi_at' => now(),
            'notif_dibaca_at' => null, // memicu notifikasi baru di mobile
        ]);

        // Kembalikan "stand terakhir" ke data valid sebelumnya (bila ada),
        // supaya stand yang ditolak tidak jadi acuan pencatatan berikutnya.
        $sebelumnya = CatatMeter::where('id_pelanggan', $catatMeter->id_pelanggan)
            ->where('id_petugas', $catatMeter->id_petugas)
            ->where('id', '!=', $catatMeter->id)
            ->where('waktu', '<', $catatMeter->waktu)
            ->orderByDesc('waktu')
            ->first();

        if ($sebelumnya) {
            PelangganDetail::where('id_pelanggan', $catatMeter->id_pelanggan)
                ->where('id_petugas', $catatMeter->id_petugas)
                ->update(['stand_terakhir' => $sebelumnya->stand]);
        }

        return response()->json(['success' => 'Data catat meter ditolak dan dikembalikan ke petugas.']);
    }

    public function dataTidakTerdaftar(Request $request)
    {
        $catatMeter = CatatMeter::with(['Pelanggan.rute.wilayah', 'Petugas', 'KondisiMeter', 'FotoCater'])
            ->select(['id', 'id_pelanggan', 'id_petugas', 'id_kondisi', 'waktu', 'stand', 'gps', 'status'])
            ->latest();

        return DataTables::of($catatMeter)
            ->addIndexColumn()
            ->addColumn('pelanggan', function ($row) {
                return $row->Pelanggan ? $row->Pelanggan->nama : '';
            })
            ->addColumn('wilayah', function ($row) {
                return $row->Pelanggan && $row->Pelanggan->rute && $row->Pelanggan->rute->wilayah ? $row->Pelanggan->rute->wilayah->wilayah : '';
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
        $pelanggan = Pelanggan::with(['rute.wilayah'])
            ->select(['id', 'nama', 'alamat', 'id_rute'])
            ->orderBy('nama');

        return DataTables::of($pelanggan)
            ->addIndexColumn()
            ->addColumn('wilayah', function ($row) {
                return $row->rute && $row->rute->wilayah ? $row->rute->wilayah->wilayah : '';
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
        $catatMeter = CatatMeter::withoutGlobalScopes()->with(['Pelanggan.rute.wilayah', 'Petugas', 'KondisiMeter', 'FotoCater'])->findOrFail($id);
        $data = $catatMeter->toArray();
        $pel = $catatMeter->Pelanggan;
        $foto = $catatMeter->FotoCater->first();
        $fotoRumah = $pel && $pel->FotoPelanggan ? $pel->FotoPelanggan->first() : null;
        $data['pelanggan'] = $pel ? ['id' => $pel->id, 'nama' => $pel->nama] : null;
        $data['nama'] = $pel ? $pel->nama : '';
        $data['alamat'] = $pel ? $pel->alamat : '';
        $data['telepon'] = $pel ? $pel->telepon : '';
        $data['wilayah'] = $pel && $pel->rute && $pel->rute->wilayah ? $pel->rute->wilayah->wilayah : '';
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

        $catatMeter = CatatMeter::withoutGlobalScopes()->findOrFail($id);
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
        $catatMeter = CatatMeter::withoutGlobalScopes()->findOrFail($id);

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
        $catatMeter = CatatMeter::withoutGlobalScopes()->findOrFail($id);
        $catatMeter->update(['stand' => $request->stand]);
        return response()->json(['success' => 'Stand meter berhasil diperbarui.']);
    }

    public function excel(Request $request)
    {
        $catatMeter = CatatMeter::withoutGlobalScopes()
            ->with(['Pelanggan.rute.wilayah', 'Petugas', 'KondisiMeter'])
            ->select(['id', 'id_pelanggan', 'id_petugas', 'id_kondisi', 'waktu', 'stand', 'gps', 'status', 'status_verifikasi', 'alasan_tolak']);

        if ($request->filled('status_verifikasi')) {
            $catatMeter->where('status_verifikasi', $request->status_verifikasi);
        }
        if ($request->filled('id_petugas')) {
            $catatMeter->where('id_petugas', $request->id_petugas);
        }
        if ($request->filled('id_kondisi')) {
            $catatMeter->where('id_kondisi', $request->id_kondisi);
        }
        if ($request->filled('id_wilayah')) {
            $catatMeter->whereHas('Pelanggan.rute', function ($q) use ($request) {
                $q->where('id_wilayah', $request->id_wilayah);
            });
        }
        if ($request->filled('bulan')) {
            $catatMeter->whereMonth('waktu', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $catatMeter->whereYear('waktu', $request->tahun);
        }
        $search = $request->input('search.value');
        if ($search) {
            $catatMeter->where(function ($q) use ($search) {
                $q->whereHas('Pelanggan', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%$search%")
                        ->orWhere('alamat', 'like', "%$search%");
                })->orWhere('stand', 'like', "%$search%");
            });
        }

        $catatMeter = $catatMeter->latest();
        $data = $catatMeter->get();

        $filename = 'catat-meter-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Nama', 'Wilayah', 'Stand Meter', 'Waktu', 'Kondisi Meter', 'Petugas', 'GPS', 'Status', 'Verifikasi', 'Alasan Tolak']);

            $no = 1;
            foreach ($data as $row) {
                $pel = $row->Pelanggan;
                fputcsv($handle, [
                    $no++,
                    $pel ? $pel->nama : '',
                    $pel && $pel->rute && $pel->rute->wilayah ? $pel->rute->wilayah->wilayah : '',
                    $row->stand,
                    $row->waktu ? date('d/m/Y H:i', strtotime($row->waktu)) : '',
                    $row->KondisiMeter ? $row->KondisiMeter->kondisi : '',
                    $row->Petugas ? $row->Petugas->nama : '',
                    $row->gps,
                    $row->status == 1 ? 'Aktif' : 'Tidak Aktif',
                    ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'declined' => 'Ditolak'][$row->status_verifikasi] ?? '',
                    $row->status_verifikasi === 'declined' ? $row->alasan_tolak : '',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
