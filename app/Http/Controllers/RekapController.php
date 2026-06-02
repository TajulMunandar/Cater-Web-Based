<?php

namespace App\Http\Controllers;

use App\Models\CatatMeter;
use App\Models\KondisiMeter;
use App\Models\Petugas;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $page = 'Rekap Catat Meter';
        $wilayahs = Wilayah::all();
        $kondisis = KondisiMeter::all();

        $bulan = $request->input('bulan', now()->format('m'));
        $tahun = $request->input('tahun', now()->format('Y'));

        $daysInMonth = Carbon::create((int)$tahun, (int)$bulan)->daysInMonth;

        $petugas = Petugas::whereHas('CatatMeter', function ($q) use ($bulan, $tahun) {
            $q->whereYear('waktu', $tahun)->whereMonth('waktu', $bulan);
        })->orderBy('nama')->get();

        $rawData = CatatMeter::selectRaw('
                DAY(waktu) as hari,
                id_petugas,
                COUNT(*) as total
            ')
            ->whereYear('waktu', $tahun)
            ->whereMonth('waktu', $bulan)
            ->groupBy('hari', 'id_petugas')
            ->get();

        $matrix = [];
        $petugasTotals = [];
        foreach ($rawData as $row) {
            $matrix[$row->id_petugas][$row->hari] = $row->total;
            $petugasTotals[$row->id_petugas] = ($petugasTotals[$row->id_petugas] ?? 0) + $row->total;
        }

        $dayTotals = [];
        $chartData = [];
        foreach (range(1, $daysInMonth) as $day) {
            $total = 0;
            foreach ($petugas as $p) {
                $total += $matrix[$p->id][$day] ?? 0;
            }
            $dayTotals[$day] = $total;
            $chartData[] = ['hari' => $day, 'total' => $total];
        }

        $totalCatatan = array_sum($dayTotals);
        $totalPetugas = $petugas->count();
        $totalPelanggan = CatatMeter::whereYear('waktu', $tahun)
            ->whereMonth('waktu', $bulan)
            ->distinct('id_pelanggan')
            ->count('id_pelanggan');

        return view('dashboard.pages.data-rekap.rekap-catat-meter')
            ->with(compact(
                'page', 'wilayahs', 'kondisis',
                'bulan', 'tahun', 'daysInMonth',
                'petugas', 'matrix', 'petugasTotals', 'dayTotals', 'chartData',
                'totalCatatan', 'totalPetugas', 'totalPelanggan'
            ));
    }

    public function kondisi(Request $request)
    {
        $page = 'Rekap Kondisi';

        $bulan = $request->input('bulan', now()->format('m'));
        $tahun = $request->input('tahun', now()->format('Y'));

        $kondisiList = KondisiMeter::orderBy('kondisi')->get();
        $petugas = Petugas::whereHas('CatatMeter', function ($q) use ($bulan, $tahun) {
            $q->whereYear('waktu', $tahun)->whereMonth('waktu', $bulan);
        })->orderBy('nama')->get();

        $rawData = CatatMeter::selectRaw('
                id_petugas,
                id_kondisi,
                COUNT(*) as total
            ')
            ->whereYear('waktu', $tahun)
            ->whereMonth('waktu', $bulan)
            ->groupBy('id_petugas', 'id_kondisi')
            ->get();

        $matrix = [];
        $petugasTotals = [];
        foreach ($rawData as $row) {
            $matrix[$row->id_petugas][$row->id_kondisi] = (int)$row->total;
            $petugasTotals[$row->id_petugas] = ($petugasTotals[$row->id_petugas] ?? 0) + (int)$row->total;
        }

        $kondisiTotals = [];
        foreach ($kondisiList as $k) {
            $total = 0;
            foreach ($petugas as $p) {
                $total += $matrix[$p->id][$k->id] ?? 0;
            }
            $kondisiTotals[$k->id] = $total;
        }

        $grandTotal = array_sum($kondisiTotals);

        $kondisiBaikId = null;
        foreach ($kondisiList as $k) {
            if (strtolower($k->kondisi) === 'meter baik') {
                $kondisiBaikId = $k->id;
                break;
            }
        }

        $totalMeterBaik = $kondisiBaikId ? ($kondisiTotals[$kondisiBaikId] ?? 0) : 0;
        $totalBermasalah = $grandTotal - $totalMeterBaik;

        $petugasBaikTerbanyak = '-';
        $maxBaik = 0;
        foreach ($petugas as $p) {
            $v = $kondisiBaikId ? ($matrix[$p->id][$kondisiBaikId] ?? 0) : 0;
            if ($v > $maxBaik) {
                $maxBaik = $v;
                $petugasBaikTerbanyak = $p->nama;
            }
        }

        $kondisiColors = [
            'meter baik' => '#16a34a',
            'meter baik tdk ada air' => '#ca8a04',
            'meter baik tdk pakai air' => '#ea580c',
            'meter baik toko/rumah kosong' => '#2563eb',
            'meter baru' => '#0d9488',
            'meter buram' => '#64748b',
            'meter dibongkar pelanggan' => '#dc2626',
            'meter dilepas' => '#db2777',
            'meter hilang' => '#7c3aed',
            'meter kaca pecah' => '#4338ca',
            'meter lebih catat' => '#0891b2',
        ];

        return view('dashboard.pages.data-rekap.rekap-kondisi')
            ->with(compact(
                'page', 'kondisiList', 'petugas', 'bulan', 'tahun',
                'matrix', 'petugasTotals', 'kondisiTotals', 'grandTotal',
                'totalMeterBaik', 'totalBermasalah',
                'petugasBaikTerbanyak', 'maxBaik', 'kondisiColors'
            ));
    }

    public function wilayah(Request $request)
    {
        $page = 'Rekap Wilayah';

        $bulan = $request->input('bulan', now()->format('m'));
        $tahun = $request->input('tahun', now()->format('Y'));

        $daysInMonth = Carbon::create((int)$tahun, (int)$bulan)->daysInMonth;

        $semuaWilayah = Wilayah::orderBy('kode')->get();

        $rawData = CatatMeter::selectRaw('
                p.id_wilayah,
                DAY(cm.waktu) as hari,
                COUNT(DISTINCT cm.id_pelanggan) as total
            ')
            ->from('catat_meters as cm')
            ->join('pelanggans as p', 'cm.id_pelanggan', '=', 'p.id')
            ->whereYear('cm.waktu', $tahun)
            ->whereMonth('cm.waktu', $bulan)
            ->groupBy('p.id_wilayah', DB::raw('DAY(cm.waktu)'))
            ->get();

        $matrix = [];
        $wilayahTotals = [];
        foreach ($rawData as $row) {
            $matrix[$row->id_wilayah][$row->hari] = (int)$row->total;
            $wilayahTotals[$row->id_wilayah] = ($wilayahTotals[$row->id_wilayah] ?? 0) + (int)$row->total;
        }

        $dayTotals = [];
        foreach (range(1, $daysInMonth) as $day) {
            $total = 0;
            foreach ($semuaWilayah as $w) {
                $total += $matrix[$w->id][$day] ?? 0;
            }
            $dayTotals[$day] = $total;
        }

        $totalCatatanBulanIni = array_sum($dayTotals);
        $totalWilayahAktif = count($wilayahTotals);
        $totalPelangganDicatat = CatatMeter::whereYear('waktu', $tahun)
            ->whereMonth('waktu', $bulan)
            ->distinct('id_pelanggan')
            ->count('id_pelanggan');

        $tanggalTerbanyak = '-';
        $maxCatatanTanggal = 0;
        if (!empty($dayTotals)) {
            $maxVal = max($dayTotals);
            if ($maxVal > 0) {
                $tanggalTerbanyak = array_keys($dayTotals, $maxVal)[0];
                $maxCatatanTanggal = $maxVal;
            }
        }

        return view('dashboard.pages.data-rekap.rekap-wilayah')
            ->with(compact(
                'page', 'semuaWilayah', 'bulan', 'tahun', 'daysInMonth',
                'matrix', 'wilayahTotals', 'dayTotals',
                'totalCatatanBulanIni', 'totalWilayahAktif', 'totalPelangganDicatat',
                'tanggalTerbanyak', 'maxCatatanTanggal'
            ));
    }

    public function dataCatatMeter(Request $request)
    {
        $query = CatatMeter::selectRaw('
            DATE(waktu) as tanggal,
            COUNT(*) as total_catatan,
            COUNT(DISTINCT id_petugas) as total_petugas,
            COUNT(DISTINCT id_pelanggan) as total_pelanggan_dicatat
        ');

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('waktu', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('waktu', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('id_wilayah')) {
            $query->whereHas('Pelanggan', function ($q) use ($request) {
                $q->where('id_wilayah', $request->id_wilayah);
            });
        }
        if ($request->filled('id_kondisi')) {
            $query->where('id_kondisi', $request->id_kondisi);
        }

        $query->groupBy(DB::raw('DATE(waktu)'));
        $query->orderBy('tanggal', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_formatted', function ($row) {
                return date('d/m/Y', strtotime($row->tanggal));
            })
            ->make(true);
    }

    public function dataKondisi(Request $request)
    {
        $totalAllQuery = CatatMeter::from('catat_meters as cm')
            ->join('kondisi_meters as km', 'cm.id_kondisi', '=', 'km.id');

        if ($request->filled('tanggal_awal')) {
            $totalAllQuery->whereDate('cm.waktu', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $totalAllQuery->whereDate('cm.waktu', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('id_wilayah')) {
            $totalAllQuery->join('pelanggans as p', 'cm.id_pelanggan', '=', 'p.id')
                ->where('p.id_wilayah', $request->id_wilayah);
        }

        $totalAll = $totalAllQuery->count();

        $query = CatatMeter::selectRaw('
            km.id as kondisi_id,
            km.kondisi,
            COUNT(cm.id) as total_data
        ')
            ->from('catat_meters as cm')
            ->join('kondisi_meters as km', 'cm.id_kondisi', '=', 'km.id');

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('cm.waktu', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('cm.waktu', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('id_wilayah')) {
            $query->join('pelanggans as p', 'cm.id_pelanggan', '=', 'p.id')
                ->where('p.id_wilayah', $request->id_wilayah);
        }

        $query->groupBy('km.id', 'km.kondisi');
        $query->orderBy('total_data', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('persentase', function ($row) use ($totalAll) {
                return $totalAll > 0 ? round(($row->total_data / $totalAll) * 100, 1) : 0;
            })
            ->addColumn('kondisi_badge', function ($row) {
                $k = strtolower($row->kondisi);
                if ($k == 'aktif') return '<span class="badge bg-success">' . e($row->kondisi) . '</span>';
                if ($k == 'rusak') return '<span class="badge bg-danger">' . e($row->kondisi) . '</span>';
                return '<span class="badge bg-warning text-dark">' . e($row->kondisi) . '</span>';
            })
            ->rawColumns(['kondisi_badge'])
            ->make(true);
    }

    public function dataWilayah(Request $request)
    {
        $query = CatatMeter::selectRaw('
            w.id as wilayah_id,
            w.wilayah,
            COUNT(DISTINCT cm.id_pelanggan) as total_pelanggan,
            COUNT(CASE WHEN LOWER(km.kondisi) = \'aktif\' THEN 1 END) as meter_aktif,
            COUNT(CASE WHEN LOWER(km.kondisi) = \'rusak\' THEN 1 END) as meter_rusak,
            COUNT(cm.id) as total_catatan
        ')
            ->from('catat_meters as cm')
            ->join('pelanggans as p', 'cm.id_pelanggan', '=', 'p.id')
            ->join('wilayahs as w', 'p.id_wilayah', '=', 'w.id')
            ->join('kondisi_meters as km', 'cm.id_kondisi', '=', 'km.id');

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('cm.waktu', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('cm.waktu', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('id_kondisi')) {
            $query->where('cm.id_kondisi', $request->id_kondisi);
        }

        $query->groupBy('w.id', 'w.wilayah');
        $query->orderBy('w.wilayah');

        return DataTables::of($query)
            ->addIndexColumn()
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
