<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan locale Indonesia untuk data yang lebih relevan

        // Generate 10.000 data petugas
        foreach (range(1, 10000) as $index) {
            DB::table('petugas')->insert([
                'photo' => $faker->imageUrl(640, 480, 'people', true), // Generate URL foto palsu
                'nama' => $faker->name,
                'nip' => $faker->unique()->numerify('NIP##########'), // Generate NIP acak
                'no_hp1' => $faker->phoneNumber,
                'no_hp2' => $faker->optional()->phoneNumber, // no_hp2 optional
                'email' => $faker->unique()->safeEmail,
                'username' => $faker->unique()->userName,
                'password' => bcrypt('password123'), // Default password untuk semua user
                'tipe_pekerjaan' => $faker->word,
                'level' => $faker->randomElement([1, 2, 3]), // Misalnya level 1, 2, 3
                'jenis_pekerjaan' => $faker->word,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
