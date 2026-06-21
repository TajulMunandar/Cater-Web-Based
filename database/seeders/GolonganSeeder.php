<?php

namespace Database\Seeders;

use App\Models\Golongan;
use Illuminate\Database\Seeder;

class GolonganSeeder extends Seeder
{
    public function run(): void
    {
        $golongans = [
            ['id' => 7, 'kode' => '3A', 'nama' => 'USAHA A', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => '3A - USAHA A'],
            ['id' => 8, 'kode' => 'RC', 'nama' => 'RUMAH TANGGA C', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => 'RC - RUMAH TANGGA C'],
            ['id' => 9, 'kode' => '2B', 'nama' => 'RUMAH TANGGA B', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => '2B - RUMAH TANGGA B'],
            ['id' => 10, 'kode' => '2C', 'nama' => 'RUMAH TANGGA C', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => '2C - RUMAH TANGGA C'],
            ['id' => 11, 'kode' => '2F', 'nama' => 'INSTANSI PEMERINTAH DAN TNI/POLRI', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => '2F - INSTANSI PEMERINTAH DAN TNI/POLRI'],
            ['id' => 12, 'kode' => 'UA', 'nama' => 'USAHA A', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => 'UA - USAHA A'],
            ['id' => 13, 'kode' => 'RB', 'nama' => 'RUMAH TANGGA B', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => 'RB - RUMAH TANGGA B'],
            ['id' => 14, 'kode' => '2D', 'nama' => 'RUMAH TANGGA D', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => '2D - RUMAH TANGGA D'],
            ['id' => 15, 'kode' => 'RD', 'nama' => 'RUMAH TANGGA D', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => 'RD - RUMAH TANGGA D'],
            ['id' => 16, 'kode' => '1B', 'nama' => 'SOSIAL KHUSUS', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => '1B - SOSIAL KHUSUS'],
            ['id' => 17, 'kode' => '1A', 'nama' => 'SOSIAL UMUM', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => '1A - SOSIAL UMUM'],
            ['id' => 18, 'kode' => '3B', 'nama' => 'USAHA B', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => '3B - USAHA B'],
            ['id' => 19, 'kode' => 'IP', 'nama' => 'INSTANSI PEMERINTAH DAN TNI/POLRI', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => 'IP - INSTANSI PEMERINTAH DAN TNI/POLRI'],
            ['id' => 20, 'kode' => 'SB', 'nama' => 'SOSIAL KHUSUS', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => 'SB - SOSIAL KHUSUS'],
            ['id' => 21, 'kode' => 'SA', 'nama' => 'SOSIAL UMUM', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => 'SA - SOSIAL UMUM'],
            ['id' => 22, 'kode' => 'UB', 'nama' => 'USAHA B', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => 'UB - USAHA B'],
            ['id' => 118, 'kode' => 'TKC', 'nama' => 'TARIF KHUSUS AIR CURAH', 'tarif_per_m3' => 0.00, 'biaya_admin' => 0.00, 'keterangan' => 'TKC - TARIF KHUSUS AIR CURAH'],
        ];

        foreach ($golongans as $golongan) {
            Golongan::updateOrCreate(
                ['id' => $golongan['id']],
                $golongan
            );
        }
    }
}
