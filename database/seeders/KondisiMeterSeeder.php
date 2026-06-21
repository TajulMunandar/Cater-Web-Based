<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class KondisiMeterSeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get(database_path('../kondisi_meter.md'));

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        $inserted = 0;

        foreach ($data as $item) {
            $existing = DB::table('kondisi_meters')->find($item['id_kondisi']);

            if (!$existing) {
                DB::table('kondisi_meters')->insert([
                    'id' => $item['id_kondisi'],
                    'kondisi' => $item['kondisi_meter'],
                    'keterangan' => $item['kondisi_meter'],
                    'kode' => $item['kode'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $inserted++;
            }
        }

        $this->command->info('Successfully seeded ' . $inserted . ' kondisi meter.');
    }
}
