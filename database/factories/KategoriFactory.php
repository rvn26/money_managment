<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kategori>
 */
class KategoriFactory extends Factory
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
            'nama' => $this->faker->unique()->word(),
            'emoji' => $this->faker->randomElement(['🍔', '🚗', '🔌', '👕', '🏥', '🎮', '📚', '💼', '🛒']),
            'warna' => $this->faker->safeHexColor(),
            'deskripsi' => $this->faker->sentence(),
        ];
    }
}
