<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('dashboard_stats', 300, fn () => $this->computeStats());

        $pertumbuhan = Cache::remember('dashboard_pertumbuhan', 3600, function () {
            return \Illuminate\Support\Facades\DB::table('pelanggans')
                ->selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, COUNT(*) as total')
                ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
                ->whereNull('deleted_at')
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
            return \Illuminate\Support\Facades\DB::table('pelanggans as p')
                ->join('rutes as r', 'p.id_rute', '=', 'r.id')
                ->join('wilayahs as w', 'r.id_wilayah', '=', 'w.id')
                ->select('w.wilayah', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
                ->groupBy('w.wilayah')
                ->orderByDesc('total')
                ->get()
                ->map(fn($row) => [
                    'wilayah' => $row->wilayah,
                    'total' => (int) $row->total,
                ]);
        });

        $distribusiGolongan = Cache::remember('dashboard_golongan', 3600, function () {
            return \Illuminate\Support\Facades\DB::table('pelanggans as p')
                ->join('golongans as g', 'p.id_gol', '=', 'g.id')
                ->select('g.nama', 'g.kode', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
                ->groupBy('g.id', 'g.nama', 'g.kode')
                ->orderByDesc('total')
                ->get()
                ->map(fn($row) => [
                    'golongan' => $row->nama,
                    'kode' => $row->kode,
                    'total' => (int) $row->total,
                ]);
        });

        $aktivitasTerbaru = Cache::remember('dashboard_aktivitas', 300, function () {
            return Pelanggan::with(['rute.wilayah:id,wilayah', 'golongan:id,nama'])
                ->latest()
                ->limit(10)
                ->get(['id', 'nama', 'alamat', 'status', 'id_rute', 'id_gol', 'created_at']);
        });

        $pencatatanBulanIni = Cache::remember('dashboard_pencatatan', 3600, function () {
            $totalAktif = \Illuminate\Support\Facades\DB::table('pelanggans')
                ->where('status', Pelanggan::STATUS_AKTIF)
                ->whereNull('deleted_at')
                ->count();

            $start = now()->startOfMonth();
            $end = now()->endOfMonth();

            $tercatat = \Illuminate\Support\Facades\DB::table('catat_meters as c')
                ->selectRaw('COUNT(DISTINCT c.id_pelanggan) as total')
                ->whereBetween('c.waktu', [$start, $end])
                ->value('total');

            $belum = max(0, $totalAktif - $tercatat);

            return [
                'label' => now()->translatedFormat('F Y'),
                'tercatat' => (int) $tercatat,
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
            'aktivitasTerbaru', 'pencatatanBulanIni', 'notifikasi', 'page'
        ));
    }

    private function computeStats(): array
    {
        $row = \Illuminate\Support\Facades\DB::table('pelanggans')
            ->whereNull('deleted_at')
            ->selectRaw('
                COUNT(*) as total_pelanggan,
                SUM(CASE WHEN status = "aktif" THEN 1 ELSE 0 END) as pelanggan_aktif,
                SUM(CASE WHEN status = "non-aktif" THEN 1 ELSE 0 END) as pelanggan_nonaktif,
                SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as pelanggan_baru_bulan_ini
            ', [now()->month, now()->year])
            ->first();

        return [
            'total_pelanggan' => (int) $row->total_pelanggan,
            'pelanggan_aktif' => (int) $row->pelanggan_aktif,
            'pelanggan_nonaktif' => (int) $row->pelanggan_nonaktif,
            'pelanggan_baru_bulan_ini' => (int) $row->pelanggan_baru_bulan_ini,
        ];
    }

    /**
     * Grid-clustered coordinates for the map section (AJAX).
     *
     * Query params:
     *   - zoom:  zoom level (default 9, range 5-18)
     *   - neLat, neLng, swLat, swLng: bounding box (optional)
     *
     * Response: array of {id, nama, lat, lng, status, count}
     */
    public function getKoordinat(Request $request): JsonResponse
    {
        $zoom = (int) ($request->query('zoom', 9));
        $zoom = max(5, min(18, $zoom));

        $step = max(0.0005, 0.05 / pow(1.6, $zoom - 9));

        $neLat = $request->has('neLat') ? round((float) $request->query('neLat') / $step) * $step : null;
        $neLng = $request->has('neLng') ? round((float) $request->query('neLng') / $step) * $step : null;
        $swLat = $request->has('swLat') ? round((float) $request->query('swLat') / $step) * $step : null;
        $swLng = $request->has('swLng') ? round((float) $request->query('swLng') / $step) * $step : null;

        $cacheKey = sprintf(
            'dashboard_koordinat_z%d_%s_%s_%s_%s',
            $zoom,
            $neLat ?? 'def', $neLng ?? 'def', $swLat ?? 'def', $swLng ?? 'def'
        );

        $ttl = 120;

        $data = Cache::remember($cacheKey, $ttl, function () use ($zoom, $step, $neLat, $neLng, $swLat, $swLng, $request) {
            $query = \Illuminate\Support\Facades\DB::table('pelanggans as p')
                ->whereNotNull('p.lat')
                ->whereNotNull('p.long')
                ->where('p.lat', '!=', 0)
                ->where('p.long', '!=', 0)
                ->whereNull('p.deleted_at')
                ->selectRaw('
                    ROUND(p.lat / ?) * ?  as grid_lat,
                    ROUND(p.long / ?) * ? as grid_lng,
                    COUNT(*)            as cnt,
                    MAX(p.id)           as any_id,
                    MAX(p.nama)         as any_nama,
                    MAX(p.status = "non-aktif") as has_nonaktif
                ', [$step, $step, $step, $step]);

            if ($neLat !== null && $neLng !== null && $swLat !== null && $swLng !== null) {
                $query->whereBetween('p.lat', [$swLat, $neLat])
                       ->whereBetween('p.long', [$swLng, $neLng]);
            } else {
                $query->where('p.lat', '>=', 4.70)
                      ->where('p.lat', '<=', 5.40)
                      ->where('p.long', '>=', 96.60)
                      ->where('p.long', '<=', 97.55);
            }

            $query->limit(500)
                  ->groupBy('grid_lat', 'grid_lng');

            return $query->get()->map(function ($row) use ($step) {
                return [
                    'id'     => 'c_' . md5((string)($row->grid_lat . '_' . $row->grid_lng)),
                    'nama'   => $row->any_nama,
                    'lat'    => (float) $row->grid_lat,
                    'lng'    => (float) $row->grid_lng,
                    'status' => $row->has_nonaktif ? 'non-aktif' : 'aktif',
                    'count'  => (int) $row->cnt,
                ];
            })->values()->all();
        });

        return response()->json($data);
    }

    public function notifikasi(): JsonResponse
    {
        $stats = Cache::remember('dashboard_stats', 300, fn () => $this->computeStats());

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
        Cache::forget('dashboard_golongan');
        Cache::forget('dashboard_aktivitas');
        Cache::forget('dashboard_pencatatan');
        Cache::forget('dashboard_notifikasi');
    }

    private function buildNotifikasi(array $stats): array
    {
        return Cache::remember('dashboard_notifikasi', 60, function () use ($stats) {
            $notifikasi = [];

            $tanpaKoordinat = \Illuminate\Support\Facades\DB::table('pelanggans')
                ->where(function ($q) {
                    $q->whereNull('lat')->orWhereNull('long')->orWhere('lat', 0)->orWhere('long', 0);
                })
                ->whereNull('deleted_at')
                ->count();

            if ($tanpaKoordinat > 0) {
                $notifikasi[] = [
                    'type' => 'warning',
                    'icon' => 'map-pin-off',
                    'pesan' => "{$tanpaKoordinat} pelanggan belum memiliki koordinat lokasi.",
                    'url' => route('pelanggan.baru.index'),
                ];
            }

            $baruHariIni = \Illuminate\Support\Facades\DB::table('pelanggans')
                ->whereDate('created_at', today())
                ->whereNull('deleted_at')
                ->count();

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
        });
    }
}
