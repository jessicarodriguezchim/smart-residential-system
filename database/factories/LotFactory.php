<?php

namespace Database\Factories;

use App\Models\Lot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lot>
 */
class LotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => null,
            'number' => 'LOT-' . fake()->unique()->numberBetween(100, 999),
            'street' => fake()->streetName(),
            'surface_area' => fake()->randomFloat(2, 150, 400),
            'status' => fake()->randomElement(['disponible', 'vendido', 'apartado']),
            'notes' => fake()->sentence(),
        ];
    }
}
