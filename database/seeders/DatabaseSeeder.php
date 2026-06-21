<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            GolonganSeeder::class,
            KondisiMeterSeeder::class,
            PetugasSeeder::class,
            WilayahSeeder::class,
            PelangganCsvSeeder::class,
        ]);
    }
}
