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
    /**
     * Riwayat pencatatan petugas (semua status: pending, approved, declined).
     */
    public function index(Request $request): JsonResponse
    {
        $petugas = $request->user()->petugas;

        $recordings = CatatMeter::withoutGlobalScopes()
            ->where('id_petugas', $petugas->id)
            ->with(['pelanggan:id,nama,no_sambu', 'kondisiMeter:id,kondisi', 'fotoCater'])
            ->orderBy('waktu', 'desc')
            ->get()
            ->map(fn ($item) => $this->transform($item));

        return response()->json(['data' => $recordings]);
    }

    /**
     * Daftar pencatatan yang DITOLAK admin beserta alasannya.
     * `unread` = jumlah penolakan yang belum dibuka petugas (untuk badge notifikasi).
     */
    public function ditolak(Request $request): JsonResponse
    {
        $petugas = $request->user()->petugas;

        $items = CatatMeter::withoutGlobalScopes()
            ->where('id_petugas', $petugas->id)
            ->where('status_verifikasi', CatatMeter::VERIF_DECLINED)
            ->with(['pelanggan:id,nama,no_sambu,alamat', 'kondisiMeter:id,kondisi', 'fotoCater'])
            ->orderByDesc('verifikasi_at')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $items->count(),
                'unread' => $items->whereNull('notif_dibaca_at')->count(),
                'items' => $items->map(fn ($item) => $this->transform($item))->values(),
            ],
        ]);
    }

    /**
     * Tandai semua penolakan sebagai sudah dibaca (dipanggil saat halaman penolakan dibuka).
     */
    public function ditolakDibaca(Request $request): JsonResponse
    {
        $petugas = $request->user()->petugas;

        CatatMeter::withoutGlobalScopes()
            ->where('id_petugas', $petugas->id)
            ->where('status_verifikasi', CatatMeter::VERIF_DECLINED)
            ->whereNull('notif_dibaca_at')
            ->update(['notif_dibaca_at' => now()]);

        return response()->json(['success' => true]);
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

        // Pencatatan bulan ini yang belum ditolak = sudah dicatat.
        // (Data ditolak sengaja tidak dihitung agar petugas bisa mencatat ulang.)
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

        // Pencatatan ulang atas data yang ditolak: perbarui record yang sama
        // (kembali ke "pending"), alasan penolakan lama tetap tersimpan sebagai
        // konteks bagi admin sampai disetujui.
        $declined = CatatMeter::withoutGlobalScopes()
            ->where('id_pelanggan', $pelanggan->id)
            ->where('id_petugas', $petugas->id)
            ->where('status_verifikasi', CatatMeter::VERIF_DECLINED)
            ->whereYear('waktu', now()->year)
            ->whereMonth('waktu', now()->month)
            ->latest('id')
            ->first();

        $payload = [
            'id_kondisi' => $request->id_kondisi,
            'waktu' => $request->waktu,
            'stand' => $request->stand,
            'gps' => $request->gps ?? '',
            'status' => 1,
            'status_verifikasi' => CatatMeter::VERIF_PENDING,
            'verifikasi_oleh' => null,
            'verifikasi_at' => null,
            'notif_dibaca_at' => null,
        ];

        if ($declined) {
            $declined->update($payload);
            $catatMeter = $declined;

            if ($request->hasFile('foto')) {
                foreach ($catatMeter->fotoCater as $old) {
                    Storage::disk('public')->delete($old->foto);
                    $old->delete();
                }
            }
        } else {
            $catatMeter = CatatMeter::create($payload + [
                'id_pelanggan' => $pelanggan->id,
                'id_petugas' => $petugas->id,
            ]);
        }

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
            'message' => 'Catat meter berhasil disimpan dan menunggu verifikasi.',
            'data' => [
                'id' => $catatMeter->id,
                'id_pelanggan' => $catatMeter->id_pelanggan,
                'stand' => $catatMeter->stand,
                'waktu' => $catatMeter->waktu,
                'status_verifikasi' => $catatMeter->status_verifikasi,
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

    private function transform(CatatMeter $item): array
    {
        return [
            'id' => $item->id,
            'id_pelanggan' => $item->id_pelanggan,
            'customer_name' => $item->pelanggan->nama ?? '',
            'customer_no' => $item->pelanggan->no_sambu ?? '',
            'customer_alamat' => $item->pelanggan->alamat ?? null,
            'stand' => $item->stand,
            'waktu' => $item->waktu,
            'gps' => $item->gps,
            'id_kondisi' => $item->id_kondisi,
            'kondisi' => $item->kondisiMeter->kondisi ?? '',
            'foto' => $item->fotoCater->first()?->foto,
            'status_verifikasi' => $item->status_verifikasi,
            'alasan_tolak' => $item->status_verifikasi === CatatMeter::VERIF_DECLINED
                ? $item->alasan_tolak
                : null,
            'verifikasi_at' => $item->verifikasi_at?->toIso8601String(),
            'dibaca' => $item->notif_dibaca_at !== null,
            'created_at' => $item->created_at->toIso8601String(),
        ];
    }
}
