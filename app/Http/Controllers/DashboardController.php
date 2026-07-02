<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Owner;
use App\Models\Visit;
use App\Models\MaintenanceFee;
use App\Models\Payment;
use App\Models\AuditLog;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Display the comprehensive dashboard.
     */
    public function index()
    {
        // 1. Fetch KPI metrics
        $totalLots = Lot::count();
        $soldLots = Lot::where('status', 'vendido')->count();
        $availableLots = Lot::where('status', 'disponible')->count();
        $totalOwners = Owner::where('status', 'activo')->count();
        
        // Active visits today
        $activeVisitsCount = Visit::where('status', 'activo')->count();
        
        // Financial metrics
        $totalIncome = Payment::where('status', 'aprobado')->sum('amount');
        $pendingFeesCount = MaintenanceFee::where('status', 'pendiente')->count();
        $overdueFeesCount = MaintenanceFee::where('status', 'vencido')->count();
        $totalPendingAmount = MaintenanceFee::whereIn('status', ['pendiente', 'vencido'])->sum('amount');

        // 2. Fetch lists for sections
        $lots = Lot::with('owner')->orderBy('number')->get();
        $owners = Owner::with('user')->orderBy('last_name')->get();
        
        $visits = Visit::with(['lot', 'entryRegisteredBy', 'exitRegisteredBy'])
            ->orderBy('entry_at', 'desc')
            ->take(15)
            ->get();
            
        $maintenanceFees = MaintenanceFee::with(['lot', 'owner'])
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $payments = Payment::with(['maintenanceFee.lot', 'registeredBy'])
            ->orderBy('payment_date', 'desc')
            ->take(10)
            ->get();

        $notifications = Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        $auditLogs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        // 3. User lists for dropdowns
        $usersList = User::all();
        $vigilanteUser = User::whereHas('roles', function($q) {
            $q->where('name', 'vigilante');
        })->first() ?? User::first();

        return view('dashboard', compact(
            'totalLots', 'soldLots', 'availableLots', 'totalOwners', 'activeVisitsCount',
            'totalIncome', 'pendingFeesCount', 'overdueFeesCount', 'totalPendingAmount',
            'lots', 'owners', 'visits', 'maintenanceFees', 'payments', 'notifications',
            'auditLogs', 'usersList', 'vigilanteUser'
        ));
    }

    /**
     * Register a new visit entry.
     */
    public function storeVisit(Request $request)
    {
        $request->validate([
            'lot_id' => 'required|exists:lots,id',
            'visitor_name' => 'required|string|max:150',
            'vehicle_plate' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        // Default to first user or dummy admin
        $userId = User::where('email', 'vigilante@fracc.com')->value('id') ?? User::first()->id;

        DB::beginTransaction();
        try {
            $visit = Visit::create([
                'lot_id' => $request->lot_id,
                'visitor_name' => $request->visitor_name,
                'vehicle_plate' => strtoupper($request->vehicle_plate),
                'entry_registered_by' => $userId,
                'entry_at' => Carbon::now(),
                'qr_code' => 'QR-' . strtoupper(Str::random(10)),
                'status' => 'activo',
                'notes' => $request->notes,
            ]);

            // Create notification for the lot owner (if any)
            $lot = Lot::with('owner')->find($request->lot_id);
            if ($lot && $lot->owner && $lot->owner->user_id) {
                Notification::create([
                    'user_id' => $lot->owner->user_id,
                    'type' => 'Visita Detectada',
                    'channel' => 'database',
                    'title' => 'Ingreso de visitante registrado',
                    'content' => "El visitante {$visit->visitor_name} con placas " . ($visit->vehicle_plate ?: 'N/A') . " ha ingresado con destino a tu domicilio ({$lot->number}).",
                    'sent_at' => Carbon::now(),
                ]);
            }

            // Audit log
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'registro_visita',
                'model_type' => Visit::class,
                'model_id' => $visit->id,
                'new_values' => json_encode($visit->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return back()->with('success', 'Visita registrada con éxito. Notificación enviada al propietario.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar visita: ' . $e->getMessage());
        }
    }

    /**
     * Register exit for a visit.
     */
    public function exitVisit(Request $request, $id)
    {
        $visit = Visit::findOrFail($id);
        
        // Default to first user or dummy admin
        $userId = User::where('email', 'vigilante@fracc.com')->value('id') ?? User::first()->id;

        DB::beginTransaction();
        try {
            $oldValues = $visit->toArray();
            
            $visit->update([
                'exit_at' => Carbon::now(),
                'exit_registered_by' => $userId,
                'status' => 'completado',
            ]);

            // Audit log
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'salida_visita',
                'model_type' => Visit::class,
                'model_id' => $visit->id,
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($visit->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return back()->with('success', 'Salida de la visita registrada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar salida: ' . $e->getMessage());
        }
    }

    /**
     * Record a manual payment for a maintenance fee.
     */
    public function payFee(Request $request)
    {
        $request->validate([
            'maintenance_fee_id' => 'required|exists:maintenance_fees,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:stripe,mercado_pago,transferencia,efectivo',
        ]);

        $fee = MaintenanceFee::findOrFail($request->maintenance_fee_id);
        $userId = User::where('email', 'admin@fracc.com')->value('id') ?? User::first()->id;

        DB::beginTransaction();
        try {
            // Update fee status
            $oldFeeValues = $fee->toArray();
            $fee->status = 'pagado';
            $fee->save();

            // Create Payment entry
            $payment = Payment::create([
                'maintenance_fee_id' => $fee->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                'payment_date' => Carbon::now(),
                'status' => 'aprobado',
                'registered_by' => $userId,
            ]);

            // Notification to owner
            if ($fee->owner && $fee->owner->user_id) {
                Notification::create([
                    'user_id' => $fee->owner->user_id,
                    'type' => 'Pago Aprobado',
                    'channel' => 'database',
                    'title' => 'Confirmación de pago de mantenimiento',
                    'content' => "Hemos registrado tu pago de $" . number_format($request->amount, 2) . " correspondiente a la cuota del mes {$fee->month}/{$fee->year} para el lote {$fee->lot->number} por el método {$request->payment_method}.",
                    'sent_at' => Carbon::now(),
                ]);
            }

            // Audit log
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'registro_pago',
                'model_type' => Payment::class,
                'model_id' => $payment->id,
                'new_values' => json_encode($payment->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return back()->with('success', 'Pago registrado y cuota marcada como Pagada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Create a new Maintenance Fee.
     */
    public function generateFee(Request $request)
    {
        $request->validate([
            'lot_id' => 'required|exists:lots,id',
            'amount' => 'required|numeric|min:0.01',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
            'due_date' => 'required|date',
        ]);

        $lot = Lot::findOrFail($request->lot_id);
        if (!$lot->owner_id) {
            return back()->with('error', 'No se puede generar cuota para un lote sin propietario asignado.');
        }

        $userId = User::where('email', 'admin@fracc.com')->value('id') ?? User::first()->id;

        DB::beginTransaction();
        try {
            $fee = MaintenanceFee::create([
                'lot_id' => $lot->id,
                'owner_id' => $lot->owner_id,
                'amount' => $request->amount,
                'penalty_amount' => 0.00,
                'month' => $request->month,
                'year' => $request->year,
                'due_date' => $request->due_date,
                'status' => 'pendiente',
            ]);

            // Notification
            if ($lot->owner->user_id) {
                Notification::create([
                    'user_id' => $lot->owner->user_id,
                    'type' => 'Nueva Cuota',
                    'channel' => 'database',
                    'title' => 'Nueva cuota de mantenimiento generada',
                    'content' => "Se ha generado la cuota de mantenimiento de $" . number_format($request->amount, 2) . " para el lote {$lot->number} correspondiente al mes {$request->month}/{$request->year}. Fecha de vencimiento: {$request->due_date}.",
                    'sent_at' => Carbon::now(),
                ]);
            }

            // Audit
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'generacion_cuota',
                'model_type' => MaintenanceFee::class,
                'model_id' => $fee->id,
                'new_values' => json_encode($fee->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return back()->with('success', 'Nueva cuota de mantenimiento generada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al generar cuota: ' . $e->getMessage());
        }
    }
}
