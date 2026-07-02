<?php

namespace Database\Seeders;

use App\Models\Lot;
use App\Models\MaintenanceFee;
use App\Models\Payment;
use App\Models\Penalty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FinancialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@fracc.com')->first();
        
        $lot1 = Lot::where('number', 'LOT-01')->first(); // Juan
        $lot2 = Lot::where('number', 'LOT-02')->first(); // María
        $lot3 = Lot::where('number', 'LOT-03')->first(); // María (Lot 2)

        // 1. Fee Paid (Stripe) for Lot 1
        $fee1 = MaintenanceFee::create([
            'lot_id' => $lot1->id,
            'owner_id' => $lot1->owner_id,
            'amount' => 1500.00,
            'penalty_amount' => 0.00,
            'month' => 5,
            'year' => 2026,
            'due_date' => Carbon::create(2026, 5, 10),
            'status' => 'pagado',
            'notes' => 'Cuota ordinaria de Mayo.',
        ]);

        Payment::create([
            'maintenance_fee_id' => $fee1->id,
            'amount' => 1500.00,
            'payment_method' => 'stripe',
            'transaction_id' => 'ch_3M2q5vLkdIwHu7ix00123456',
            'payment_date' => Carbon::create(2026, 5, 8, 14, 30, 0),
            'status' => 'aprobado',
            'receipt_path' => 'receipts/LOT-01-2026-05.pdf',
            'registered_by' => null,
        ]);

        // 2. Fee Overdue (Vencido) for Lot 1 with Penalty
        $fee2 = MaintenanceFee::create([
            'lot_id' => $lot1->id,
            'owner_id' => $lot1->owner_id,
            'amount' => 1500.00,
            'penalty_amount' => 150.00, // 10% penalty
            'month' => 6,
            'year' => 2026,
            'due_date' => Carbon::create(2026, 6, 10),
            'status' => 'vencido',
            'notes' => 'Cuota ordinaria de Junio.',
        ]);

        Penalty::create([
            'maintenance_fee_id' => $fee2->id,
            'amount' => 150.00,
            'reason' => 'Recargo automático por morosidad 10%',
            'applied_at' => Carbon::create(2026, 6, 11),
            'status' => 'pendiente',
        ]);

        // 3. Fee Paid (Efectivo) for Lot 2
        $fee3 = MaintenanceFee::create([
            'lot_id' => $lot2->id,
            'owner_id' => $lot2->owner_id,
            'amount' => 1500.00,
            'penalty_amount' => 0.00,
            'month' => 5,
            'year' => 2026,
            'due_date' => Carbon::create(2026, 5, 10),
            'status' => 'pagado',
            'notes' => 'Pago recibido en oficinas de administración.',
        ]);

        Payment::create([
            'maintenance_fee_id' => $fee3->id,
            'amount' => 1500.00,
            'payment_method' => 'efectivo',
            'transaction_id' => 'EF-2026-0001',
            'payment_date' => Carbon::create(2026, 5, 10, 11, 0, 0),
            'status' => 'aprobado',
            'receipt_path' => 'receipts/LOT-02-2026-05.pdf',
            'registered_by' => $admin->id,
        ]);

        // 4. Fee Pending for Lot 3
        MaintenanceFee::create([
            'lot_id' => $lot3->id,
            'owner_id' => $lot3->owner_id,
            'amount' => 1500.00,
            'penalty_amount' => 0.00,
            'month' => 6,
            'year' => 2026,
            'due_date' => Carbon::create(2026, 6, 10),
            'status' => 'pendiente',
            'notes' => 'Cuota de Junio del segundo lote.',
        ]);
    }
}
