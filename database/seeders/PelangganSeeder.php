<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggans = [
            [
                'no_sambu' => 'SMB-001',
                'no_kontrol' => 'KTR-001',
                'nama' => 'Ahmad Fauzi',
                'alamat' => 'Jl. Merdeka No. 12, Kel. Pusat Kota',
                'telepon' => '081234567890',
                'type' => 'Rumah Tangga',
                'id_wilayah' => null,
                'id_gol' => 1,
                'status' => Pelanggan::STATUS_AKTIF,
                'lat' => 4.8712,
                'long' => 97.4798,
            ],
            [
                'no_sambu' => 'SMB-002',
                'no_kontrol' => 'KTR-002',
                'nama' => 'Siti Rahmawati',
                'alamat' => 'Jl. Diponegoro No. 45, Kel. Sejahtera',
                'telepon' => '082345678901',
                'type' => 'Rumah Tangga',
                'id_wilayah' => null,
                'id_gol' => 2,
                'status' => Pelanggan::STATUS_AKTIF,
                'lat' => 4.8650,
                'long' => 97.4850,
            ],
            [
                'no_sambu' => 'SMB-003',
                'no_kontrol' => 'KTR-003',
                'nama' => 'Budi Hartono',
                'alamat' => 'Jl. Sudirman No. 78, Kel. Makmur',
                'telepon' => '083456789012',
                'type' => 'Rumah Tangga',
                'id_wilayah' => null,
                'id_gol' => 1,
                'status' => Pelanggan::STATUS_AKTIF,
                'lat' => 4.8780,
                'long' => 97.4720,
            ],
            [
                'no_sambu' => 'SMB-004',
                'no_kontrol' => 'KTR-004',
                'nama' => 'Dewi Sartika',
                'alamat' => 'Jl. Teuku Umar No. 23, Kel. Damai',
                'telepon' => '084567890123',
                'type' => 'Rumah Tangga',
                'id_wilayah' => null,
                'id_gol' => 2,
                'status' => Pelanggan::STATUS_NON_AKTIF,
                'lat' => 4.8600,
                'long' => 97.4900,
            ],
            [
                'no_sambu' => 'SMB-005',
                'no_kontrol' => 'KTR-005',
                'nama' => 'CV. Karya Mandiri',
                'alamat' => 'Jl. Ahmad Yani No. 100, Kel. Industri',
                'telepon' => '085678901234',
                'type' => 'Komersial',
                'id_wilayah' => null,
                'id_gol' => 4,
                'status' => Pelanggan::STATUS_AKTIF,
                'lat' => 4.8820,
                'long' => 97.4650,
            ],
            [
                'no_sambu' => 'SMB-006',
                'no_kontrol' => 'KTR-006',
                'nama' => 'H. Abdullah',
                'alamat' => 'Jl. Pattimura No. 67, Kel. Agraris',
                'telepon' => '086789012345',
                'type' => 'Rumah Tangga',
                'id_wilayah' => null,
                'id_gol' => 3,
                'status' => Pelanggan::STATUS_AKTIF,
                'lat' => 4.8750,
                'long' => 97.4780,
            ],
            [
                'no_sambu' => 'SMB-007',
                'no_kontrol' => 'KTR-007',
                'nama' => 'Indah Lestari',
                'alamat' => 'Jl. Gajah Mada No. 34, Kel. Asri',
                'telepon' => '087890123456',
                'type' => 'Rumah Tangga',
                'id_wilayah' => null,
                'id_gol' => 1,
                'status' => Pelanggan::STATUS_AKTIF,
                'lat' => 4.8680,
                'long' => 97.4820,
            ],
            [
                'no_sambu' => 'SMB-008',
                'no_kontrol' => 'KTR-008',
                'nama' => 'PD. Sumber Jaya',
                'alamat' => 'Jl. Hasanuddin No. 56, Kel. Niaga',
                'telepon' => '088901234567',
                'type' => 'Komersial',
                'id_wilayah' => null,
                'id_gol' => 5,
                'status' => Pelanggan::STATUS_AKTIF,
                'lat' => 4.8900,
                'long' => 97.4600,
            ],
            [
                'no_sambu' => 'SMB-009',
                'no_kontrol' => 'KTR-009',
                'nama' => 'Rudi Hermawan',
                'alamat' => 'Jl. Imam Bonjol No. 89, Kel. Permai',
                'telepon' => '089012345678',
                'type' => 'Rumah Tangga',
                'id_wilayah' => null,
                'id_gol' => 1,
                'status' => Pelanggan::STATUS_NON_AKTIF,
                'lat' => 4.8550,
                'long' => 97.4950,
            ],
            [
                'no_sambu' => 'SMB-010',
                'no_kontrol' => 'KTR-010',
                'nama' => 'Nurul Hidayah',
                'alamat' => 'Jl. KH. Ahmad Dahlan No. 15, Kel. Harmoni',
                'telepon' => '080123456789',
                'type' => 'Rumah Tangga',
                'id_wilayah' => null,
                'id_gol' => 2,
                'status' => Pelanggan::STATUS_AKTIF,
                'lat' => 4.8720,
                'long' => 97.4750,
            ],
        ];

        foreach ($pelanggans as $data) {
            Pelanggan::create($data);
        }
    }
}
