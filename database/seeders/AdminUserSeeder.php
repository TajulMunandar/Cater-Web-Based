<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user (level = 0)
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'level' => 0,
        ]);

        // Create Petugas user (level = 1)
        User::create([
            'name' => 'Petugas',
            'username' => 'petugas',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password'),
            'level' => 1,
        ]);
    }
}
