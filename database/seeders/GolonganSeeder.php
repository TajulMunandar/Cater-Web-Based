<?php

namespace Database\Seeders;

use App\Models\Golongan;
use Illuminate\Database\Seeder;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $golongans = [
            ['kode' => 'D1', 'nama' => 'Domestik 1', 'tarif_per_m3' => 5000, 'biaya_admin' => 10000],
            ['kode' => 'D2', 'nama' => 'Domestik 2', 'tarif_per_m3' => 6000, 'biaya_admin' => 15000],
            ['kode' => 'D3', 'nama' => 'Domestik 3', 'tarif_per_m3' => 7000, 'biaya_admin' => 20000],
            ['kode' => 'K1', 'nama' => 'Komersial 1', 'tarif_per_m3' => 8000, 'biaya_admin' => 25000],
            ['kode' => 'K2', 'nama' => 'Komersial 2', 'tarif_per_m3' => 10000, 'biaya_admin' => 30000],
            ['kode' => 'I', 'nama' => 'Industri', 'tarif_per_m3' => 12000, 'biaya_admin' => 50000],
        ];

        foreach ($golongans as $golongan) {
            Golongan::create($golongan);
        }
    }
}