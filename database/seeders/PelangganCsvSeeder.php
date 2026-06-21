<?php

namespace Database\Seeders;

use App\Models\FotoPelanggan;
use App\Models\Golongan;
use App\Models\KondisiMeter;
use App\Models\Pelanggan;
use App\Models\PelangganDetail;
use App\Models\Petugas;
use App\Models\Rute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PelangganCsvSeeder extends Seeder
{
    private $kondisiTextMap = [
        'NORMAL' => 'METER BAIK',
        'TERHALANG' => 'METER TERHALANG',
        'NORMAL, LAIN-LAIN' => 'METER RUSAK',
        'NORMAL, TERTIMBUN' => 'METER TERTIMBUN',
        'LAIN-LAIN' => 'METER RUSAK',
        'TERTIMBUN' => 'METER TERTIMBUN',
        'RUSAK' => 'METER RUSAK',
        'BURAM' => 'METER BURAM',
    ];

    public function run(): void
    {
        $filePath = database_path('pelanggan.csv');

        if (!File::exists($filePath)) {
            $this->command->error('File pelanggan.csv tidak ditemukan di ' . $filePath);
            return;
        }

        $file = fopen($filePath, 'r');
        $headers = fgetcsv($file);

        $petugasMap = Petugas::pluck('id', 'nama')->all();
        $golonganMap = Golongan::pluck('id', 'kode')->all();
        $ruteMap = Rute::pluck('id', 'kode')->all();
        $kondisiMap = KondisiMeter::pluck('id', 'kondisi')->all();

        $countPelanggan = 0;
        $countDetail = 0;
        $countFoto = 0;

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($headers, $row);

            $kodeUrut = str_replace('.', '', $data['KODE URUT']);
            $idRute = $ruteMap[$kodeUrut] ?? null;

            $namaPetugas = trim($data['PETUGAS']);
            $idPetugas = $petugasMap[$namaPetugas] ?? null;

            $golonganStr = trim($data['GOLONGAN']);
            $kodeGolongan = explode(' ', $golonganStr)[0];
            $idGol = $golonganMap[$kodeGolongan] ?? null;

            $statusRaw = strtolower(trim($data['STATUS']));
            $status = in_array($statusRaw, ['aktif', 'non-aktif']) ? $statusRaw : 'aktif';

            $lat = !empty($data['KOORDINAT']) ? (float) $data['KOORDINAT'] : null;
            $long = !empty($data['KOORDINA_1']) ? (float) $data['KOORDINA_1'] : null;

            $telepon = !empty($data['NOMOR HP/W']) ? str_replace([',', '.', '-', ' '], '', $data['NOMOR HP/W']) : null;

            $pelanggan = Pelanggan::updateOrCreate(
                ['no_sambu' => $data['ID PELANGG']],
                [
                    'nama' => $data['NAMA PELAN'],
                    'alamat' => $data['ALAMAT'],
                    'telepon' => $telepon,
                    'type' => null,
                    'id_rute' => $idRute,
                    'id_gol' => $idGol,
                    'status' => $status,
                    'lat' => $lat,
                    'long' => $long,
                ]
            );

            $countPelanggan++;

            $kondisiKey = strtoupper(trim($data['KONDISI FI']));
            $kondisiLabel = $this->kondisiTextMap[$kondisiKey] ?? $data['KONDISI FI'];
            $idKondisi = $kondisiMap[$kondisiLabel] ?? 11;

            PelangganDetail::updateOrCreate(
                ['id_pelanggan' => $pelanggan->id],
                [
                    'id_petugas' => $idPetugas ?? 1,
                    'id_kondisi' => $idKondisi,
                    'waktu_catat_meter' => $this->parseDate($data['TAHUN PASA']),
                    'stand_terakhir' => 0,
                    'ket' => trim($data['NOTES'] ?? ''),
                    'urutan' => 1,
                ]
            );

            $countDetail++;

            foreach (['FOTO RUMAH'] as $fotoCol) {
                $url = trim($data[$fotoCol] ?? '');
                if (!empty($url)) {
                    FotoPelanggan::updateOrCreate(
                        ['id_pelanggan' => $pelanggan->id, 'foto' => $url],
                        ['foto' => $url]
                    );
                    $countFoto++;
                }
            }
        }

        fclose($file);

        $this->command->info("PelangganCsvSeeder selesai: {$countPelanggan} pelanggan, {$countDetail} detail, {$countFoto} foto.");
    }

    private function parseDate($dateStr)
    {
        if (empty($dateStr)) {
            return null;
        }

        $months = [
            'januari' => '01', 'februari' => '02', 'maret' => '03', 'april' => '04',
            'mei' => '05', 'juni' => '06', 'juli' => '07', 'agustus' => '08',
            'september' => '09', 'oktober' => '10', 'november' => '11', 'desember' => '12',
        ];

        $dateStr = trim($dateStr);

        if (preg_match('/^(\d{1,2})\s+([a-zA-Z]+)\s+(\d{4}|\d{2})$/', $dateStr, $m)) {
            $day = $m[1];
            $month = strtolower($m[2]);
            $year = $m[3];

            if (isset($months[$month])) {
                $year = strlen($year) == 2 ? ($year > 50 ? 1900 + $year : 2000 + $year) : $year;
                return sprintf('%04d-%02d-%02d 00:00:00', $year, $months[$month], $day);
            }
        }

        $dt = \DateTime::createFromFormat('d-M-y', $dateStr);
        if ($dt !== false) {
            return $dt->format('Y-m-d H:i:s');
        }

        $dt = \DateTime::createFromFormat('d-M-Y', $dateStr);
        if ($dt !== false) {
            return $dt->format('Y-m-d H:i:s');
        }

        return null;
    }
}
