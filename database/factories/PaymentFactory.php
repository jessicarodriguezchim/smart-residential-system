<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'maintenance_fee_id' => \App\Models\MaintenanceFee::factory(),
            'amount' => 1500.00,
            'payment_method' => fake()->randomElement(['stripe', 'mercado_pago', 'transferencia', 'efectivo']),
            'transaction_id' => fake()->uuid(),
            'payment_date' => now(),
            'status' => 'aprobado',
            'receipt_path' => null,
            'registered_by' => null,
        ];
    }
}
