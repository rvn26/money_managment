<?php

namespace Database\Factories;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengeluaran>
 */
class PengeluaranFactory extends Factory
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
            'id_kategori' => Kategori::factory(),
            'tanggal_pengeluaran' => $this->faker->date('Y-m-d', 'now'),
            'total' => $this->faker->randomFloat(2, 5000, 2000000),
            'description' => $this->faker->sentence(),
            'tujuan' => $this->faker->words(3, true),
            'metode_pembayaran' => $this->faker->randomElement(['Qris', 'Bank', 'Dana', 'Gopay', 'Cash']),
            'status' => $this->faker->randomElement(['draft', 'approved', 'paid']),
        ];
    }
}
