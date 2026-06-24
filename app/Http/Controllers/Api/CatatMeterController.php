<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatatMeter;
use App\Models\KondisiMeter;
use App\Models\Pelanggan;
use App\Models\PelangganDetail;
use App\Models\FotoCater;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatatMeterController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $petugas = $request->user()->petugas;

        $recordings = CatatMeter::where('id_petugas', $petugas->id)
            ->with(['pelanggan:id,nama,no_sambu', 'kondisiMeter:id,kondisi', 'fotoCater'])
            ->orderBy('waktu', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'id_pelanggan' => $item->id_pelanggan,
                    'customer_name' => $item->pelanggan->nama ?? '',
                    'customer_no' => $item->pelanggan->no_sambu ?? '',
                    'stand' => $item->stand,
                    'waktu' => $item->waktu,
                    'gps' => $item->gps,
                    'id_kondisi' => $item->id_kondisi,
                    'kondisi' => $item->kondisiMeter->kondisi ?? '',
                    'foto' => $item->fotoCater->first()?->foto,
                    'created_at' => $item->created_at->toIso8601String(),
                ];
            });

        return response()->json(['data' => $recordings]);
    }
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggans,id',
            'stand' => 'required|integer|min:0',
            'waktu' => 'required|date',
            'gps' => 'nullable|string|max:25',
            'id_kondisi' => 'required|exists:kondisi_meters,id',
            'foto' => 'nullable|image|max:5120',
        ]);

        $petugas = $request->user()->petugas;
        $pelanggan = Pelanggan::findOrFail($request->id_pelanggan);

        // Verify this pelanggan is assigned to this petugas
        $assigned = PelangganDetail::where('id_pelanggan', $pelanggan->id)
            ->where('id_petugas', $petugas->id)
            ->exists();

        if (!$assigned) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan ini tidak terdaftar untuk petugas Anda.',
            ], 403);
        }

        // Check if already recorded this month
        $alreadyRecorded = CatatMeter::where('id_pelanggan', $pelanggan->id)
            ->whereYear('waktu', now()->year)
            ->whereMonth('waktu', now()->month)
            ->exists();

        if ($alreadyRecorded) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan ini sudah dicatat bulan ini.',
            ], 409);
        }

        $catatMeter = CatatMeter::create([
            'id_pelanggan' => $pelanggan->id,
            'id_petugas' => $petugas->id,
            'id_kondisi' => $request->id_kondisi,
            'waktu' => $request->waktu,
            'stand' => $request->stand,
            'gps' => $request->gps ?? '',
            'status' => 1,
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('cater-foto', 'public');
            FotoCater::create([
                'id_cater' => $catatMeter->id,
                'foto' => $path,
            ]);
        }

        // Update stand terakhir di pelanggan_detail
        PelangganDetail::where('id_pelanggan', $pelanggan->id)
            ->where('id_petugas', $petugas->id)
            ->update(['stand_terakhir' => $request->stand]);

        return response()->json([
            'success' => true,
            'message' => 'Catat meter berhasil disimpan.',
            'data' => [
                'id' => $catatMeter->id,
                'id_pelanggan' => $catatMeter->id_pelanggan,
                'stand' => $catatMeter->stand,
                'waktu' => $catatMeter->waktu,
            ],
        ], 201);
    }

    public function kondisi(): JsonResponse
    {
        $kondisi = KondisiMeter::orderBy('kondisi')->get(['id', 'kondisi', 'keterangan']);

        return response()->json([
            'success' => true,
            'data' => $kondisi,
        ]);
    }
}
