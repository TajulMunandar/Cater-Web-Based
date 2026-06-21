<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get(database_path('../wilayah.md'));

        $json = preg_replace('/\]\s*\[/', ',', trim($json));

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        $grouped = [];
        foreach ($data as $item) {
            $cabang = $item['cabang'];
            if (!isset($grouped[$cabang])) {
                $grouped[$cabang] = [
                    'id' => $item['id_wilayah'],
                    'items' => [],
                ];
            }
            $grouped[$cabang]['items'][] = $item;
        }

        $insertedWilayah = 0;
        $insertedRute = 0;

        DB::transaction(function () use ($grouped, &$insertedWilayah, &$insertedRute) {
            foreach ($grouped as $cabang => $group) {
                $wilayahId = $group['id'];

                $existingWilayah = DB::table('wilayahs')->find($wilayahId);
                if (!$existingWilayah) {
                    DB::table('wilayahs')->insert([
                        'id' => $wilayahId,
                        'wilayah' => $cabang,
                        'ket' => '',
                        'center_lat' => '',
                        'center_long' => '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $insertedWilayah++;
                }

                foreach ($group['items'] as $item) {
                    $existingRute = DB::table('rutes')
                        ->where('id_wilayah', $wilayahId)
                        ->where('rute', $item['nama_wilayah'])
                        ->first();
                    if (!$existingRute) {
                        DB::table('rutes')->insert([
                            'id_wilayah' => $wilayahId,
                            'rute' => $item['nama_wilayah'],
                            'kode' => $item['kode_wilayah'],
                            'ket' => '',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $insertedRute++;
                    }
                }
            }
        });

        $this->command->info("Successfully seeded {$insertedWilayah} wilayah and {$insertedRute} rute.");
    }
}
