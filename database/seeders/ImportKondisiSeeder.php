<?php

namespace Database\Seeders;

use App\Models\KondisiMeter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class ImportKondisiSeeder extends Seeder
{
    public function run(): void
    {
        $data = $this->fetchData();

        if (empty($data)) {
            $this->command->warn('Tidak bisa mengambil data dari server. Seed dibatalkan.');
            return;
        }

        $count = 0;
        foreach ($data as $item) {
            $raw = trim($item['nama'] ?? '');
            if (empty($raw)) continue;

            // split combined values like "MATI, BURAM, TERTIMBUN, GEDEK"
            $parts = array_map('trim', explode(',', $raw));
            foreach ($parts as $kondisi) {
                $kondisi = trim($kondisi);
                if (empty($kondisi)) continue;
                // truncate to fit varchar(20)
                $kondisi = substr($kondisi, 0, 20);

                $kode = $this->makeKode($kondisi);

                KondisiMeter::updateOrCreate(
                    ['kondisi' => $kondisi],
                    [
                        'kode' => $kode,
                        'keterangan' => $raw,
                    ]
                );
                $count++;
            }
        }

        $this->command->info("Berhasil mengimpor {$count} data kondisi meter.");
    }

    private function makeKode(string $nama): string
    {
        $kata = explode(' ', $nama);
        $huruf = '';
        foreach ($kata as $kw) {
            if (!empty($kw)) $huruf .= strtoupper($kw[0]);
        }
        return substr($huruf, 0, 10);
    }

    private function fetchData(): array
    {
        try {
            $response = Http::timeout(10)->get('http://localhost:5000/all-data');
            if ($response->successful()) {
                $json = $response->json();
                return $json['data']['kondisi_fisik'] ?? [];
            }
        } catch (\Exception $e) {
            $this->command->error('Gagal mengambil data: ' . $e->getMessage());
        }
        return [];
    }
}
