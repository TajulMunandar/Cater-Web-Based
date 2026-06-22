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
                ->select('w.id', 'w.wilayah', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
                ->groupBy('w.id', 'w.wilayah')
                ->orderByDesc('total')
                ->get()
                ->map(fn($row) => [
                    'id' => $row->id,
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

        $wilayahId = $request->query('wilayah_id');
        $detail = (bool) $request->query('detail', false);

        // Grid adaptif: kasar saat zoom-out (hemat row), halus saat zoom-in (akurat).
        // zoom 8 ≈ 220m/sel, zoom 11 ≈ 48m/sel, zoom 13 ≈ 21m/sel, zoom 16 ≈ 4m/sel.
        // Pada mode detail, step diabaikan di SELECT (tidak ada grouping), tapi tetap dipakai
        // untuk men-snap cache key supaya hit-rate tinggi.
        $step = max(0.00002, 0.008 / pow(1.5, $zoom - 9));

        // Bounding box viewport + buffer 30% agar marker di tepi tidak "pop" saat pan.
        $hasBbox = $request->has('neLat') && $request->has('neLng')
                && $request->has('swLat') && $request->has('swLng');

        $bbox = null;
        if ($hasBbox) {
            $neLat = (float) $request->query('neLat');
            $neLng = (float) $request->query('neLng');
            $swLat = (float) $request->query('swLat');
            $swLng = (float) $request->query('swLng');
            $latSpan = max(0.0001, $neLat - $swLat);
            $lngSpan = max(0.0001, $neLng - $swLng);
            $buffer = 0.30;
            $bbox = [
                'swLat' => $swLat - $latSpan * $buffer,
                'neLat' => $neLat + $latSpan * $buffer,
                'swLng' => $swLng - $lngSpan * $buffer,
                'neLng' => $neLng + $lngSpan * $buffer,
            ];
        }

        // Snap bbox ke grid supaya cache hit-rate tinggi untuk viewport yang hampir sama.
        $bboxSnapped = $bbox ? [
            'swLat' => floor($bbox['swLat'] / $step) * $step,
            'neLat' => ceil($bbox['neLat'] / $step) * $step,
            'swLng' => floor($bbox['swLng'] / $step) * $step,
            'neLng' => ceil($bbox['neLng'] / $step) * $step,
        ] : null;

        $cacheKey = sprintf(
            'dashboard_koordinat_v3_%s_z%d_%s_w%s',
            $detail ? 'd' : 'c',
            $zoom,
            $bboxSnapped
                ? sprintf('%s_%s_%s_%s',
                    round($bboxSnapped['swLat'], 5),
                    round($bboxSnapped['neLat'], 5),
                    round($bboxSnapped['swLng'], 5),
                    round($bboxSnapped['neLng'], 5))
                : 'global',
            $wilayahId ?? 'all'
        );

        $ttl = 180;

        $data = Cache::remember($cacheKey, $ttl, function () use ($step, $bbox, $wilayahId, $detail) {
            $query = \Illuminate\Support\Facades\DB::table('pelanggans as p')
                ->whereNotNull('p.lat')
                ->whereNotNull('p.long')
                ->where('p.lat', '!=', 0)
                ->where('p.long', '!=', 0)
                ->whereNull('p.deleted_at');

            if ($wilayahId) {
                $query->join('rutes as r', 'p.id_rute', '=', 'r.id')
                      ->where('r.id_wilayah', $wilayahId);
            }

            if ($bbox) {
                $query->whereBetween('p.lat', [$bbox['swLat'], $bbox['neLat']])
                      ->whereBetween('p.long', [$bbox['swLng'], $bbox['neLng']]);
            }

            if ($detail) {
                // Mode detail: 1 baris per pelanggan, data lengkap untuk popup.
                $query->leftJoin('golongans as g', 'p.id_gol', '=', 'g.id');
                if (!$wilayahId) {
                    $query->leftJoin('rutes as r2', 'p.id_rute', '=', 'r2.id')
                          ->leftJoin('wilayahs as w', 'r2.id_wilayah', '=', 'w.id');
                } else {
                    $query->leftJoin('wilayahs as w', 'r.id_wilayah', '=', 'w.id');
                }

                $query->select([
                    'p.id', 'p.nama', 'p.lat', 'p.long as lng',
                    'p.status', 'p.no_sambu', 'p.alamat', 'p.telepon',
                    'g.nama as golongan_nama',
                    'w.wilayah as wilayah_nama',
                ]);

                return $query->limit(2000)->get()->map(function ($row) {
                    return [
                        'id'      => 'p_' . $row->id,
                        'any_id'  => (int) $row->id,
                        'nama'    => $row->nama,
                        'lat'     => (float) $row->lat,
                        'lng'     => (float) $row->lng,
                        'status'  => $row->status,
                        'count'   => 1,
                        'detail'  => [
                            'no_sambu' => $row->no_sambu,
                            'alamat'   => $row->alamat,
                            'telepon'  => $row->telepon,
                            'golongan' => $row->golongan_nama,
                            'wilayah'  => $row->wilayah_nama,
                        ],
                    ];
                })->values()->all();
            }

            // Mode cluster (default): grid aggregation untuk performa.
            $query->selectRaw('
                ROUND(p.lat / ?) * ?  as grid_lat,
                ROUND(p.long / ?) * ? as grid_lng,
                COUNT(*)            as cnt,
                MAX(p.id)           as any_id,
                MAX(p.nama)         as any_nama,
                MAX(p.status = "non-aktif") as has_nonaktif
            ', [$step, $step, $step, $step]);

            $query->groupBy('grid_lat', 'grid_lng')
                  ->orderByRaw('cnt DESC')
                  ->limit(2000);

            return $query->get()->map(function ($row) {
                return [
                    'id'     => 'c_' . md5((string)($row->grid_lat . '_' . $row->grid_lng)),
                    'any_id' => (int) $row->any_id,
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
