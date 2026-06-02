<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pelanggan>
 */
class PelangganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'no_sambu' => fake()->unique()->regexify('[A-Z]{2}-[0-9]{4}'),
            'no_kontrol' => fake()->unique()->regexify('[A-Z]{2}-[0-9]{4}'),
            'nama' => fake()->name(),
            'alamat' => fake()->address(),
            'telepon' => fake()->phoneNumber(),
            'status' => Pelanggan::STATUS_AKTIF,
        ];
    }
}
