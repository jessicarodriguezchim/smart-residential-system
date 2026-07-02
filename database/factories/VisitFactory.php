<?php

namespace Database\Factories;

use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Visit>
 */
class VisitFactory extends Factory
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
            'visitor_name' => fake()->name(),
            'visitor_id_number' => fake()->bothify('ID-######'),
            'vehicle_plate' => fake()->bothify('???-####'),
            'entry_registered_by' => \App\Models\User::factory(),
            'exit_registered_by' => null,
            'entry_at' => now(),
            'exit_at' => null,
            'qr_code' => fake()->uuid(),
            'status' => 'activo',
            'notes' => fake()->sentence(),
        ];
    }
}
