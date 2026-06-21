<?php

namespace Database\Seeders;

use App\Models\Golongan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class ImportGolonganSeeder extends Seeder
{
    public function run(): void
    {
        $data = $this->fetchData();

        if (empty($data)) {
            $this->command->warn('Tidak bisa mengambil data dari server. Seed dibatalkan.');
            return;
        }

        // only import proper golongan (codes like "1A", "2B", "RC", etc.), not individual names
        $count = 0;
        foreach ($data as $item) {
            $kode = trim($item['kode'] ?? '');
            $nama = trim($item['nama'] ?? '');
            if (empty($kode) || empty($nama)) continue;
            // skip individual names: codes matching names (person entries), or codes with spaces/slashes, or too long
            if ($kode === $nama || strlen($kode) > 10 || preg_match('/[\/\s]/', $kode)) continue;

            Golongan::updateOrCreate(
                ['kode' => $kode],
                [
                    'nama' => $nama,
                    'keterangan' => $item['original'] ?? null,
                ]
            );
            $count++;
        }

        $this->command->info("Berhasil mengimpor {$count} data golongan.");
    }

    private function fetchData(): array
    {
        try {
            $response = Http::timeout(10)->get('http://localhost:5000/all-data');
            if ($response->successful()) {
                $json = $response->json();
                return $json['data']['golongan'] ?? [];
            }
        } catch (\Exception $e) {
            $this->command->error('Gagal mengambil data: ' . $e->getMessage());
        }
        return [];
    }
}
