<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('maintenance_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained('lots')->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('owners')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('penalty_amount', 10, 2)->default(0.00);
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->date('due_date');
            $table->enum('status', ['pendiente', 'pagado', 'vencido', 'parcial'])->default('pendiente');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['lot_id', 'owner_id']);
            $table->index('status');
            $table->index(['month', 'year']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_fee_id')->nullable()->constrained('maintenance_fees')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['stripe', 'mercado_pago', 'transferencia', 'efectivo']);
            $table->string('transaction_id', 150)->nullable()->unique();
            $table->timestamp('payment_date');
            $table->enum('status', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->string('receipt_path', 255)->nullable();
            $table->foreignId('registered_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('maintenance_fee_id');
            $table->index('payment_date');
        });

        Schema::create('penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_fee_id')->constrained('maintenance_fees')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('reason', 255);
            $table->date('applied_at');
            $table->enum('status', ['pendiente', 'pagado', 'condonado'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalties');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('maintenance_fees');
    }
};
