<?php

namespace Database\Seeders;

use App\Models\PelangganDetail;
use Illuminate\Database\Seeder;

class PelangganDetailSeeder extends Seeder
{
    public function run(): void
    {
        $details = [
            [
                'id_pelanggan' => 1,
                'id_petugas' => 1,
                'rute' => 1,
                'id_kondisi' => 1,
                'waktu_catat_meter' => now()->subMonth()->setTime(8, 30),
                'stand_terakhir' => 1250,
                'ket' => 'Normal',
                'urutan' => 1,
                'id_wilayah' => null,
            ],
            [
                'id_pelanggan' => 2,
                'id_petugas' => 1,
                'rute' => 1,
                'id_kondisi' => 1,
                'waktu_catat_meter' => now()->subMonth()->setTime(9, 15),
                'stand_terakhir' => 980,
                'ket' => 'Normal',
                'urutan' => 2,
                'id_wilayah' => null,
            ],
            [
                'id_pelanggan' => 3,
                'id_petugas' => 1,
                'rute' => 1,
                'id_kondisi' => 2,
                'waktu_catat_meter' => now()->subMonth()->setTime(10, 0),
                'stand_terakhir' => 2100,
                'ket' => 'Meter buram',
                'urutan' => 3,
                'id_wilayah' => null,
            ],
            [
                'id_pelanggan' => 4,
                'id_petugas' => 1,
                'rute' => 1,
                'id_kondisi' => 1,
                'waktu_catat_meter' => now()->subMonth()->setTime(10, 45),
                'stand_terakhir' => 450,
                'ket' => 'Normal',
                'urutan' => 4,
                'id_wilayah' => null,
            ],
            [
                'id_pelanggan' => 5,
                'id_petugas' => 2,
                'rute' => 2,
                'id_kondisi' => 1,
                'waktu_catat_meter' => now()->subMonth()->setTime(8, 0),
                'stand_terakhir' => 5600,
                'ket' => 'Normal',
                'urutan' => 1,
                'id_wilayah' => null,
            ],
            [
                'id_pelanggan' => 6,
                'id_petugas' => 2,
                'rute' => 2,
                'id_kondisi' => 1,
                'waktu_catat_meter' => now()->subMonth()->setTime(8, 45),
                'stand_terakhir' => 1780,
                'ket' => 'Normal',
                'urutan' => 2,
                'id_wilayah' => null,
            ],
            [
                'id_pelanggan' => 7,
                'id_petugas' => 2,
                'rute' => 2,
                'id_kondisi' => 1,
                'waktu_catat_meter' => now()->subMonth()->setTime(9, 30),
                'stand_terakhir' => 1320,
                'ket' => 'Normal',
                'urutan' => 3,
                'id_wilayah' => null,
            ],
            [
                'id_pelanggan' => 8,
                'id_petugas' => 2,
                'rute' => 2,
                'id_kondisi' => 1,
                'waktu_catat_meter' => now()->subMonth()->setTime(10, 15),
                'stand_terakhir' => 4200,
                'ket' => 'Normal',
                'urutan' => 4,
                'id_wilayah' => null,
            ],
            [
                'id_pelanggan' => 9,
                'id_petugas' => 1,
                'rute' => 1,
                'id_kondisi' => 2,
                'waktu_catat_meter' => now()->subMonth()->setTime(11, 30),
                'stand_terakhir' => 670,
                'ket' => 'Meter buram',
                'urutan' => 5,
                'id_wilayah' => null,
            ],
            [
                'id_pelanggan' => 10,
                'id_petugas' => 1,
                'rute' => 1,
                'id_kondisi' => 1,
                'waktu_catat_meter' => now()->subMonth()->setTime(12, 0),
                'stand_terakhir' => 890,
                'ket' => 'Normal',
                'urutan' => 6,
                'id_wilayah' => null,
            ],
        ];

        foreach ($details as $data) {
            PelangganDetail::create($data);
        }
    }
}
