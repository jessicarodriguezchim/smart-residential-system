<?php

namespace Database\Factories;

use App\Models\MaintenanceFee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MaintenanceFee>
 */
class MaintenanceFeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lot_id' => \App\Models\Lot::factory(),
            'owner_id' => \App\Models\Owner::factory(),
            'amount' => 1500.00,
            'penalty_amount' => 0.00,
            'month' => fake()->numberBetween(1, 12),
            'year' => 2026,
            'due_date' => now()->addDays(10),
            'status' => 'pendiente',
            'notes' => fake()->sentence(),
        ];
    }
}
