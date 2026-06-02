<?php

namespace App\Http\Controllers;

use App\Models\CatatMeter;
use App\Models\Pelanggan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('dashboard_stats', 300, function () {
            $total = Pelanggan::count();
            $aktif = Pelanggan::where('status', Pelanggan::STATUS_AKTIF)->count();
            $nonaktif = Pelanggan::where('status', Pelanggan::STATUS_NON_AKTIF)->count();
            $baruBulanIni = Pelanggan::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            return [
                'total_pelanggan' => $total,
                'pelanggan_aktif' => $aktif,
                'pelanggan_nonaktif' => $nonaktif,
                'pelanggan_baru_bulan_ini' => $baruBulanIni,
            ];
        });

        $pertumbuhan = Cache::remember('dashboard_pertumbuhan', 3600, function () {
            return Pelanggan::selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, COUNT(*) as total')
                ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
                ->groupBy('tahun', 'bulan')
                ->orderBy('tahun')
                ->orderBy('bulan')
                ->get()
                ->map(fn($row) => [
                    'label' => \Carbon\Carbon::create($row->tahun, $row->bulan)->translatedFormat('M Y'),
                    'total' => (int) $row->total,
                ]);
        });

        $distribusiWilayah = Cache::remember('dashboard_wilayah', 3600, function () {
            return Pelanggan::selectRaw('id_wilayah, COUNT(*) as total')
                ->with('wilayah:id,wilayah')
                ->groupBy('id_wilayah')
                ->get()
                ->map(fn($row) => [
                    'wilayah' => $row->wilayah?->wilayah ?? 'Tidak Diketahui',
                    'total' => (int) $row->total,
                ]);
        });

        $distribusiGolongan = Pelanggan::selectRaw('id_gol, COUNT(*) as total')
            ->with('golongan:id,nama,kode')
            ->groupBy('id_gol')
            ->get()
            ->map(fn($row) => [
                'golongan' => $row->golongan?->nama ?? '-',
                'kode' => $row->golongan?->kode ?? '-',
                'total' => (int) $row->total,
            ]);

        $koordinatPelanggan = Cache::remember('dashboard_koordinat', 600, function () {
            return Pelanggan::select('id', 'nama', 'alamat', 'lat', 'long', 'status', 'id_wilayah')
                ->whereNotNull('lat')
                ->whereNotNull('long')
                ->where('lat', '!=', 0)
                ->where('long', '!=', 0)
                ->with('wilayah:id,wilayah')
                ->get()
                ->map(fn($p) => [
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'alamat' => $p->alamat,
                    'lat' => (float) $p->lat,
                    'long' => (float) $p->long,
                    'status' => $p->status,
                    'wilayah' => $p->wilayah?->wilayah ?? '-',
                    'url' => route('pelanggan.show', $p->id),
                ]);
        });

        $aktivitasTerbaru = Pelanggan::with(['wilayah:id,wilayah', 'golongan:id,nama'])
            ->latest()
            ->limit(10)
            ->get(['id', 'nama', 'alamat', 'status', 'id_wilayah', 'id_gol', 'created_at']);

        $pencatatanBulanIni = Cache::remember('dashboard_pencatatan', 3600, function () {
            $totalAktif = Pelanggan::where('status', Pelanggan::STATUS_AKTIF)->count();

            $start = now()->startOfMonth();
            $end = now()->endOfMonth();

            $tercatat = CatatMeter::whereBetween('waktu', [$start, $end])
                ->distinct('id_pelanggan')
                ->count('id_pelanggan');

            $belum = max(0, $totalAktif - $tercatat);

            return [
                'label' => now()->translatedFormat('F Y'),
                'tercatat' => $tercatat,
                'belum_tercatat' => $belum,
                'total_aktif' => $totalAktif,
                'persentase_tercatat' => $totalAktif > 0 ? round($tercatat / $totalAktif * 100) : 0,
                'persentase_belum' => $totalAktif > 0 ? round($belum / $totalAktif * 100) : 0,
            ];
        });

        $notifikasi = $this->buildNotifikasi($stats);

        $page = 'Dashboard';

        return view('dashboard.pages.index', compact(
            'stats', 'pertumbuhan', 'distribusiWilayah', 'distribusiGolongan',
            'koordinatPelanggan', 'aktivitasTerbaru', 'pencatatanBulanIni', 'notifikasi', 'page'
        ));
    }

    public function notifikasi(): JsonResponse
    {
        $total = Pelanggan::count();
        $aktif = Pelanggan::where('status', Pelanggan::STATUS_AKTIF)->count();
        $nonaktif = Pelanggan::where('status', Pelanggan::STATUS_NON_AKTIF)->count();

        $stats = [
            'total_pelanggan' => $total,
            'pelanggan_aktif' => $aktif,
            'pelanggan_nonaktif' => $nonaktif,
        ];

        return response()->json($this->buildNotifikasi($stats));
    }

    public function refresh(): RedirectResponse
    {
        $this->clearDashboardCache();
        return redirect()->route('dashboard')->with('success', 'Data dashboard diperbarui.');
    }

    public function clearDashboardCache(): void
    {
        Cache::forget('dashboard_stats');
        Cache::forget('dashboard_pertumbuhan');
        Cache::forget('dashboard_wilayah');
        Cache::forget('dashboard_koordinat');
        Cache::forget('dashboard_pencatatan');
    }

    private function buildNotifikasi(array $stats): array
    {
        $notifikasi = [];

        $tanpaKoordinat = Pelanggan::where(function ($q) {
            $q->whereNull('lat')->orWhereNull('long')->orWhere('lat', 0)->orWhere('long', 0);
        })->count();

        if ($tanpaKoordinat > 0) {
            $notifikasi[] = [
                'type' => 'warning',
                'icon' => 'map-pin-off',
                'pesan' => "{$tanpaKoordinat} pelanggan belum memiliki koordinat lokasi.",
                'url' => route('pelanggan.baru.index'),
            ];
        }

        $baruHariIni = Pelanggan::whereDate('created_at', today())->count();
        if ($baruHariIni > 0) {
            $notifikasi[] = [
                'type' => 'success',
                'icon' => 'user-plus',
                'pesan' => "{$baruHariIni} pelanggan baru terdaftar hari ini.",
                'url' => route('pelanggan.baru.index'),
            ];
        }

        $pctNonAktif = $stats['total_pelanggan'] > 0
            ? round($stats['pelanggan_nonaktif'] / $stats['total_pelanggan'] * 100)
            : 0;

        if ($pctNonAktif > 20) {
            $notifikasi[] = [
                'type' => 'danger',
                'icon' => 'alert-triangle',
                'pesan' => "{$pctNonAktif}% pelanggan berstatus non-aktif ({$stats['pelanggan_nonaktif']} pelanggan).",
                'url' => route('pelanggan.index'),
            ];
        }

        return $notifikasi;
    }
}
