<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get(database_path('../petugas.md'));

        $json = preg_replace('/\]\s*\[/', ',', trim($json));

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        $inserted = 0;

        DB::transaction(function () use ($data, &$inserted) {
            foreach ($data as $item) {
                $username = $item['username'];
                $password = Str::lower(str_replace(' ', '', $username));

                $nip = !empty($item['nip']) ? $item['nip'] : 'ID-P' . $item['id_petugas'];

                $tipePekerjaan = $item['jenisPekerjaan'] === 'ADMIN' ? 'KANTOR' : 'LAPANGAN';
                $level = $item['jenisPekerjaan'] === 'ADMIN' ? 1 : 2;

                $existingUser = DB::table('users')
                    ->where('email', $item['email'])
                    ->orWhere('username', $username)
                    ->first();

                if ($existingUser) {
                    $userId = $existingUser->id;
                } else {
                    $userId = DB::table('users')->insertGetId([
                        'name' => $item['nama'],
                        'username' => $username,
                        'email' => $item['email'],
                        'password' => bcrypt($password),
                        'level' => $level,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $existingPetugas = DB::table('petugas')->find($item['id_petugas']);

                if (!$existingPetugas) {
                    DB::table('petugas')->insert([
                        'id' => $item['id_petugas'],
                        'photo' => null,
                        'nama' => $item['nama'],
                        'nip' => $nip,
                        'no_hp1' => Str::substr($item['noHp'], 0, 13),
                        'no_hp2' => null,
                        'tipe_pekerjaan' => $tipePekerjaan,
                        'level' => $level,
                        'jenis_pekerjaan' => $item['jenisPekerjaan'],
                        'user_id' => $userId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $inserted++;
                }
            }
        });

        $this->command->info('Successfully seeded ' . $inserted . ' petugas with user accounts.');
    }
}
