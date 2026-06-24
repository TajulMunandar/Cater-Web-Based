<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatatMeter;
use App\Models\Pelanggan;
use App\Models\PelangganDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function belumDicatat(Request $request): JsonResponse
    {
        $petugas = $request->user()->petugas;

        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $pelangganIds = PelangganDetail::where('id_petugas', $petugas->id)
            ->pluck('id_pelanggan');

        $sudahDicatat = DB::table('catat_meters')
            ->whereIn('id_pelanggan', $pelangganIds)
            ->whereYear('waktu', $tahun)
            ->whereMonth('waktu', $bulan)
            ->pluck('id_pelanggan');

        $pelanggans = Pelanggan::with(['rute.wilayah', 'golongan', 'PelangganDetail' => function ($q) use ($petugas) {
                $q->where('id_petugas', $petugas->id);
            }])
            ->whereIn('id', $pelangganIds)
            ->whereNotIn('id', $sudahDicatat)
            ->where('status', Pelanggan::STATUS_AKTIF)
            ->orderBy(
                PelangganDetail::select('urutan')
                    ->whereColumn('id_pelanggan', 'pelanggans.id')
                    ->where('id_petugas', $petugas->id)
                    ->orderBy('urutan')
                    ->limit(1)
            )
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'total' => $pelanggans->count(),
                'pelanggans' => $pelanggans->map(function ($p) {
                    $detail = $p->PelangganDetail->first();
                    return [
                        'id' => $p->id,
                        'no_sambu' => $p->no_sambu,
                        'nama' => $p->nama,
                        'alamat' => $p->alamat,
                        'telepon' => $p->telepon,
                        'lat' => (float) $p->lat,
                        'long' => (float) $p->long,
                        'urutan' => $detail ? $detail->urutan : 0,
                        'stand_terakhir' => $detail ? $detail->stand_terakhir : 0,
                        'rute' => $p->rute ? [
                            'rute' => $p->rute->rute,
                            'kode' => $p->rute->kode,
                        ] : null,
                        'wilayah' => $p->rute && $p->rute->wilayah ? $p->rute->wilayah->wilayah : null,
                        'golongan' => $p->golongan ? [
                            'nama' => $p->golongan->nama,
                            'tarif_per_m3' => (float) $p->golongan->tarif_per_m3,
                            'biaya_admin' => (float) $p->golongan->biaya_admin,
                        ] : null,
                    ];
                }),
            ],
        ]);
    }

    public function semua(Request $request): JsonResponse
    {
        $petugas = $request->user()->petugas;

        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $pelangganIds = PelangganDetail::where('id_petugas', $petugas->id)
            ->pluck('id_pelanggan');

        $catatMeters = CatatMeter::whereIn('id_pelanggan', $pelangganIds)
            ->whereYear('waktu', $tahun)
            ->whereMonth('waktu', $bulan)
            ->get()
            ->keyBy('id_pelanggan');

        $pelanggans = Pelanggan::with(['rute.wilayah', 'golongan', 'PelangganDetail' => function ($q) use ($petugas) {
                $q->where('id_petugas', $petugas->id);
            }])
            ->whereIn('id', $pelangganIds)
            ->where('status', Pelanggan::STATUS_AKTIF)
            ->orderBy(
                PelangganDetail::select('urutan')
                    ->whereColumn('id_pelanggan', 'pelanggans.id')
                    ->where('id_petugas', $petugas->id)
                    ->orderBy('urutan')
                    ->limit(1)
            )
            ->get();

        $total = $pelanggans->count();
        $totalTercatat = $catatMeters->count();

        return response()->json([
            'success' => true,
            'data' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'total' => $total,
                'total_tercatat' => $totalTercatat,
                'total_belum' => $total - $totalTercatat,
                'pelanggans' => $pelanggans->map(function ($p) use ($catatMeters) {
                    $detail = $p->PelangganDetail->first();
                    $catatMeter = $catatMeters->get($p->id);
                    return [
                        'id' => $p->id,
                        'no_sambu' => $p->no_sambu,
                        'nama' => $p->nama,
                        'alamat' => $p->alamat,
                        'telepon' => $p->telepon,
                        'lat' => (float) $p->lat,
                        'long' => (float) $p->long,
                        'urutan' => $detail ? $detail->urutan : 0,
                        'stand_terakhir' => $detail ? $detail->stand_terakhir : 0,
                        'is_recorded' => $catatMeter !== null,
                        'stand_baru' => $catatMeter ? (int) $catatMeter->stand : null,
                        'rute' => $p->rute ? [
                            'rute' => $p->rute->rute,
                            'kode' => $p->rute->kode,
                        ] : null,
                        'wilayah' => $p->rute && $p->rute->wilayah ? $p->rute->wilayah->wilayah : null,
                        'golongan' => $p->golongan ? [
                            'nama' => $p->golongan->nama,
                            'tarif_per_m3' => (float) $p->golongan->tarif_per_m3,
                            'biaya_admin' => (float) $p->golongan->biaya_admin,
                        ] : null,
                    ];
                }),
            ],
        ]);
    }
}
