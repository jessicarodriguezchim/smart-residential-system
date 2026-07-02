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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained('lots')->onDelete('cascade');
            $table->string('visitor_name', 150);
            $table->string('visitor_id_number', 50)->nullable();
            $table->string('vehicle_plate', 20)->nullable();
            $table->foreignId('entry_registered_by')->constrained('users');
            $table->foreignId('exit_registered_by')->nullable()->constrained('users');
            $table->timestamp('entry_at');
            $table->timestamp('exit_at')->nullable();
            $table->string('qr_code', 255)->nullable()->unique();
            $table->enum('status', ['pre_autorizado', 'activo', 'completado', 'cancelado'])->default('activo');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['lot_id', 'entry_at']);
            $table->index('status');
            $table->index('qr_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
