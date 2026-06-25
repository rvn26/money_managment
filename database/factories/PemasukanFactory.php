<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pemasukan>
 */
class PemasukanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'tanggal' => $this->faker->date('Y-m-d', 'now'),
            'jenis' => $this->faker->randomElement(['gaji', 'bonus', 'penjualan', 'investasi', 'lain-lain']),
            'total' => $this->faker->randomFloat(2, 10000, 5000000),
            'metode_pembayaran' => $this->faker->randomElement(['Qris', 'Bank', 'Dana', 'Gopay', 'Cash']),
            'status' => $this->faker->randomElement(['pending', 'lunas']),
            'deskripsi' => $this->faker->sentence(),
        ];
    }
}
